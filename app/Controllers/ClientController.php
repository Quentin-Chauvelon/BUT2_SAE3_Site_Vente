<?php

namespace App\Controllers;

use App\Entities\Exemplaire;
use App\Entities\Favori;
use App\Models\ModeleClient;
use App\Entities\Client;
use App\Models\ModeleProduit;
use App\Models\ModeleFavori;
use App\Models\ModeleExemplaire;
use App\Models\ModeleCommande;
use App\Models\ModeleAdresse;
use App\Models\ModeleCoupon;
use Exception;
use App\Libraries\fpdf\fpdf;

require (APPPATH  . 'Libraries' . DIRECTORY_SEPARATOR . 'fpdf' . DIRECTORY_SEPARATOR . 'fpdf.php');


/**
 * ClientController est le contrôleur utilisé pour la majorité des actions faites par les utilisateurs lambda du site.
 */
class ClientController extends BaseController
{

    public function __construct()
    {
        $this->ModeleClient = ModeleClient::getInstance();
        $this->ModeleProduit = ModeleProduit::getInstance();
        $this->ModeleFavori = ModeleFavori::getInstance();
        $this->ModeleExemplaire = ModeleExemplaire::getInstance();
        $this->ModeleCommande = ModeleCommande::getInstance();
        $this->ModeleAdresse = ModeleAdresse::getInstance();
        $this->ModeleCoupon = ModeleCoupon::getInstance();
        $this->request = \Config\Services::request();
    }

    /**
     * Retourne la page quand on appuie sur le bouton monCompte.
     * Se connecter si on n'est pas connecté, sinon affiche la page monCompte.
     * @return string
     */
    public function monCompte(): string
    {
        // La vue du compte de l'utilisateur.
        if ($this->SessionExistante()) {
            return view("compte", array(
                "compteAction" => "profil",
                "emailDejaUtilise" => false,
                "session" => $this->getDonneesSession()
            ));
        }
        // La vue de connexion
        return view("creerCompte", array(
            "compteDejaExistant" => false,
            "passwordsDifferents" => false,
            "session" => $this->getDonneesSession()
        ));
    }

    /**
     * @return string La vue pour créer un compte.
     */
    public function inscription(): string
    {
        return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
    }

