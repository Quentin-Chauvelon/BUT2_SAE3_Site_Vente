<?php

namespace App\Controllers;

use App\Entities\Exemplaire;
use App\Models\ModeleClient;
use App\Entities\Client;
use App\Models\ModeleProduit;
use App\Models\ModeleFavori;
use App\Models\ModeleExemplaire;
use App\Models\ModeleCommande;
use App\Models\ModeleAdresse;
use App\Models\ModeleCoupon;
use App\Libraries\fpdf\fpdf;

require (APPPATH  . 'Libraries' . DIRECTORY_SEPARATOR . 'fpdf' . DIRECTORY_SEPARATOR . 'fpdf.php');


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

        // $this->session = \Config\Services::session();
        // $this->session->start();
        
        $this->request = \Config\Services::request();
    }


    public function monCompte() {
        if ($this->SessionExistante()) {
            return view("compte", array(
                "compteAction" => "profil",
                "emailDejaUtilise" => false,
                "session" => $this->getDonneesSession()
            ));
        }

        else {
            return view("creerCompte", array(
                "compteDejaExistant" => false,
                "passwordsDifferents" => false,
                "session" => $this->getDonneesSession()
            ));
        }
    }


    public function inscription() {
        return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
    }


    public function creerCompte()
    {
        $email = $this->request->getPost('email');
        $result =  $this->ModeleClient->where('adresse_email', $email)->first();
        
        // si l'utilisateur a déjà un compte, on lui suggère de se connecter plutôt
        if ($result != NULL) {
            return view("creerCompte", array("compteDejaExistant" => true, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        // on récupère les deux mots de passe pour s'assurer qu'ils sont égaux
        $password = $this->request->getPost('password');
        $passwordRepetition = $this->request->getPost('passwordRepetition');

        // si les deux mot de passe sont différents, on retourne une erreur
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
        $this->ModeleClient->insert($client);
        
        // on récupère l'id du client qui vient d'être créé
        $idClient = $this->ModeleClient->getInsertID();
        // $idClient = $this->ModeleClient->where('adresse_email', $email)->first()->id_client;
        
        // on sauvegarde certaines données dans la session
        if ($this->request->getPost("rester_connecte") == "rester_connecte") {
            setcookie("idClient", (int)$idClient, time() + 60 * 60 * 24 * 30);
            setcookie("password", $password, time() + 60 * 60 * 24 * 30);
        }

        $this->setDonneesSession($idClient, $client->prenom, $client->nom, $email);

        return view("succesCreationCompteClient");
    }


    public function connexion() {
        return view("connexionCompte", array("compteNonExistant" => false, "passwordFaux" => false, "session" => $this->getDonneesSession()));
    }


    public function connexionCompte() {
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


    public function deconnexion() {
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


    public function getAllFavorisClient(): array {
        return $this->ModeleFavori->getFavorisClient($this->session->get('id'));
    }


    public function afficherFavoris() {

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
            catch (\Exception $e) {
                continue;
            }
            $produits[] = $produit;
        }

        return view("compte", array("compteAction" => "favoris", "favoris" => $produits, "session" => $this->getDonneesSession()));
    }


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
        if ($returnProduit == 1) {
            return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => $this->estEnFavori($idProduit), "manqueExemplaire" => false, "session" => $this->getDonneesSession()));
        } else {
            return $this->afficherFavoris();
        }
    }


    public function supprimerFavori(int $idProduit) {
        try {
            $this->ModeleFavori->where('id_client', $this->getSessionId())->where('id_produit', $idProduit)->delete();
        }
        catch (\Exception $e) {
            return;
        }
    }


    public function afficherPanierCoupon($coupon, string $etatCoupon) {

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
                $exemplaireCle = (string)$exemplaire->id_produit . $exemplaire->couleur . $exemplaire->taille;

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
                $produit = $this->ModeleProduit->where('id_produit', $idProduit)->first();

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


    public function afficherPanier() {
        return $this->afficherPanierCoupon(NULL, "");
    }


    public function afficherHistorique() {
        $commandes = $this->ModeleCommande->findAll();

        $commandesClient = array();
        $idClient = $this->getSessionId();

        foreach($commandes as $commande) {
            $idCommande = $commande->id_client;

            if ($idCommande == $idClient) {
                $commandesClient[] = $commande;
            }
        }

        return view("compte", array("compteAction" => "historique", "commandes" => $commandesClient, "exemplaires" => $this->ModeleExemplaire->findAll(), "session" => $this->getDonneesSession()));
    }


    public function modifierProfil() {
        $email = $this->request->getPost('email');

        // si l'adresse mail indiquée est la même que celle déjà utilisée pour le compte, alors on l'enlève pour ne pas trouver de compte dans le select suivant
        if ($this->SessionExistante() && $this->session->get('email') == $email) {
            $email = "";
        }

        $result =  $this->ModeleClient->where('adresse_email', $email)->first();

        // s'il existe déjà un autre compte avec l'adresse mail indiquée ou que la variable de session n'est pas définie, on renvoie une erreur
        if ($result != NULL || !$this->SessionExistante()) {
            return view("compte", array("compteAction" => "profil", "emailDejaUtilise" => true, "session" => $this->getDonneesSession()));
        }

        $prenom = $this->request->getPost('prenom');
        $nom = $this->request->getPost('nom');

        $clientAvant = $this->ModeleClient->where('adresse_email', $this->session->get("email"))->first();

        if ($clientAvant != NULL) {
            $idClientAvant = $clientAvant->id_client;

            $client = array(
                'adresse_email' => ($email != "") ? $email : $clientAvant->adresse_email,
                'prenom' => ($prenom != "") ? $prenom : $clientAvant->prenom,
                'nom' => ($nom != "") ? $nom : $clientAvant->nom
                // 'password' => $clientAvant->password
            );

            $this->ModeleClient->update($idClientAvant, $client);

            $this->setDonneesSession($idClientAvant, $client['prenom'], $client['nom'], $client['adresse_email']);
        }

        return view("compte", array("compteAction" => "profil", "emailDejaUtilise" => false, "session" => $this->getDonneesSession()));
    }


    public function ajouterAuPanier() {
        // $idProduit = $this->request->getPost('idProduit');

        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }
        
        // $commandes = $this->ModeleCommande->findAll();
        // $idCommandeClient = NULL;

        // $idClient = $this->getSessionId();

        // foreach($commandes as $commande) {
        //     $idCommande = $commande->id_client;

        //     if ($idCommande == $idClient) {
        //         $idCommandeClient = $idCommande;
        //     }
        // }

        // s'il n'existe pas de commande pour l'utilisateur, on en crée une
        // if (!$idCommandeClient) {
        //     $commandes = $this->ModeleCommande->insert(array('id_client' => $idClient));
        // }

        $idProduit = $this->request->getPost('idProduit');
        $quantite = $this->request->getPost('quantite');
        $couleur = $this->request->getPost('couleur');
        $taille = $this->request->getPost('taille');

        $exemplaire = $this->ModeleExemplaire
            ->where('est_disponible', true)
            ->where('id_produit', $idProduit)
            ->where('couleur', $couleur)
            ->where('taille', $taille)
            ->first();

        if ($exemplaire == NULL) {
            return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => $this->estEnFavori($idProduit), "manqueExemplaire" => true, "session" => $this->getDonneesSession()));
        }

        // on s'assure qu'il y assez d'exemplaires pour la couleur et la taille donnée
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

        foreach($panier as $key=>$exemplaire) {
            if ($exemplaire->id_produit == $exempl->id_produit && $exemplaire->couleur == $exempl->couleur && $exemplaire->taille == $exempl->taille) {
                $panier[$key]->quantite = $panier[$key]->quantite + (int)$exempl->quantite; 
                $exemplaireDejaPresentDansPanier = true;
            }
        }

        if (!$exemplaireDejaPresentDansPanier) {
            $panier[] = $exempl;
        }

        // on sauvegarde le panier dans la session
        $this->session->set("panier", $panier);

