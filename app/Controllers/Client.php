<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\ClientEntity;
use App\Models\ProductModel;

class Client extends BaseController
{


    public function __construct()
    {
        $this->ClientModel = new ClientModel();
        $this->ProductModel = new ProductModel();

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
            return view("compte", array("compteAction" => "profil", "session" => $this->getDonneesSession()));
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
                $id = $this->request->getPost('id_client');
                $prenom = $this->request->getPost('prenom');
                $nom = $this->request->getPost('nom');
                $passwordEncrypte = password_hash($password, PASSWORD_DEFAULT);
    
                $client = new ClientEntity();
                $client->setPrenom($prenom);
                $client->setNom($nom);
                $client->setAdresse_email($email);
                $client->setPassword($passwordEncrypte);
    
                $this->ClientModel->creerCompte($client);
    
                $this->setDonneesSession($id, $prenom, $nom, $email);
    
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
        return $this->ClientModel->favorisClient($this->session->get('id'));
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


    public function ajouterFavori(int $idProduit) {

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
                
                return view('product', array("product" => $this->ProductModel->findById($idProduit), "session" => $this->getDonneesSession()));
            }
        }

        $this->ClientModel->ajouterFavori($this->session->get("id"), $idProduit);
        
        return view('product', array("product" => $this->ProductModel->findById($idProduit), "session" => $this->getDonneesSession()));
    }


    public function supprimerFavori(int $idProduit) {
        $this->ClientModel->supprimerFavori($this->session->get("id"), $idProduit);
    }
}