<?php

namespace App\Controllers;

use App\Models\ModeleClient;
use App\Models\ModeleProduit;
use App\Models\ModeleExemplaire;
use App\Models\ModeleCollection;


class AdminController extends BaseController
{

    public function __construct()
    {
        $this->ModeleClient = ModeleClient::getInstance();
        $this->ModeleProduit = ModeleProduit::getInstance();
        $this->ModeleExemplaire = ModeleExemplaire::getInstance();
        $this->ModeleCollection = ModeleCollection::getInstance();
        
        $this->request = \Config\Services::request();
    }


    public function returnAdminView(string $notHidden) {
        $exemplaires = $this->ModeleExemplaire->findAll();

        $exemplairesParCouleurTailleProduits = array();

        foreach ($exemplaires as $exemplaire) {
            $idProduit = $exemplaire->id_produit;
            $taille = $exemplaire->taille;
            $couleur = $exemplaire->couleur;
            $estDisponible = $exemplaire->est_disponible;

            if (!array_key_exists($exemplaire->id_produit, $exemplairesParCouleurTailleProduits)) {
                $exemplairesParCouleurTailleProduits[$idProduit] = array();
            }

            if (!array_key_exists($taille, $exemplairesParCouleurTailleProduits[$idProduit])) {
                $exemplairesParCouleurTailleProduits[$idProduit][$taille] = array();
            }

            if (array_key_exists($couleur, $exemplairesParCouleurTailleProduits[$idProduit][$taille])) {
                if ($estDisponible) {
                    $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur][0] += 1;
                }

                $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur][1] += 1;
            }

            else {
                if ($estDisponible) {
                    $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur] = array(1, 1);
                } else {
                    $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur] = array(0, 1);
                }
            }
        }

        $notHidden = "exemplaires";
        return view("adminView", array("notHidden" => $notHidden, "utilisateurs" => $this->ModeleClient->findAll(), "produits" => $this->ModeleProduit->findAll(), "collections" => $this->ModeleCollection->findAll(), "exemplaires" => $exemplairesParCouleurTailleProduits));
    }


    public function adminView($password) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "session" => $this->getDonneesSession()));
        }

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        $email = $this->session->get("email");
        $client =  $this->ModeleClient->where('adresse_email', $email)->first();
        $hashedPassword = $client->password;

        // si les mots de passe sont différents, alors on retourne une erreur
        if (!password_verify($password, $hashedPassword)) {
            return view('home', array("estAdmin" => true, "session" => $this->getDonneesSession()));
        }

        if ($client->est_admin) {
            return $this->returnAdminView('utilisateurs');
        }
    }


    public function mettreAdmin($idClient) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "session" => $this->getDonneesSession()));
        }

        $client = array(
            "est_admin" => true
        );

        $this->ModeleClient->update($idClient, $client);

        return $this->returnAdminView('utilisateurs');
    }


    public function supprimerUtilisateur($idClient) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "session" => $this->getDonneesSession()));
        }

        $this->ModeleClient->delete($idClient);

        return $this->returnAdminView('utilisateurs');
    }


    public function creerProduit() {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "session" => $this->getDonneesSession()));
        }

        $idCollection = $this->request->getPost('id_collection');

        $produit = array(
            "id_produit" => 0,
            "id_collection" => ($idCollection != "") ? $idCollection : NULL,
            "nom" => $this->request->getPost('nom'),
            "prix" => $this->request->getPost('prix'),
            "reduction" => $this->request->getPost('reduction'),
            "description" => $description = $this->request->getPost('description'),
            "categorie" => $this->request->getPost('categorie'),
            "parution" => date("Ymd")
        );

        var_dump($produit);

        $this->ModeleProduit->insert($produit);

        return $this->returnAdminView('produits');
    }


    public function modifierProduitVue($idProduit) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "session" => $this->getDonneesSession()));
        }

        $produit = $this->ModeleProduit->find($idProduit);

        return view('modifierProduitVue', array("produit" => $produit, "collections" => $this->ModeleCollection->findAll(), "session" => $this->getDonneesSession()));
    }
    

    public function modifierProduit() {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "session" => $this->getDonneesSession()));
        }

        $idCollection = $this->request->getPost('id_collection');

        $produit = array(
            "id_collection" => ($idCollection != "") ? $idCollection : NULL,
            "nom" => $this->request->getPost('nom'),
            "prix" => $this->request->getPost('prix'),
            "reduction" => $this->request->getPost('reduction'),
            "description" => $description = $this->request->getPost('description'),
            "categorie" => $this->request->getPost('categorie'),
        );

        $this->ModeleProduit->update($this->request->getPost('id_produit'), $produit);

        return $this->returnAdminView('produits');
    }


    public function supprimerProduit($idProduit) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "session" => $this->getDonneesSession()));
        }

        $this->ModeleProduit->delete($idProduit);

        return $this->returnAdminView('produits');
    }
}