    /**
     * Crée un compte à partir des paramètres en post.
     * @return string La vue suivante.
     */
    public function creerCompte(): string
    {
        $email = $this->request->getPost('email');
        try {
            $result =  $this->ModeleClient->where('adresse_email', $email)->first();
        } catch (Exception) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        
        // si l'utilisateur a déjà un compte, on lui suggère de se connecter plutôt
        if ($result != NULL) {
            return view("creerCompte", array("compteDejaExistant" => true, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        // on récupère les deux mots de passe pour s'assurer qu'ils sont égaux
        $password = $this->request->getPost('password');
        $passwordRepetition = $this->request->getPost('passwordRepetition');

        // si les deux mots de passe sont différents, on retourne une erreur
        if ($password != $passwordRepetition) {        
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => true, "session" => $this->getDonneesSession()));
        }

        $prenom = $this->request->getPost('prenom');
        $nom = $this->request->getPost('nom');

        if ($prenom == "" || $nom == "") {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        // si les deux mot de passe sont égaux, on crée le compte
        $client = new Client();
        $client->adresse_email = $email;
        $client->nom = $nom;
        $client->prenom = $prenom;
        $client->password = $password;

        // on ajoute le client à la base de donnée
        try {
            $this->ModeleClient->insert($client);
        } catch (Exception) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }
        
        // on récupère l'id du client qui vient d'être créé
        $idClient = $this->ModeleClient->getInsertID();
        
        // on sauvegarde certaines données dans la session
        if ($this->request->getPost("rester_connecte") == "rester_connecte") {
            setcookie("idClient", (int)$idClient, time() + 60 * 60 * 24 * 30);
            setcookie("password", $password, time() + 60 * 60 * 24 * 30);
        }

        $this->setDonneesSession($idClient, $client->prenom, $client->nom, $email);

        return view("successCreationCompteClient");
    }

    /**
     * Retourne la page de connexion.
     * @return string La page de connexion
     */
    public function connexion(): string {
        return view("connexionCompte", array("compteNonExistant" => false, "passwordFaux" => false, "session" => $this->getDonneesSession()));
    }

    /**
     * Connecte un utilisateur qui a rentré son identifiant et son mot de passe, puis le redirige vers Home.
     * @return string
     */
    public function connexionCompte(): string {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $result =  $this->ModeleClient->getClientParEmail($email);

        // si l'utilisateur n'a pas encore de compte, on lui suggère d'en créer un
        if ($result == NULL) {
            return view("connexionCompte", array("compteNonExistant" => true, "passwordFaux" => false, "session" => $this->getDonneesSession()));
        }


        // si les mots de passe sont différents, alors on retourne une erreur
        if (!$result->checkPassword($password)) {
            return view("connexionCompte", array("compteNonExistant" => false, "passwordFaux" => true, "session" => $this->getDonneesSession()));
        }

        $id = $result->id_client;
        $prenom = $result->prenom;
        $nom = $result->nom;
        
        $this->setDonneesSession($id, $prenom, $nom, $email);

        if ($this->request->getPost("rester_connecte") == "rester_connecte") {
            setcookie("idClient", (int)$id, time() + 60 * 60 * 24 * 30);
            setcookie("password", $result->password, time() + 60 * 60 * 24 * 30);
        }

        return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
    }


    /**
     * Déconnecte un utilisateur.
     * @return string La vue Home
     */
    public function deconnexion(): string {
        $this->session->destroy();

        // on supprime le cookie de rester connecte s'il est présent
        if (isset($_COOKIE["idClient"]) && isset($_COOKIE["password"])) {
            unset($_COOKIE['idClient']);
            unset($_COOKIE['password']);
            setcookie('idClient', "", 1);
            setcookie('password', "", 1);
        }

        return view('home', array("estAdmin" => false, "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => array('panier' => array(), 'id'  => NULL, 'prenom' => NULL, 'nom' => NULL, 'email' => NULL)));
    }

    /**
     * Retourne la liste des favoris de l'utilisateur actuel.
     * @return Favori[] Les favoris de l'utilisateur connecté
     */
    public function getAllFavorisClient(): array {
        return $this->ModeleFavori->getFavorisClient($this->session->get('id'));
    }

    /**
     * Retourne la page des favoris de l'utilisateur actuel.
     * @return string La vue des favoris
     */
    public function afficherFavoris(): string
    {

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        $favoris = $this->getAllFavorisClient();

        $produits = array();

        foreach ($favoris as $favori) {
            $idFavori = $favori->id_produit;
            try{
                $produit = $this->ModeleProduit->find($idFavori);
            }
            catch (Exception) {
                continue;
            }
            $produits[] = $produit;
        }

        return view("compte", array("compteAction" => "favoris", "favoris" => $produits, "session" => $this->getDonneesSession()));
    }

    /**
     * Ajoute un produit aux favoris de l'utilisateur actuel.
     * @param int $idProduit L'id du produit à ajouter aux favoris
     * @param int $returnProduit 0 si on retourne la page des favoris, 1 si on retourne la page du produit
     * @return string La vue des favoris
     */
    public function ajouterFavori(int $idProduit, int $returnProduit) {

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }
        if ($this->estEnFavori($idProduit)) {
            $this->supprimerFavori($idProduit);
        } else
        {
            $this->ModeleFavori->ajouterFavori($this->session->get('id'), $idProduit);
        }
        // Rediriger sur la page des produits si $returnProduit == 1, sinon sur la page de tous les favoris.
        if ($returnProduit == 1) {
            return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => $this->estEnFavori($idProduit), "manqueExemplaire" => false, "session" => $this->getDonneesSession()));
        } else {
            return $this->afficherFavoris();
        }
    }

    /**
     * Retire un produit des favoris de l'utilisateur actuel.
     * @param int $idProduit L'id du produit
     * @return void
     */
    public function supprimerFavori(int $idProduit): void
    {
        try {
            $this->ModeleFavori->where('id_client', $this->getSessionId())->where('id_produit', $idProduit)->delete();
        }
        catch (Exception) {
            return;
        }
    }

    /**
     * Affiche la vue du panier de l'utilisateur actuel.
     * @param $coupon Le coupon utilisé ou null.
     * @param string $etatCoupon L'état du coupon utilisé ou vide.
     * @return string La vue
     */
    public function afficherPanierCoupon($coupon, string $etatCoupon): string
    {

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        $panier = $this->session->get("panier");

        $exemplairesUnique = array();
        $quantitesExemplaires = array();
        $produits = array();

        if($panier != null){

            // on compte la quantite de chaque exemplaire en fonction du produit, de la couleur et de la taille
            foreach ($panier as $exemplaire) {
                $exemplaireCle = $exemplaire->id_produit . $exemplaire->couleur . $exemplaire->taille;

                if (array_key_exists($exemplaireCle, $quantitesExemplaires)) {
                    $quantitesExemplaires[$exemplaireCle] += $exemplaire->quantite;
                } else {
                    $quantitesExemplaires[$exemplaireCle] = $exemplaire->quantite;
                    $exemplairesUnique[$exemplaireCle] = array(
                        "id_produit" => $exemplaire->id_produit,
                        "couleur" => $exemplaire->couleur,
                        "taille" => $exemplaire->taille
                    );
                }
            }

            foreach ($panier as $exemplaire) {
                $idProduit = $exemplaire->id_produit;
                try{
                    $produit = $this->ModeleProduit->find($idProduit);
                }
                catch (Exception) {
                    continue;
                }

                // si le produit n'a pas été trouvée, on renvoie sur la page d'accueil
                if ($produit == NULL) {
                    return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
                }

                if (!array_key_exists($idProduit, $produits)) {

                    $produits[$idProduit] = array(
                        "nom" => $produit->nom,
                        "prix" => $produit->prix
                    );
                }
            }
        }

        return view("compte", array(
            "compteAction" => "panier",
            "panier" => $exemplairesUnique,
            "quantitesExemplaires" => $quantitesExemplaires,
            "produits" => $produits,
            "coupon" => $coupon,
            "etatCoupon" => $etatCoupon,
            "session" => $this->getDonneesSession()
        ));
    }

