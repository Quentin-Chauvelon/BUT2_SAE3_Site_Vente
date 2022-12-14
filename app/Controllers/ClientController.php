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

    
    public function getDonneesSession() {
        return array(
            'panier'  => $this->session->get("panier"),
            'id'  => $this->session->get("id"),
            'prenom' => $this->session->get("prenom"),
            'nom' => $this->session->get("nom"),
            'email' => $this->session->get("email")
        );
    }


    public function getSessionId() {
        return $this->session->get('id');
    }


    public function SessionExistante() {
        return $this->session->has('id') && $this->session->get('id') != NULL;
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
        $result =  $this->ModeleClient->where('adresse_email', $email)->findAll();
        
        // s'il n'existe pas de compte avec cette adresse mail, on en crée un
        if (count($result) == 0) {
            $password = $this->request->getPost('password');
            $passwordRepetition = $this->request->getPost('passwordRepetition');

            // si les deux mot de passe sont égaux, on crée le compte
            if ($password == $passwordRepetition) {
                $prenom = $this->request->getPost('prenom');
                $nom = $this->request->getPost('nom');
                $passwordEncrypte = password_hash($password, PASSWORD_DEFAULT);
    
                $client = array(
                    'adresse_email' => $email,
                    'prenom' => $prenom,
                    'nom' => $nom,
                    'password' => $passwordEncrypte
                );
    
                $this->ModeleClient->insert($client);

                $id_client = $this->ModeleClient->where('adresse_email', $email)->findAll()[0]->id_client;
    
                $this->setDonneesSession($id_client, $prenom, $nom, $email);
    
                return view("succesCreationCompteClient");
            }

            // si les deux mot de passe sont différents, on return une erreur
            else {
                return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => true, "session" => $this->getDonneesSession()));
            }
        }

        // sinon, on suggère à l'utilisateur de se connecter
        else {
            return view("creerCompte", array("compteDejaExistant" => true, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }
    }


    public function connexion() {
        return view("connexionCompte", array("compteNonExistant" => false, "passwordFaux" => false, "session" => $this->getDonneesSession()));
    }


    public function connexionCompte() {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $result =  $this->ModeleClient->where('adresse_email', $email)->findAll();

        if (count($result) > 0) {
            $hashedPassword = $result[0]->password;

            if (password_verify($password, $hashedPassword)) {
                $id = $result[0]->id_client;
                $prenom = $result[0]->prenom;
                $nom = $result[0]->nom;

                $this->setDonneesSession($id, $prenom, $nom, $email);

                return view('home', array("session" => $this->getDonneesSession()));
            }

            else {
                return view("connexionCompte", array("compteNonExistant" => false, "passwordFaux" => true, "session" => $this->getDonneesSession()));
            }
        }

        else {
            return view("connexionCompte", array("compteNonExistant" => true, "passwordFaux" => false, "session" => $this->getDonneesSession()));
        }
    }


    public function deconnexion() {
        $this->session->destroy();

        return view('home', array("session" => array('id'  => NULL, 'prenom' => NULL, 'nom' => NULL, 'email' => NULL)));       
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
        $panier = $this->session->get("panier");

        $exemplairesUnique = array();
        $quantitesExemplaires = array();
        $produits = array();

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
            $produit = $this->ModeleProduit->where('id_produit', $idProduit)->findAll();

            if (count($produit) > 0) {
                if (!array_key_exists($idProduit, $produits)) {

                    $produits[$idProduit] = array(
                        "nom" => $produit[0]->nom,
                        "prix" => $produit[0]->prix
                    );
                }
            }
        }

        return view("compte", array("compteAction" => "panier", "panier" => $exemplairesUnique, "quantitesExemplaires" => $quantitesExemplaires, "produits" => $produits, "session" => $this->getDonneesSession()));
    }


    public function afficherHistorique() {
        return view("compte", array("compteAction" => "historique", "session" => $this->getDonneesSession()));
    }


    public function modifierProfil() {
        $email = $this->request->getPost('email');
        $result =  $this->ModeleClient->where('adresse_email', $email)->findAll();

        if (count($result) == 0 && $this->SessionExistante()) {
            $prenom = $this->request->getPost('prenom');
            $nom = $this->request->getPost('nom');
    
            $clientAvant = $this->ModeleClient->where('adresse_email', $this->session->get("email"))->findAll();

            if (count($clientAvant) > 0) {
                $clientAvant = $clientAvant[0];
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

        else {
            return view("compte", array("compteAction" => "profil", "emailDejaUtilise" => true, "session" => $this->getDonneesSession()));
        }
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

        // si certains exemplaires n'étaient pas disponibles et que l'on a pas réussi à 
        if ($quantite != 0) {
            return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => $this->estEnFavori($idProduit), "manqueExemplaire" => false, "session" => $this->getDonneesSession()));
        }


        return view('product', array("product" => $this->ModeleProduit->find($idProduit), "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => true, "produitFavori" => $this->estEnFavori($idProduit), "manqueExemplaire" => false, "session" => $this->getDonneesSession()));
    }


    public function supprimerDuPanier(int $idProduit, string $couleur, string $taille) {
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

        $idClient = $this->getSessionId();
        $panier = $this->session->get("panier");
        $nombreArticles = count($panier);

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

        // on crée la commande pour l'utilisateur
        $this->ModeleCommande->insert($commande);

        // on récupère l'id de la commande qui vient d'être crée
        $idCommande = $this->ModeleCommande->getInsertID();

        $exemplaireModifiee = array(
            'id_commande' => $idCommande,
            'est_disponible' => false
        );

        // on ajoute tous les articles à la commande
        foreach ($panier as $exemplaire) {
            $this->ModeleExemplaire->update($exemplaire->id_exemplaire, $exemplaireModifiee);
        }

        // on calcule le montant de l'id
        $this->ModeleCommande->CalculerMontant($idCommande);

        $montant = $this->ModeleCommande
            ->where('id_commande', $idCommande)
            ->where('est_validee', false)
            ->first()
            ->montant;

        return view("compte", array("compteAction" => "validerCommandeAdresse", "montant" => $montant, "nombreArticles" => $nombreArticles, "idCommande" => $idCommande, "session" => $this->getDonneesSession()));
    }


    public function adresseCommande() {
        $idClient = $this->getSessionId();
        $idCommande = $this->request->getPost('idCommande');

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

        // on modifie l'adresse de la commande et on la valide
        $commandeModifiee = array(
            'id_adresse' => $idAdresse,
            'est_validee' => true
        );

        $this->ModeleCommande->update((int)$idCommande, $commandeModifiee);

        // on vide le panier
        $this->session->set("panier", array());

        return view("commandeValidee");
    }
}


// en cas de déconnexion pendant la validation de la commande, quand clique sur valider et payer : retour sur la commande précédente
// faire en sorte qu'il soit impossible de créer une commande tant que la précédente n'est pas validée
// find_column pour les requêtes sur autre que primary key mais pas besoin de find all (email ?) ? + pour avoir les id return qui permet d'avoir la primary key (getInsertId)
// utiliser les méthodes verifypassword et checkpassword du model
// first au lieu de [0]
// mettre les fonctions communes aux controlleurs dans baseController (getsession, session existante, get id session, est en favori...)
// + - pour les quantités du panier (caché quand min et max)
// view admin
// taille, couleurs, quantite dispo
// sauvegarder les adresses (adresses déjà utilisés + les proposer dans la commande)
// activation compte par email (rajouter base de données -> bool estVerifie et code activation surement)
// rester connecté
// marquer les produits en rupture de stock dans SHOP