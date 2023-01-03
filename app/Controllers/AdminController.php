<?php

namespace App\Controllers;

use App\Models\ModeleClient;
use App\Models\ModeleProduit;
use App\Models\ModeleExemplaire;
use App\Models\ModeleCollection;
use App\Models\ModeleCommande;


class AdminController extends BaseController
{

    public function __construct()
    {
        $this->ModeleClient = ModeleClient::getInstance();
        $this->ModeleProduit = ModeleProduit::getInstance();
        $this->ModeleExemplaire = ModeleExemplaire::getInstance();
        $this->ModeleCollection = ModeleCollection::getInstance();
        $this->ModeleCommande = ModeleCommande::getInstance();
        
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

        // $notHidden = "exemplaires";
        return view("adminView", array("notHidden" => $notHidden, "utilisateurs" => $this->ModeleClient->findAll(), "produits" => $this->ModeleProduit->findAll(), "collections" => $this->ModeleCollection->findAll(), "exemplaires" => $exemplairesParCouleurTailleProduits, "commandes" => $this->ModeleCommande->findAll()));
    }


    public function adminView($password) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
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
            return view('home', array("estAdmin" => true, "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        if ($client->est_admin) {
            return $this->returnAdminView('utilisateurs');
        }
    }


    public function mettreAdmin($idClient) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $client = array(
            "est_admin" => true
        );

        $this->ModeleClient->update($idClient, $client);

        return $this->returnAdminView('utilisateurs');
    }

    
    public function enleverAdmin($idClient) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $client = array(
            "est_admin" => false
        );

        $this->ModeleClient->update($idClient, $client);

        return $this->returnAdminView('utilisateurs');
    }


    public function supprimerUtilisateur($idClient) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $this->ModeleClient->delete($idClient);

        return $this->returnAdminView('utilisateurs');
    }


    public function creerProduit() {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
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

        $this->ModeleProduit->insert($produit);

        return $this->returnAdminView('produits');
    }


    public function modifierProduitVue($idProduit) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $produit = $this->ModeleProduit->find($idProduit);

        return view('modifierProduitVue', array("produit" => $produit, "collections" => $this->ModeleCollection->findAll(), "session" => $this->getDonneesSession()));
    }
    

    public function modifierProduit() {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $idCollection = $this->request->getPost('id_collection');
        $prix = $this->request->getPost('prix');
        $reduction = $this->request->getPost('reduction');

        $produit = array(
            "id_collection" => ($idCollection != "") ? $idCollection : NULL,
            "nom" => $this->request->getPost('nom'),
            "prix" => $prix,
            "reduction" => $reduction,
            "description" => $this->request->getPost('description'),
            "categorie" => $this->request->getPost('categorie'),
        );

        if ($prix >= 0 && $reduction >= 0) {
            $this->ModeleProduit->update($this->request->getPost('id_produit'), $produit);
        }

        return $this->returnAdminView('produits');
    }


    public function supprimerProduit($idProduit) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $this->ModeleProduit->delete($idProduit);

        return $this->returnAdminView('produits');
    }


    public function creerExemplaire() {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $exemplaire = array(
            "id_exemplaire" => 0,
            "id_produit" => $this->request->getPost('id_produit'),
            "id_commande" => NULL,
            "date_obtention" => date("Ymd"),
            "est_disponible" => true,
            "taille" => $this->request->getPost('taille'),
            "couleur" => lcfirst($this->request->getPost('couleur')),
        );

        $this->ModeleExemplaire->insert($exemplaire);

        return $this->returnAdminView('exemplaires');
    }


    public function supprimer1Exemplaire(int $idProduit, string $taille, string $couleur) {
        
        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $idExemplaire = $this->ModeleExemplaire
            ->where('id_produit', $idProduit)
            ->where('taille', $taille)
            ->where('couleur', $couleur)
            ->where('est_disponible', true)
            ->first();

        if ($idExemplaire != NULL) {
            $this->ModeleExemplaire->delete($idExemplaire->id_exemplaire);
        }

        return $this->returnAdminView('exemplaires');
    }

    public function supprimerTousLesExemplaires(int $idProduit, string $taille, string $couleur) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $this->ModeleExemplaire
            ->where('id_produit', $idProduit)
            ->where('taille', $taille)
            ->where('couleur', $couleur)
            ->where('est_disponible', true)
            ->delete();

        return $this->returnAdminView('exemplaires');
    }


    public function creerCollection() {
        
        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $today = date("Ymd");
        $dateLimiteArray = explode("-", $this->request->getPost('date_limite'));

        $dateLimite = $dateLimiteArray[0] . $dateLimiteArray[1] . $dateLimiteArray[2];

        $collection = array(
            "id_collection" => 0,
            "nom" => $this->request->getPost('nom'),
            "parution" => $today,
            "date_limite" => $dateLimite,
        );

        if ($dateLimite > $today) {
            $this->ModeleCollection->insert($collection);
        }

        return $this->returnAdminView('collections');
    }


    public function supprimerCollection($idCollection) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $this->ModeleCollection->delete($idCollection);

        return $this->returnAdminView('collections');
    }
}