    /**
     * Affiche la vue du panier sans coupon de réduction.
     * @return string La vue du panier
     */
    public function afficherPanier(): string
    {
        return $this->afficherPanierCoupon(NULL, "");
    }


    public function afficherHistorique() {
        try{
            $commandes = $this->ModeleCommande->where('id_client', $this->getSessionId())->findAll();
        }
        catch (Exception) {
            $commandes = array();
        }
        return view("compte", array("compteAction" => "historique", "commandes" => $commandes, "exemplaires" => $this->ModeleExemplaire->findAll(), "session" => $this->getDonneesSession()));
    }

    /**
     * Affiche la vue du compte de l'utilisateur actuel.
     * @return string La vue du compte
     */
    public function modifierProfil() {
        $email = $this->request->getPost('email');

        // si l'adresse mail indiquée est la même que celle déjà utilisée pour le compte, alors on l'enlève pour ne pas trouver de compte dans le select suivant
        if ($this->SessionExistante() && $this->session->get('email') == $email) {
            $email = "";
        }
        try{
            $result =  $this->ModeleClient->where('adresse_email', $email)->first();
        } catch (Exception) {
            $result = null;
        }
        // s'il existe déjà un autre compte avec l'adresse mail indiquée ou que la variable de session n'est pas définie, on renvoie une erreur
        if ($result != NULL || !$this->SessionExistante()) {
            return view("compte", array("compteAction" => "profil", "emailDejaUtilise" => true, "session" => $this->getDonneesSession()));
        }

        $prenom = $this->request->getPost('prenom');
        $nom = $this->request->getPost('nom');

        try {
            $clientAvant = $this->ModeleClient->where('adresse_email', $this->session->get("email"))->first();
        } catch (Exception) {
            $clientAvant = null;
        }

        if ($clientAvant != NULL) {
            $idClientAvant = $clientAvant->id_client;

            $client = array(
                'adresse_email' => ($email != "") ? $email : $clientAvant->adresse_email,
                'prenom' => ($prenom != "") ? $prenom : $clientAvant->prenom,
                'nom' => ($nom != "") ? $nom : $clientAvant->nom
                // 'password' => $clientAvant->password
            );
            try {
                $this->ModeleClient->update($idClientAvant, $client);
                $this->setDonneesSession($idClientAvant, $client['prenom'], $client['nom'], $client['adresse_email']);
            } catch (Exception) {}

        }
        return view("compte", array("compteAction" => "profil", "emailDejaUtilise" => false, "session" => $this->getDonneesSession()));
    }

    /**
     * Ajoute des produits au panier puis va sur la vue.
     * @return string
     */
    public function ajouterAuPanier(): string {

        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }
        $idProduit = $this->request->getPost('idProduit');
        $quantite = $this->request->getPost('quantite');
        $couleur = $this->request->getPost('couleur');
        $taille = $this->request->getPost('taille');

        try {
            $exemplaire = $this->ModeleExemplaire
                ->where('est_disponible', true)
                ->where('id_produit', $idProduit)
                ->where('couleur', $couleur)
                ->where('taille', $taille)
                ->first();
        } catch (Exception) {
            $exemplaire = null;
        }

