<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\ClientEntity;
use App\Models\ProductModel;
use App\Models\FavorisModel;
use App\Models\CommandeModel;
use App\Models\ExemplaireModel;

class Client extends BaseController
{


    public function __construct()
    {
        $this->ClientModel = new ClientModel();
        $this->ProductModel = new ProductModel();
        $this->FavorisModel = new FavorisModel();
        $this->CommandeModel = new CommandeModel();
        $this->ExemplaireModel = new ExemplaireModel();

        // $this->session = \Config\Services::session();
        // $this->session->start();
        
        $this->request = \Config\Services::request();
    }


    public function setDonneesSession(int $id, string $prenom, string $nom, string $email) {
        $donneesClient = [
            'id' => $id,
            'prenom'  => $prenom,
            'nom'     => $nom,
            'email' => $email
        ];
        
        $this->session->set($donneesClient);
    }

    
    public function getDonneesSession() {
        $valeursSession = array(
            'id'  => $this->session->get("id"),
            'prenom' => $this->session->get("prenom"),
            'nom' => $this->session->get("nom"),
            'email' => $this->session->get("email")
        );

        return $valeursSession;
    }


    public function SessionExistante() {
        return $this->session->has('id') && $this->session->get('id') != NULL;
    }


    public function monCompte() {
        if ($this->session->get("email")) {
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
        $result =  $this->ClientModel->clientAvecEmail($email);

        // s'il n'existe pas de compte avec cette adresse mail, on en crée un
        if ($result == NULL) {
            $password = $this->request->getPost('password');
            $passwordRepetition = $this->request->getPost('passwordRepetition');

            // si les deux mot de passe sont égaux, on crée le compte
            if ($password == $passwordRepetition) {
                $prenom = $this->request->getPost('prenom');
                $nom = $this->request->getPost('nom');
                $passwordEncrypte = password_hash($password, PASSWORD_DEFAULT);
    
                $client = new ClientEntity();
                $client->setPrenom($prenom);
                $client->setNom($nom);
                $client->setAdresse_email($email);
                $client->setPassword($passwordEncrypte);
    
                $this->ClientModel->creerCompte($client);

                $id_client = $this->ClientModel->clientAvecEmail($email)->getId_client();
    
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
        $result =  $this->ClientModel->clientAvecEmail($email);

        if ($result != NULL) {
            $hashedPassword = $result->getPassword();

            if (password_verify($password, $hashedPassword)) {
                $id = $result->getId_client();
                $prenom = $result->getPrenom();
                $nom = $result->getNom();

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
        return $this->FavorisModel->favorisClient($this->session->get('id'));
    }


    public function afficherFavoris() {

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        $favoris = $this->getAllFavorisClient();

        $products = array();

        foreach ($favoris as $favori) {
            $idFavori = $favori->getId_produit();
            
            $product = $this->ProductModel->findById($idFavori);

            $products[] = $product;
        }

        return view("compte", array("compteAction" => "favoris", "favoris" => $products, "session" => $this->getDonneesSession()));
    }


    public function ajouterFavori(int $idProduit, int $returnProduit) {

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }
        
        $favoris = $this->getAllFavorisClient();

        foreach ($favoris as $favori) {
            $idFavori = $favori->getId_produit();

            // si le produit que l'on veut ajouter aux favoris y est déjà, alors on le supprime
            if ($idFavori == $idProduit) {
                $this->supprimerFavori($idProduit);
                
                
                if ($returnProduit == 1) {
                    return view('product', array("product" => $this->ProductModel->findById($idProduit), "produitFavori" => false, "session" => $this->getDonneesSession()));
                } else {
                    return $this->afficherFavoris();
                }
            }
        }

        $this->FavorisModel->ajouterFavori($this->session->get("id"), $idProduit);
        
        return view('product', array("product" => $this->ProductModel->findById($idProduit), "produitFavori" => true, "session" => $this->getDonneesSession()));
    }


    public function supprimerFavori(int $idProduit) {
        $this->FavorisModel->supprimerFavori($this->session->get("id"), $idProduit);
    }


    public function afficherPanier() {
        return view("compte", array("compteAction" => "panier", "session" => $this->getDonneesSession()));
    }


    public function afficherHistorique() {
        return view("compte", array("compteAction" => "historique", "session" => $this->getDonneesSession()));
    }


    public function modifierProfil() {
        $email = $this->request->getPost('email');
        $result =  $this->ClientModel->clientAvecEmail($email);

        if ($result == NULL && $this->SessionExistante()) {
            $prenom = $this->request->getPost('prenom');
            $nom = $this->request->getPost('nom');
    
            $clientAvant = $this->ClientModel->clientAvecEmail($this->session->get("email"));
            $id = $clientAvant->getId_client();

            $client = new ClientEntity();
            $client->setId_client($id);
            $client->setPrenom(($prenom != "") ? $prenom : $clientAvant->getPrenom());
            $client->setNom(($nom != "") ? $nom : $clientAvant->getNom());
            $client->setAdresse_email(($email != "") ? $email : $clientAvant->getAdresse_email());
            $client->setPassword($clientAvant->getPassword());

            $this->ClientModel->modifierCompteClient($client);

            $this->setDonneesSession($id, $client->getPrenom(), $client->getNom(), $client->getAdresse_email());

            return view("compte", array("compteAction" => "profil", "emailDejaUtilise" => false, "session" => $this->getDonneesSession()));
        }

        else {
            return view("compte", array("compteAction" => "profil", "emailDejaUtilise" => true, "session" => $this->getDonneesSession()));
        }
    }


    public function ajouterAuPanier() {
        $idProduit = $this->request->getPost('idProduit');

        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }
        
        $commandes = $this->CommandeModel->getCommandes();
        $idCommandeClient = NULL;

        $idClient = $this->session->get("id");

        foreach($commandes as $commande) {
            $idCommande = $commande->getId_client();

            if ($idCommande == $idClient) {
                $idCommandeClient = $idCommande;
            }
        }

        // s'il n'existe pas de commande pour l'utilisateur, on en crée une
        if (!$idCommandeClient) {
            $commandes = $this->CommandeModel->creerCommande($idClient);
        }

        $quantite = $this->request->getPost('quantite');
        $couleur = $this->request->getPost('couleur');
        $taille = $this->request->getPost('taille');

        $exemplaires = $this->ExemplaireModel->getExemplairesDispoParProduitCouleurTaille($idProduit, $couleur, $taille);

        // on s'assure qu'il y assez d'exemplaires pour la couleur et la taille donnée
        if (count($exemplaires) < $quantite) {
            echo "Erreur : Il n'y a pas assez d'exemplaires avec la quantité, couleur ou taille donnée (maximum : " . count($exemplaires) . ").";
        }

        foreach ($exemplaires as $exemplaire) {
            $idExemplaire = $exemplaire->getId_exemplaire();
            $estDisponible = $exemplaire->getEst_disponible();
            $dateObtention = $exemplaire->getDate_obtention();

            if ($estDisponible) {
                $this->ExemplaireModel->modifierExemplaire($idExemplaire, $idProduit, $couleur, $taille, $estDisponible, $dateObtention, $idCommandeClient);

                $quantite -= 1;

                if ($quantite == 0) {
                    break;
                }
            }
        }

        // si certains exemplaires n'étaient pas disponibles et que l'on a pas réussi à 
        if ($quantite != 0) {
            echo "Erreur : Il n'y a pas eu assez d'exemplaires avec la quantité, couleur ou taille donnée.";
        }

        $result = $this->CommandeModel->getContenuCommande($idCommandeClient);

        var_dump($result);

        foreach ($result as $exemplaire) {
            var_dump($exemplaire);
        }
    }
}

// taille, couleurs, quantite dispo
// sauvegarder les adresses (idclient dans la table adresse)
// activation compte par email (rajouter base de données -> bool estVerifie et code activation surement)
// modifier mot de passe (envoie email) dans infos personnelles
// marquer les produits en rupture de stock dans SHOP
// faire une erreur plus propre pour un manque d'exemplaire (revenir sur la page produit avec un message en rouge)