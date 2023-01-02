<?php

namespace App\Controllers;

use App\Models\ModeleClient;
use App\Entities\Client;
use App\Models\ModeleProduit;
use App\Models\ModeleFavori;
use App\Models\ModeleExemplaire;
use App\Models\ModeleCommande;
use App\Models\ModeleAdresse;

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

        // $this->session = \Config\Services::session();
        // $this->session->start();
        
        $this->request = \Config\Services::request();
    }


    public function setDonneesSession(int $id, string $prenom, string $nom, string $email) {
        $donneesClient = [
            'panier'  => array(),
            'id' => $id,
            'prenom'  => $prenom,
            'nom'     => $nom,
            'email' => $email
        ];
        
        $this->session->set($donneesClient);
    }


    public function estEnFavori(int $idProduit) {
        $produitFavori = true;

        $favoris = $this->ModeleFavori->where('id_client', $this->getSessionId())->findAll();

        // on regarde si le produit est en favori
        foreach ($favoris as $favori) {
            $idFavori = $favori->id_produit;

            if ($idFavori == $idProduit) {
                $produitFavori = true;
            }
        }

        return $produitFavori;
    }


    public function monCompte() {
        if ($this->SessionExistante()) {
            return view("compte", array("compteAction" => "profil", "emailDejaUtilise" => false, "session" => $this->getDonneesSession()));
        }
        else {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
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

        // si les deux mot de passe sont égaux, on crée le compte
        $prenom = $this->request->getPost('prenom');
        $nom = $this->request->getPost('nom');
        $passwordEncrypte = password_hash($password, PASSWORD_DEFAULT);
        
        $client = array(
            'adresse_email' => $email,
            'prenom' => $prenom,
            'nom' => $nom,
            'password' => $passwordEncrypte
        );
        
        // on ajoute le client à la base de donnée
        $this->ModeleClient->insert($client);
        
        // on récupère l'id du client qui vient d'être créé
        $idClient = $this->ModeleClient->getInsertID();
        // $idClient = $this->ModeleClient->where('adresse_email', $email)->first()->id_client;
        
        // on sauvegarde certaines données dans la session
        $this->setDonneesSession($idClient, $prenom, $nom, $email);
        
        return view("succesCreationCompteClient");
    }


    public function connexion() {
        return view("connexionCompte", array("compteNonExistant" => false, "passwordFaux" => false, "session" => $this->getDonneesSession()));
    }


    public function connexionCompte() {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $result =  $this->ModeleClient->where('adresse_email', $email)->first();

        // si l'utilisateur n'a pas encore de compte, on lui suggère d'en créer un
        if ($result == NULL) {
            return view("connexionCompte", array("compteNonExistant" => true, "passwordFaux" => false, "session" => $this->getDonneesSession()));
        }

        $hashedPassword = $result->password;

        // si les mots de passe sont différents, alors on retourne une erreur
        if (!password_verify($password, $hashedPassword)) {
            return view("connexionCompte", array("compteNonExistant" => false, "passwordFaux" => true, "session" => $this->getDonneesSession()));
        }


        $id = $result->id_client;
        $prenom = $result->prenom;
        $nom = $result->nom;
        
        $this->setDonneesSession($id, $prenom, $nom, $email);
        
        return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
    }


    public function deconnexion() {
        $this->session->destroy();

        return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => array('panier' => array(), 'id'  => NULL, 'prenom' => NULL, 'nom' => NULL, 'email' => NULL)));       
    }


    public function getAllFavorisClient() {
        return $this->ModeleFavori->where('id_client', $this->getSessionId())->findAll();
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
            
            $produit = $this->ModeleProduit->find($idFavori);

            $produits[] = $produit;
        }

        return view("compte", array("compteAction" => "favoris", "favoris" => $produits, "session" => $this->getDonneesSession()));
    }


    public function ajouterFavori(int $idProduit, int $returnProduit) {

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }
        
        $favoris = $this->getAllFavorisClient();

        foreach ($favoris as $favori) {
            $idFavori = $favori->id_produit;

            // si le produit que l'on veut ajouter aux favoris y est déjà, alors on le supprime
            if ($idFavori == $idProduit) {
                $this->supprimerFavori($idProduit);
                
                if ($returnProduit == 1) {
                    return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => false, "session" => $this->getDonneesSession()));
                } else {
                    return $this->afficherFavoris();
                }
            }
        }

        $favori = array(
            'id_client' => $this->getSessionId(),
            'id_produit' => $idProduit
        );

        $this->ModeleFavori->insert($favori);
        
        return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => true, "session" => $this->getDonneesSession()));
    }


    public function supprimerFavori(int $idProduit) {
        $this->ModeleFavori->where('id_client', $this->getSessionId())->where('id_produit', $idProduit)->delete();
    }


    public function afficherPanier() {

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
                    $quantitesExemplaires[$exemplaireCle] += 1;
                } else {
                    $quantitesExemplaires[$exemplaireCle] = 1;
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

        return view("compte", array("compteAction" => "panier", "panier" => $exemplairesUnique, "quantitesExemplaires" => $quantitesExemplaires, "produits" => $produits, "session" => $this->getDonneesSession()));
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

        $exemplaires = $this->ModeleExemplaire
            ->where('est_disponible', true)
            ->where('id_produit', $idProduit)
            ->where('couleur', $couleur)
            ->where('taille', $taille)
            ->findAll();

        // on s'assure qu'il y assez d'exemplaires pour la couleur et la taille donnée
        if (count($exemplaires) < $quantite) {
            return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => $this->estEnFavori($idProduit), "manqueExemplaire" => true, "session" => $this->getDonneesSession()));
        }

        // on récupère le panier de l'utilisateur
        $panier = $this->session->get("panier");

        foreach ($exemplaires as $exemplaire) {
            // $idExemplaire = $exemplaire->id_exemplaire;
            // $estDisponible = $exemplaire->est_disponible;
            // $dateObtention = $exemplaire->date_obtention;

            // if ($estDisponible) {

            //     $this->ModeleExemplaire->modifierExemplaire($idExemplaire, array('id_commande' => $idCommandeClient));

            //     $quantite -= 1;

            //     if ($quantite == 0) {
            //         break;
            //     }
            // }

            // on ajoute le bon nombre d'exemplaires dans le panier
            $panier[] = $exemplaire;

            $quantite -= 1;

            if ($quantite == 0) {
                break;
            }
        }

        // on sauvegarde le panier dans la session
        $this->session->set("panier", $panier);

        // si certains exemplaires n'étaient pas disponibles
        if ($quantite != 0) {
            return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => $this->estEnFavori($idProduit), "manqueExemplaire" => true, "session" => $this->getDonneesSession()));
        }

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


    public function validerPanier() {

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        $idClient = $this->getSessionId();
        $panier = $this->session->get("panier");
        $nombreArticles = count($panier);

        if ($nombreArticles == 0) {
            return $this->afficherPanier();
        }

        $commandes = $this->ModeleCommande->findAll();
        $idClient = $this->getSessionId();
        $idCommande = NULL;

        // on regarde si l'utiliateur n'a pas déjà une commande en cours, sinon en crée une
        foreach($commandes as $commande) {
            if ($commande->id_client == $idClient && $commande->id_adresse == NULL && $commande->est_validee == false) {
                $idCommande = $commande->id_commande;
            }
        }

        // si l'utilisateur n'a pas de commande en cours, on en crée une
        if ($idCommande == NULL) {
            $commande = array(
                'id_client' => $idClient,
                'date_commande' => date("Ymd"),
                'date_livraison_estimee' => date("Ymd", mktime(0, 0, 0, date("m"), date("d")+15, date("Y"))),
                'date_livraison' => NULL,
                'id_coupon' => NULL,
                'est_validee' => false,
                'montant' => 0,
                'id_adresse' => NULL
            );

            $this->ModeleCommande->insert($commande);

            // on récupère l'id de la commande qui vient d'être crée
            $idCommande = $this->ModeleCommande->getInsertID();
        }

        // on enlève tous les exemplaires de la commande au cas où la commande aurait été annulée et certains articles enlevés ou ajoutés
        foreach ($panier as $exemplaire) {
            $this->ModeleExemplaire
                ->where('id_commande', $idCommande)
                ->set(['id_commande' => NULL, "est_disponible" => true])
                ->update();
        }

        // on ajoute tous les articles à la commande
        $exemplaireModifiee = array(
            'id_commande' => $idCommande,
            'est_disponible' => false
        );

        // on trouve un exemplaire disponible correspondant à l'exemplaire et on modifie id_commande et est_disponible
        foreach ($panier as $exemplaire) {
            $idExemplaire = $this->ModeleExemplaire
                ->where('est_disponible', true)
                ->where('id_produit', $exemplaire->id_produit)
                ->where('couleur', $exemplaire->couleur)
                ->where('taille', $exemplaire->taille)
                ->first()
                ->id_exemplaire;

            $this->ModeleExemplaire->update($idExemplaire, $exemplaireModifiee);
        }

        // on calcule le montant de la commande
        $this->ModeleCommande->CalculerMontant($idCommande);

        $montant = $this->ModeleCommande
            ->where('id_commande', $idCommande)
            ->where('est_validee', false)
            ->first()
            ->montant;


        $adressesPrecendentes = array();

        // on récupère les adresses déjà utilisées par le client
        foreach ($commandes as $commande) {
            if ($commande->id_client == $idClient && $commande->id_adresse != NULL) {
                $adressesPrecendentes[] = $this->ModeleAdresse->find($commande->id_adresse);
            }
        }

        return view("compte", array("compteAction" => "validerCommandeAdresse", "montant" => $montant, "nombreArticles" => $nombreArticles, "idCommande" => $idCommande, "adressesPrecendentes" => $adressesPrecendentes, "session" => $this->getDonneesSession()));
    }


    public function adresseCommande() {
        $idClient = $this->getSessionId();
        $idCommande = $this->request->getPost('idCommande');


        // on regarde si le client a réutilisé une adresse
        $idAdresse = $this->ModeleAdresse
            ->where('rue', $this->request->getPost('rue'))
            ->where('code_postal', (int)$this->request->getPost('codePostal'))
            ->where('ville', $this->request->getPost('ville'))
            ->first();


        if ($idAdresse != NULL) {
            $idAdresse = $idAdresse->id_adresse;
        }

        // si le client utilise une nouvelle adresse, on en crée une
        else {
            $adresse = array(
                'id_adresse' => 0,
                'code_postal' => (int)$this->request->getPost('codePostal'),
                'ville' => $this->request->getPost('ville'),
                'rue' => $this->request->getPost('rue')
            );

            // on ajoute l'adresse dans la table
            $this->ModeleAdresse->insert($adresse);

            // on récupère l'id de l'adresse qui vient d'être crée
            $idAdresse = $this->ModeleAdresse->getInsertID();
        }

        // on modifie l'adresse de la commande et on la valide
        $commandeModifiee = array(
            'id_adresse' => $idAdresse,
            'est_validee' => true
        );

        $this->ModeleCommande->update((int)$idCommande, $commandeModifiee);

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
                $quantitesExemplaires[$exemplaireCle] += 1;
            } else {
                $quantitesExemplaires[$exemplaireCle] = 1;
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
}


// admin view password prompt cancel error 
// rupture de stock lequel est le mieux ?

// ajouter les images pour la collection (download en fonction du type de produit + les mettre sur la vue home)
// admin icon + blanc download + blanc
// flèche haut/bas pour voir les différentes images des produits
// tick.png -> retirer contour pour vraiment qu'il soit en png

// quantite dispo quand on change de taille et de couleur
// rester connecté
// activation compte par email (rajouter base de données -> bool estVerifie et code activation surement)
// + - pour les quantités du panier (caché quand min et max)