//        // si certains exemplaires n'étaient pas disponibles
//        if ($quantite != 0) {
//            return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => $this->estEnFavori($idProduit), "manqueExemplaire" => true, "session" => $this->getDonneesSession()));
//        }

        return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => true, "produitFavori" => $this->estEnFavori($idProduit), "manqueExemplaire" => false, "session" => $this->getDonneesSession()));
    }


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


    public function appliquerCoupon() {
        
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


    public function validerPanier($idCoupon = "") {

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        $idClient = $this->getSessionId();
        $panier = $this->session->get("panier");

        $nombreArticles = 0;

        foreach ($panier as $exemplaire) {
            $nombreArticles += $exemplaire->quantite;
        }

        if ($nombreArticles == 0) {
            return $this->afficherPanier();
        }

        $commandes = $this->ModeleCommande->findAll();
        $idClient = $this->getSessionId();
        $idCommande = NULL;

        // on regarde si l'utiliateur n'a pas déjà une commande en cours, sinon en crée une
        foreach($commandes as $commande) {
            if ($commande->id_client == $idClient && $commande->id_adresse == NULL && !$commande->est_validee) {
                $idCommande = $commande->id_commande;
            }
        }

        $coupon = $this->ModeleCoupon->find($idCoupon);

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

            $this->ModeleCommande->insert($commande);

            // on récupère l'id de la commande qui vient d'être crée
            $idCommande = $this->ModeleCommande->getInsertID();
        }

        // on applique le nouveau coupon à la commande si elle existait déjà
        elseif ($coupon != NULL) {
            $this->ModeleCommande->update($idCommande, array("id_coupon" => $coupon->id_coupon));
        }

        // on enlève tous les exemplaires de la commande au cas où la commande aurait été annulée et certains articles enlevés ou ajoutés
        $exemplaire = $this->ModeleExemplaire->where('id_commande', $idCommande)->first();

        while ($exemplaire != NULL) {
            $this->ModeleExemplaire->delete($exemplaire->id_exemplaire);

            $exemplaireAvecProduitCouleurTaille = $this->ModeleExemplaire
                ->where('id_commande', $idCommande)
                ->where('est_disponible', true)
                ->where('id_produit', $exemplaire->id_produit)
                ->where('couleur', $exemplaire->couleur)
                ->where('taille', $exemplaire->taille)
                ->first();

            // s'il y a déjà un exemplaire du même produit avec la même couleur et la même taille, on augmente la quantité, sinon on en crée un
            if ($exemplaireAvecProduitCouleurTaille != NULL) {
                $this->ModeleExemplaire
                ->where('id_exemplaire', $exemplaireAvecProduitCouleurTaille->id_exemplaire)
                ->set(['quantite' => (int)$exemplaireAvecProduitCouleurTaille->quantite + (int)$exemplaire->quantite])
                ->update();
            }
            elseif ($exemplaire->quantite > 0) {
                $this->ModeleExemplaire->creerExemplaire($exemplaire->id_produit, $exemplaire->couleur, $exemplaire->taille, $exemplaire->quantite);
            }

            $exemplaire = $this->ModeleExemplaire->where('id_commande', $idCommande)->first();
        }

        // on ajoute tous les articles à la commande
        // $exemplaireModifiee = array(
        //     'id_commande' => $idCommande,
        //     'est_disponible' => false
        // );

        // on trouve un exemplaire disponible correspondant à l'exemplaire et on modifie id_commande et est_disponible
        foreach ($panier as $exemplaire) {
            $exemplaireCommande = $this->ModeleExemplaire
                ->where('est_disponible', true)
                ->where('id_produit', $exemplaire->id_produit)
                ->where('couleur', $exemplaire->couleur)
                ->where('taille', $exemplaire->taille)
                ->first();

            if ($exemplaireCommande == NULL) {
                return $this->afficherPanier();
            }

            $this->ModeleExemplaire->ajouterExemplaireCommande($idCommande, $exemplaireCommande->id_exemplaire, $exemplaire->quantite);
        }

        // on s'assure que le coupon est valide (valable, non périmé)

        if ($coupon != NULL) {
            $coupon = "";
            $idCoupon = $this->ModeleCommande
                ->where('id_commande', $idCommande)
                ->first();

            if ($idCoupon != NULL && $idCoupon->id_coupon != NULL && $idCoupon != "") {
                $coupon = $this->ModeleCoupon->find($idCoupon->id_coupon);

                if ($coupon != NULL) {
                    if (!$coupon->est_valable || $coupon->date_limite < date("Y-m-d")) {
                        $this->ModeleCommande->update($idCommande, array("id_coupon" => ""));
                    }
                }
            }
        }
        else {
            $this->ModeleCommande->update($idCommande, array("id_coupon" => NULL));
        }

        // on calcule le montant de la commande
        $this->ModeleCommande->CalculerMontant($idCommande);

        $commande = $this->ModeleCommande
            ->where('id_commande', $idCommande)
            ->where('est_validee', false)
            ->first();

        if ($commande == NULL) {
            return $this->afficherPanier();
        }

        $montant = $commande->montant;

        return view("compte", array("compteAction" => "validerCommandeAdresse", "montant" => $montant, "nombreArticles" => $nombreArticles, "idCommande" => $idCommande, "adressesPrecendentes" => $this->ModeleAdresse->getAdressesParClient($idClient), "session" => $this->getDonneesSession()));
    }


    public function viderPanier() {
        $this->session->set("panier", array());

        return $this->afficherPanier();
    }


    public function annulerCommande($idCommande) {

        // on enlève tous les exemplaires de la commande au cas où la commande aurait été annulée et certains articles enlevés ou ajoutés
        $exemplaire = $this->ModeleExemplaire->where('id_commande', $idCommande)->first();

        while ($exemplaire != NULL) {
            $this->ModeleExemplaire->delete($exemplaire->id_exemplaire);

            $exemplaireAvecProduitCouleurTaille = $this->ModeleExemplaire
                ->where('id_commande', $idCommande)
                ->where('est_disponible', true)
                ->where('id_produit', $exemplaire->id_produit)
                ->where('couleur', $exemplaire->couleur)
                ->where('taille', $exemplaire->taille)
                ->first();

            // s'il y a déjà un exemplaire du même produit avec la même couleur et la même taille, on augmente la quantité, sinon on en crée un
            if ($exemplaireAvecProduitCouleurTaille != NULL) {
                $this->ModeleExemplaire
                ->where('id_exemplaire', $exemplaireAvecProduitCouleurTaille->id_exemplaire)
                ->set(['quantite' => (int)$exemplaireAvecProduitCouleurTaille->quantite + (int)$exemplaire->quantite])
                ->update();
            }
            elseif ($exemplaire->quantite > 0) {
                $this->ModeleExemplaire->creerExemplaire($exemplaire->id_produit, $exemplaire->couleur, $exemplaire->taille, $exemplaire->quantite);
            }

            $exemplaire = $this->ModeleExemplaire->where('id_commande', $idCommande)->first();
        }

        return $this->afficherPanier();
    }


    public function adresseCommande() {
        $idClient = $this->getSessionId();
        $idCommande = $this->request->getPost('idCommande');

        $rue = $this->request->getPost('rue');
        $codePostal = (int)$this->request->getPost('codePostal');
        $ville = $this->request->getPost('ville');

        if (count($this->session->get("panier")) == 0) {
            return $this->afficherPanier();
        }

        // on regarde si le client a réutilisé une adresse
        $idAdresse = $this->ModeleAdresse
            ->where('rue', $rue)
            ->where('code_postal', $codePostal)
            ->where('ville', $ville)
            ->first();

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
            $this->ModeleAdresse->insert($adresse);

            // // on récupère l'id de l'adresse qui vient d'être crée
            $idAdresse = $this->ModeleAdresse->getInsertID();
        }

        if ($idAdresse != NULL) {
            // on modifie l'adresse de la commande et on la valide
            $commandeModifiee = array(
                'id_adresse' => $idAdresse,
                'est_validee' => true
            );

            $this->ModeleCommande->update((int)$idCommande, $commandeModifiee);
        }

        // on vide le panier
        $this->session->set("panier", array());

        return view("commandeValidee", array("commande" => $this->ModeleCommande->where('id_commande', $idCommande)->first()));
    }


    public function detailCommande($idCommande) {
        $commande = $this->ModeleCommande
            ->where('id_commande', $idCommande)
            ->first();

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
            $exemplaireCle = (string)$exemplaire->id_produit . $exemplaire->couleur . $exemplaire->taille;

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

        foreach ($exemplaires as $exemplaire) {
            $idProduit = $exemplaire->id_produit;
            $produit = $this->ModeleProduit->where('id_produit', $idProduit)->first();

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


    public function cgu() {
        return view("cgu", array("session" => $this->getDonneesSession()));
    }


    public function quiSommesNous() {
        return view("quiSommesNous", array("session" => $this->getDonneesSession()));
    }


    public function contact() {
        return view("contact", array("session" => $this->getDonneesSession()));
    }


    public function avis() {
        $to = "quentin.chauvelon@etu.univ-nantes.fr";
        $subject = "Avis du " . date("d/m/Y \a H:i:s");
        $message = wordwrap($this->request->getPost('avis'), 70, "\n", true);

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        mail($to, $subject, $message, $headers);

        return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
    }


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
}

// vérifier les strings vides et les tailles dans les posts
// sauter ligne pour les return view array pour que ce soit plus lisible ?
// au lieu de faire plusieurs return view, en avoir un seul dans un controller et appeler la méthode

// tester coupon
// ajouter pour les images .jpeg

// envoyer mail pour dire bravo t'es inscrit + autre ?

// max utilisations coupon ?
// pas commenter modele produit ?

// image collection
// s'assurer que les inputs sont sécurisés (appels du bon type, non vide...) surtout les posts, vérifier les appels à la bd...
// custom 404
// désactiver les erreurs + page spéciale ?