        if ($exemplaire == NULL) {
            return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => $this->estEnFavori($idProduit), "manqueExemplaire" => true, "session" => $this->getDonneesSession()));
        }

        // on s'assure qu'il y a assez d'exemplaires pour la couleur et la taille donnée
        if ($exemplaire->quantite < $quantite) {
            return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => $this->estEnFavori($idProduit), "manqueExemplaire" => true, "session" => $this->getDonneesSession()));
        }

        // on récupère le panier de l'utilisateur
        $panier = $this->session->get("panier");

        $exempl = new Exemplaire(array(
            "id_produit" => $exemplaire->id_produit,
            "couleur" => $exemplaire->couleur,
            "taille" => $exemplaire->taille,
            "quantite" => $quantite,
            "est_disponible" => false
        ));

        $exemplaireDejaPresentDansPanier = false;
        // Vérifier si on a déjà la même chose dans le panier.
        foreach($panier as $exemplaire) {
            if ($exemplaire->id_produit == $exempl->id_produit && $exemplaire->couleur == $exempl->couleur && $exemplaire->taille == $exempl->taille) {
                $exemplaire->quantite = $exemplaire->quantite + (int)$exempl->quantite;
                $exemplaireDejaPresentDansPanier = true;
            }
        }

        if (!$exemplaireDejaPresentDansPanier) {
            $panier[] = $exempl;
        }

        // on sauvegarde le panier dans la session
        $this->session->set("panier", $panier);

        return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => true, "produitFavori" => $this->estEnFavori($idProduit), "manqueExemplaire" => false, "session" => $this->getDonneesSession()));
    }

    /**
     * Retire un exemplaire du panier puis va sur la vue.
     * @param int $idProduit L'id du produit à retirer du panier.
     * @param string $couleur Sa couleur
     * @param string $taille Sa taille
     * @return string La vue
     */
    public function supprimerDuPanier(int $idProduit, string $couleur, string $taille) {

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        $panier = $this->session->get("panier");
        $cleARetire = array();
        
        foreach ($panier as $key=>$exemplaire) {
            if ($exemplaire->id_produit == $idProduit && $exemplaire->couleur == $couleur && $exemplaire->taille == $taille) {
                $cleARetire[$key] = true;
            }
        }

        $panier = array_diff_key($panier, $cleARetire);
        $this->session->set("panier", $panier);

        return $this->afficherPanier();
    }


    public function appliquerCoupon(): string
    {
        
        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        $codePromo = $this->request->getPost('code_promo');
        $coupon = $this->ModeleCoupon->find($codePromo);

        if ($coupon == NULL) {
            return $this->afficherPanierCoupon(NULL, "invalide");
        } 
        else {
            $etatCoupon = "";

            if (!$coupon->est_valable) {
                $etatCoupon = "invalide";
            }
            else if ($coupon->date_limite < date("Y-m-d")) {
                $etatCoupon = "perime";
            }
            else {
                $etatCoupon = "valide";
            }

            return $this->afficherPanierCoupon($coupon, $etatCoupon);
        }
    }

    /**
     * Valide un panier et en fait une commande.
     * @param string $idCoupon L'identifiant du potentiel coupon à appliquer.
     * @return string
     */
    public function validerPanier(string $idCoupon) {

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        $panier = $this->session->get("panier");

        // Vérifier si le panier est vide.
        $nombreArticles = 0;

        foreach ($panier as $exemplaire) {
            $nombreArticles += $exemplaire->quantite;
        }

        if ($nombreArticles == 0) {
            return $this->afficherPanier();
        }
        try{
            $commandes = $this->ModeleCommande->findAll();
        } catch(Exception){
            $commandes = array();
        }

        $idClient = $this->getSessionId();
        $idCommande = NULL;

        // on regarde si l'utilisateur n'a pas déjà une commande en cours, sinon en crée une
        foreach($commandes as $commande) {
            if ($commande->id_client == $idClient && $commande->id_adresse == NULL && !$commande->est_validee) {
                $idCommande = $commande->id_commande;
            }
        }
        try{
            $coupon = $this->ModeleCoupon->find($idCoupon);
        } catch (Exception){
            $coupon = NULL;
        }


        // si l'utilisateur n'a pas de commande en cours, on en crée une
        if ($idCommande == NULL) {
            $commande = array(
                'id_client' => $idClient,
                'date_commande' => date("Ymd"),
                'date_livraison_estimee' => date("Ymd", mktime(0, 0, 0, date("m"), date("d")+15, date("Y"))),
                'date_livraison' => NULL,
                'id_coupon' => ($coupon != NULL) ? $coupon->id_coupon : NULL,
                'est_validee' => false,
                'montant' => 0,
                'id_adresse' => NULL
            );
            try {
                $this->ModeleCommande->insert($commande);

                // on récupère l'id de la commande qui vient d'être crée
                $idCommande = $this->ModeleCommande->getInsertID();
            } catch(Exception){}

        }

        // on applique le nouveau coupon à la commande si elle existait déjà
        else if ($coupon != NULL) {
            try{
                $this->ModeleCommande->update($idCommande, array("id_coupon" => $coupon->id_coupon));
            }
            catch (Exception){}
        }

        // on enlève tous les exemplaires de la commande au cas où la commande aurait été annulée et certains articles enlevés ou ajoutés
        $this->enleverExemplairesCommande($idCommande);

        // on trouve un exemplaire disponible correspondant à l'exemplaire et on modifie id_commande et est_disponible
        foreach ($panier as $exemplaire) {
            try {
                $exemplaireCommande = $this->ModeleExemplaire
                    ->where('est_disponible', true)
                    ->where('id_produit', $exemplaire->id_produit)
                    ->where('couleur', $exemplaire->couleur)
                    ->where('taille', $exemplaire->taille)
                    ->first();
            } catch (Exception){
                $exemplaireCommande = NULL;
            }


            if ($exemplaireCommande == NULL) {
                return $this->afficherPanier();
            }

            $this->ModeleExemplaire->ajouterExemplaireCommande($idCommande, $exemplaireCommande->id_exemplaire, $exemplaire->quantite);
        }
        try {
            $coupon = $this->ModeleCommande
                ->where('id_commande', $idCommande)
                ->first();
        }
        catch (Exception){
            $coupon = NULL;
        }

        if ($coupon != NULL && $coupon != "" && $coupon->id_coupon) {
            try{
                $coupon = $this->ModeleCoupon->find($coupon->id_coupon);
            } catch (Exception){
                $coupon = NULL;
            }


            if ($coupon != NULL) {
                if (!$coupon->est_valable || $coupon->date_limite < date("Y-m-d")) {
                    try {
                        $this->ModeleCommande->update($idCommande, array("id_coupon" => NULL));
                    } catch (Exception){}
                }
            }
        }
        else {
            try {
                $this->ModeleCommande->update($idCommande, array("id_coupon" => NULL));
            } catch (Exception){}
        }

        // on calcule le montant de la commande
        $montant = 0;

        foreach ($panier as $exemplaire) {
            $produit = $this->ModeleProduit->find($exemplaire->id_produit);

            if ($produit == NULL ||$exemplaire->id_produit == NULL) {
                continue;               
            }

            $montant += (int)$produit->prix * (int)$exemplaire->quantite;
        }

        try{
            $commande = $this->ModeleCommande
                ->where('id_commande', $idCommande)
                ->where('est_validee', false)
                ->first();
        } catch (Exception){
            $commande = NULL;
        }

        if ($commande == NULL) {
            return $this->afficherPanier();
        }

        // on applique le coupon au montant de la commande
        if ($commande->id_coupon != NULL) {
            $coupon = $this->ModeleCoupon->find($commande->id_coupon);

            if ($coupon != NULL) {
                if ($coupon->est_pourcentage) {
                    $montant *= (100 - $coupon->montant) / 100;
                } else {
                    $montant -= $coupon->montant;
                }
            }
        }

        $commande->montant = $montant;

        $this->ModeleCommande->save($commande);
        var_dump($this->ModeleCommande->find($commande->id_commande));

        return view("compte", array("compteAction" => "validerCommandeAdresse", "montant" => $montant, "nombreArticles" => $nombreArticles, "idCommande" => $idCommande, "adressesPrecendentes" => $this->ModeleAdresse->getAdressesParClient($idClient), "session" => $this->getDonneesSession()));
    }

    /**
     * Vide le panier et va sur la vue panier.
     * @return string La vue panier
     */
    public function viderPanier(): string {
        $this->session->set("panier", array());

        return $this->afficherPanier();
    }

    /** Annule une commande et retourne sur le panier.
     * @param int $idCommande
     * @return string
     */
    public function annulerCommande(int $idCommande): string
    {

        // on enlève tous les exemplaires de la commande au cas où la commande aurait été annulée et certains articles enlevés ou ajoutés
        $this->enleverExemplairesCommande($idCommande);

        return $this->afficherPanier();
    }

    /**
     * Lit l'adresse renseignée par l'utilisateur lors de la commande.
     * @return string
     */
    public function adresseCommande() {
        $idCommande = $this->request->getPost('idCommande');

        $rue = $this->request->getPost('rue');
        $codePostal = (int)$this->request->getPost('codePostal');
        $ville = $this->request->getPost('ville');

        if (count($this->session->get("panier")) == 0) {
            return $this->afficherPanier();
        }

        // on regarde si le client a réutilisé une adresse
        try {
            $idAdresse = $this->ModeleAdresse
                ->where('rue', $rue)
                ->where('code_postal', $codePostal)
                ->where('ville', $ville)
                ->first();
        } catch (Exception){
            $idAdresse = NULL;
        }


        if ($idAdresse != NULL) {
            $idAdresse = $idAdresse->id_adresse;
        }

        // si le client utilise une nouvelle adresse, on en crée une
        else {
            $adresse = array(
                'id_adresse' => 0,
                'code_postal' => $codePostal,
                'ville' => $ville,
                'rue' => $rue
            );

            // on ajoute l'adresse dans la table
            try {
                $this->ModeleAdresse->insert($adresse);
                $idAdresse = $this->ModeleAdresse->getInsertID();
            } catch (Exception){
                $idAdresse = NULL;
            }
        }

        if ($idAdresse != NULL) {
            // on modifie l'adresse de la commande et on la valide
            $commandeModifiee = array(
                'id_adresse' => $idAdresse,
                'est_validee' => true
            );
            try{
                $this->ModeleCommande->update($idCommande, $commandeModifiee);
            } catch (Exception){}
            }

        // on vide le panier
        $this->session->set("panier", array());

        return view("commandeValidee", array("commande" => $this->ModeleCommande->where('id_commande', $idCommande)->first()));
    }

    /**
     * Affiche la page d'une commande.
     * @param $idCommande int
     * @return string
     */
    public function detailCommande(int $idCommande) {
        try {
            $commande = $this->ModeleCommande
                ->where('id_commande', $idCommande)
                ->first();
        }
        catch (Exception){
            $commande = NULL;
        }

        // si la commande n'a pas été trouvée, on renvoie sur la page d'accueil
        if ($commande == NULL) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }
        
        $adresse = NULL;

        // on récupère l'adresse de la commande s'il y en a une
        if ($commande->id_adresse != NULL) {
            $adresse = $this->ModeleAdresse->find($commande->id_adresse);
        }


        $exemplaires = array();

        // on récupère tous les exemplaires de la commande
        foreach ($this->ModeleExemplaire->findAll() as $exemplaire) {
            if ($exemplaire->id_commande == $idCommande) {
                $exemplaires[] = $exemplaire;
            }
        }

        // si aucun exemplaire n'a été trouvé, on renvoie sur la page d'accueil
        if (count($exemplaires) == 0) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }


        $exemplairesUnique = array();
        $quantitesExemplaires = array();
        $produits = array();

        // on compte la quantite de chaque exemplaire en fonction du produit, de la couleur et de la taille
        foreach ($exemplaires as $exemplaire) {
            $exemplaireCle = $exemplaire->id_produit . $exemplaire->couleur . $exemplaire->taille;

            if (array_key_exists($exemplaireCle, $quantitesExemplaires)) {
                $quantitesExemplaires[$exemplaireCle] += $exemplaire->quantite;
            } else {
                $quantitesExemplaires[$exemplaireCle] = $exemplaire->quantite;
                $exemplairesUnique[$exemplaireCle] = array(
                    "id_produit" => $exemplaire->id_produit,
                    "couleur" => $exemplaire->couleur,
                    "taille" => $exemplaire->taille
                );
            }
        }
        // Rajouter les produits.
        foreach ($exemplaires as $exemplaire) {
            $idProduit = $exemplaire->id_produit;
            try {
                $produit = $this->ModeleProduit->where('id_produit', $idProduit)->first();
            } catch (Exception){
                $produit = NULL;
            }


            // si le produit n'a pas été trouvée, on renvoie sur la page d'accueil
            if ($produit == NULL) {
                return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
            }

            if (!array_key_exists($idProduit, $produits)) {
                
                $produits[$idProduit] = array(
                    "nom" => $produit->nom,
                    "prix" => $produit->prix
                );
            }
        }

        return view("compte", array("compteAction" => "detailCommande", "commande" => $commande, "adresse" => $adresse, "exemplaires" => $exemplairesUnique, "quantitesExemplaires" => $quantitesExemplaires, "produits" => $produits, "session" => $this->getDonneesSession()));
    }

    /**
     * @return string La page cgu
     */
    public function cgu() {
        return view("cgu", array("session" => $this->getDonneesSession()));
    }

    /**
     * @return string La page qui sommes nous
     */
    public function quiSommesNous() {
        return view("quiSommesNous", array("session" => $this->getDonneesSession()));
    }

    /**
     * @return string La page contact
     */
    public function contact() {
        return view("contact", array("session" => $this->getDonneesSession()));
    }

    /**
     * Utilise la barre en bas de la page pour envoyer un mail d'avis sur le site.
     * @return string Home
     */
    public function avis(): string
    {
        $to = "quentin.chauvelon@etu.univ-nantes.fr";
        $subject = "Avis du " . date("d/m/Y \a H:i:s");
        $message = wordwrap($this->request->getPost('avis'), 70, "\n", true);

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        mail($to, $subject, $message, $headers);

        return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
    }

    /**
     * Envoie un mail.
     * @return string Home
     */
    public function messageContact() {
        $from = $this->request->getPost('from');
        $to = "quentin.chauvelon@etu.univ-nantes.fr";
        $subject = $this->request->getPost('subject');
        $message = wordwrap($this->request->getPost('message'), 70, "\n", true);

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <' . $from . '>' . "\r\n";

        mail($to, $subject, $message, $headers);

        return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
    }

    /**
     * Envoie un mail permettant de réinitialiser son mot de passe.
     * @return string La page mdp oublié
     */
    public function envoyerMailChangementMDP()
    {
        $client = $this->ModeleClient->getClientParEmail($this->request->getPost('email'));

        if ($client == NULL){
            return view('motDePasseOublie', ['compteNonExistant' => true]);
        }
        $code = $client->getCodeMDPOublie();
        $lien = url_to("ClientController::ChangerMotDePasse", '') . urlencode($code);
        $to = $client->adresse_email;
        $subject = "Hotgenre : Changement de mot de passe";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <hotgenre@ne-pas-repondre.fr>' . "\r\n";
        $message = "Bonjour " . $client->prenom . " " . $client->nom. ". \n"
        . "Vous avez demandé à changer votre mot de passe sur notre site. \n" .
        "Veuillez cliquer sur ce lien pour le réinitialiser :\n<a href=" .
        $lien . ">Réinitialiser mon mot de passe</a>\n" . "Si vous n'avez pas demandé à changer votre mot de passe, veuillez ignorer ce mail.\n" .
        "Merci de la confiance que vous accordez à nos services, \n" . "L'équipe Hotgenre.";

        mail($to, $subject, $message, $headers);
        return view('motDePasseOublie', ['compteNonExistant' => false]);
    }

    /**
     * Reçoit le lien du mail de changement de mdp et va sur la page pour le réinitialiser.
     * @return string La page de réinitialisation de mot de passe
     */
    public function ChangerMotDePasse($codeMDPOublie)
    {
        $client = $this->ModeleClient->getClientParCodeMDPOublie(urldecode($codeMDPOublie));

        if ($client == NULL){
            return view('motDePasseOublie', array('compteNonExistant' => true));
        }

        return view('changerMotDePasse', array("idClient" => $client->id_client, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
    }


    public function reinitialiserMotDePasse() {
        // on récupère les deux mots de passe pour s'assurer qu'ils sont égaux
        $idClient = $this->request->getPost('id_client');
        $password = $this->request->getPost('password');
        $passwordRepetition = $this->request->getPost('passwordRepetition');

        $client = $this->ModeleClient->find($idClient);

        if ($client == NULL || $idClient == NULL) {
            return view("connexionCompte", array("compteNonExistant" => false, "passwordFaux" => false, "session" => $this->getDonneesSession()));
        }

        // si les deux mot de passe sont différents, on retourne une erreur
        if ($password != $passwordRepetition || strlen($password) <= 8 || strlen($password) > 64) {        
            return view('changerMotDePasse', array("idClient" => $idClient, "passwordsDifferents" => true, "session" => $this->getDonneesSession()));
        }

        $client->password = $password;
        $this->ModeleClient->save($client);

        $this->setDonneesSession($client->id_client, $client->prenom, $client->nom, $client->adresse_email);

        return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
    }


    public function motDePasseOublie()
    {
        return view('motDePasseOublie');
    }


    public function facture(int $idCommande) {

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        $commande = $this->ModeleCommande->find($idCommande);

        if ($commande == NULL) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $idFacture = substr("0000" . (string)$idCommande, -5);
        $prenom = $this->session->get("prenom");
        $nom = $this->session->get("nom");

        $adresse = $this->ModeleAdresse->find($commande->id_adresse);

        if ($adresse == NULL || $commande->id_adresse == NULL) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $rue = $adresse->rue;
        $codePostal = $adresse->code_postal;
        $ville = $adresse->ville;

        $total = 0;

        $exemplaires = $this->ModeleExemplaire
            ->where('id_commande', $idCommande)
            ->findAll();

        if ($exemplaires == NULL) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetTextColor(46);
        $pdf->SetFont('Helvetica','B',30);
        $pdf->Ln(10);
        $pdf->Cell(40,10,'Hot Genre');
        $pdf->Ln(30);

        // $pdf->Image(site_url() . "images/logos/logo hg noir.png", 20, 160, 30, 30, "png", "http://172.26.82.56");
        $pdf->SetFont('Helvetica','B',15);
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', "Envoyé à"));
        $pdf->SetFont('Helvetica','B',15);
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', " N° de facture                       "), 0, 0, "R");
        $pdf->SetFont('Helvetica','',14);
        $pdf->Cell(0, 10, $idCommande, 0, 0, "R");
        $pdf->Ln(8);

        $pdf->SetFont('Helvetica','',14);
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', $prenom . " " . $nom));
        $pdf->SetFont('Helvetica','B',15);
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', "          Date                       "), 0, 0, "R");
        $pdf->SetFont('Helvetica','',14);
        $pdf->Cell(0, 10, date("d/m/Y"), 0, 0, "R");
        $pdf->Ln(7);

        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', $rue));
        $pdf->Ln(1);
        $pdf->SetFont('Helvetica','B',15);
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', "N° de commande                       "), 0, 0, "R");
        $pdf->SetFont('Helvetica','',14);
        $pdf->Cell(0, 10, $idCommande, 0, 0, "R");

        $pdf->Ln(6);
        $pdf->Cell(0,10, iconv('UTF-8', 'windows-1252', (string)$codePostal . ", " . $ville));

        $pdf->Ln(1);
        $pdf->SetFont('Helvetica','B',15);
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', "      Echéance                       "), 0, 0, "R");
        $pdf->SetFont('Helvetica','',14);
        $pdf->Cell(0, 10, date("d/m/Y", mktime(0, 0, 0, date("m") + 4, date("d"), date("Y"))), 0, 0, "R");

        $pdf->Ln(20);
        $pdf->SetFillColor(100);
        $pdf->SetTextColor(255);
        $pdf->SetFont('Helvetica','B',14);
        $pdf->Cell(35, 12, iconv('UTF-8', 'windows-1252', "    Quantité"), 0, 0, "C", true);
        $pdf->Cell(85, 12, "Nom", 0, 0, "C", true);
        $pdf->Cell(35, 12, "Prix unitaire", 0, 0, "C", true);
        $pdf->Cell(35, 12, "Prix", 0, 0, "C", true);


        $i = 0;

        foreach ($exemplaires as $exemplaire) {

            if ($i % 2 == 0) {
                $pdf->SetFillColor(255);
            } else {
                $pdf->SetFillColor(242);
            }

            $i += 1;

            $produit = $this->ModeleProduit->find((int)$exemplaire->id_produit);

            if ($produit == NULL) {
                continue;
            }

            $pdf->Ln(12);
            $pdf->SetTextColor(46);
            $pdf->SetFont('Helvetica','',14);
            $pdf->Cell(35, 12, $exemplaire->quantite, 0, 0, "C", true);
            $pdf->Cell(85, 12, $produit->nom . "(couleur : " . $exemplaire->couleur . ", taille : " . $exemplaire->taille . ")" , 0, 0, "L", true);
            $pdf->Cell(35, 12, ($produit->prix / 100) . chr(128), 0, 0, "C", true);
            $pdf->Cell(35, 12, (($produit->prix * $exemplaire->quantite) / 100) . chr(128), 0, 0, "C", true);

            $total += $produit->prix * $exemplaire->quantite;
        }

        $pdf->Ln(14);
        $pdf->SetFont('Helvetica','B',14);
        $pdf->Cell(120, 10, "", 0, 0, "C");
        $pdf->Cell(35, 10, "Total", 0, 0, "C");
        $pdf->Cell(35, 10, ($total / 100) . chr(128), 0, 0, "C");

        header('Content-Type: application/pdf');
        return $pdf->Output("D", 'facture_' . $idCommande . '.pdf');
    }

    /**
     * Enlève tous les exemplaires d'une commande.
     * @param $idCommande
     * @return void
     */
    public function enleverExemplairesCommande($idCommande): void
    {
        try {
            $exemplaire = $this->ModeleExemplaire->where('id_commande', $idCommande)->first();
        } catch (Exception) {
            $exemplaire = NULL;
        }
        //
        while ($exemplaire != NULL) {
            try {
                $this->ModeleExemplaire->delete($exemplaire->id_exemplaire);
            } catch (Exception) {
            }
            try {
                $exemplaireAvecProduitCouleurTaille = $this->ModeleExemplaire
                    ->where('id_commande', $idCommande)
                    ->where('est_disponible', true)
                    ->where('id_produit', $exemplaire->id_produit)
                    ->where('couleur', $exemplaire->couleur)
                    ->where('taille', $exemplaire->taille)
                    ->first();
            } catch (Exception) {
                $exemplaireAvecProduitCouleurTaille = NULL;
            }

            // s'il y a déjà un exemplaire du même produit avec la même couleur et la même taille, on augmente la quantité, sinon on en crée un
            if ($exemplaireAvecProduitCouleurTaille != NULL) {
                try {
                    $this->ModeleExemplaire
                        ->where('id_exemplaire', $exemplaireAvecProduitCouleurTaille->id_exemplaire)
                        ->set(['quantite' => (int)$exemplaireAvecProduitCouleurTaille->quantite + (int)$exemplaire->quantite])
                        ->update();
                } catch (Exception) {
                }
            } else {
                $this->ModeleExemplaire->creerExemplaire($exemplaire->id_produit, $exemplaire->couleur, $exemplaire->taille, $exemplaire->quantite);
            }
            try {
                $exemplaire = $this->ModeleExemplaire->where('id_commande', $idCommande)->first();
            } catch (Exception) {
                $exemplaire = NULL;
            }
        }
    }
}

// vérifier les strings vides et les tailles dans les posts
// sauter ligne pour les return view array pour que ce soit plus lisible ?
// au lieu de faire plusieurs return view, en avoir un seul dans un controller et appeler la méthode
// rajouter try catch sur le code que j'ai ajouté aux controllers

// home.html, home.php.save, backup.php, adminView.php.save, adminView.php.save.1
// coupon
// captcha
// tester coupon
// ajouter pour les images .jpeg

// envoyer mail pour dire bravo t'es inscrit + autre ?

// max utilisations coupon ?
// pas commenter modele produit ?

// image collection
// s'assurer que les inputs sont sécurisés (appels du bon type, non vide...) surtout les posts, vérifier les appels à la bd...
// custom 404
// désactiver les erreurs + page spéciale ?