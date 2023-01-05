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

    /** returnAdminView : Redirige vers la vue admin désirée.
     * Ne vérifie pas si l'utilisateur est admin.
     * @param string $notHidden L'onglet de la vue admin qu'on souhaite afficher, comme "utilisateurs" ou "produits".
     * @return string La vue admin avec l'onglet désiré.
     */
    public function returnAdminView(string $notHidden): string
    {
        $exemplaires = $this->ModeleExemplaire->getExemplairesDispo();
        return view("adminView", array("notHidden" => $notHidden, "utilisateurs" => $this->ModeleClient->findAll(), "produits" => $this->ModeleProduit->findAll(), "collections" => $this->ModeleCollection->findAll(), "exemplaires" => $exemplaires));
    }

    /** adminView : Redirige vers la vue admin.
     * @return string La vue admin.
     */
    public function adminView(): string {

        if (!$this->estAdmin()) { // Retourner sur la page d'accueil si l'utilisateur n'est pas admin
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }
        return $this->returnAdminView('utilisateurs');
    }

    private function setAdmin(int $idClient, bool $est_admin): string
    {
        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        try {
            $this->ModeleClient->update($idClient, array("est_admin" => $est_admin));
        } catch (\Exception $e) {
        }
        return $this->returnAdminView('utilisateurs');
    }


    public function mettreAdmin($idClient) {
        return $this->setAdmin($idClient, true);
    }

    
    public function enleverAdmin($idClient) {
        return $this->setAdmin($idClient, false);
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
        $nom = $this->request->getPost('nom');
        $prix = $this->request->getPost('prix');
        $description = $this->request->getPost('description');
        $categorie = $this->request->getPost('categorie');
        $idCollection = $this->request->getPost('id_collection');
        $this->ModeleProduit->creerProduit($nom, $prix, $description, $categorie, $idCollection);
        return $this->returnAdminView('produits');
    }


    public function modifierProduitVue($idProduit)
    {
        if (!$this->estAdmin())
        {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }
        $produit = $this->ModeleProduit->find($idProduit);
        return view('modifierProduitVue', array("produit" => $produit, "collections" => $this->ModeleCollection->findAll(), "session" => $this->getDonneesSession()));
    }
    

    public function modifierProduit() {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }
        $produit = $this->ModeleProduit->find($this->request->getPost('id_produit'));
        if ($produit == null) {
            return $this->returnAdminView('produits');
        }
        $produit->id_collection = $this->request->getPost('id_collection');
        $produit->prix = $this->request->getPost('prix');
        $produit->nom = $this->request->getPost('nom');
        $produit->description = $this->request->getPost('description');
        $produit->categorie = $this->request->getPost('categorie');
        $produit->reduction = $this->request->getPost('reduction');

        try{
            $this->ModeleProduit->save($produit);
        } catch (\Exception $e) {}
        return $this->returnAdminView('produits');

    }


    public function supprimerProduit($idProduit) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        try {
            $this->ModeleProduit->delete($idProduit);
        } catch (\Exception $e) {}
        return $this->returnAdminView('produits');
    }


    public function creerExemplaire() {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }
        $idProduit = $this->request->getPost('id_produit');
        $couleur = $this->request->getPost('couleur');
        $taille = $this->request->getPost('taille');
        $quantite = $this->request->getPost('quantite');
        if ($quantite == null || $quantite <= 0)
        {
            $quantite = 1;
        }
        $this->ModeleExemplaire->creerExemplaire($idProduit, $couleur, $taille, $quantite);

        return $this->returnAdminView('exemplaires');
    }


    public function supprimer1Exemplaire(int $idProduit, string $taille, string $couleur) {
        
        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $exemplaire = $this->ModeleExemplaire->getExemplairesDispoParProduitCouleurTaille($idProduit, $couleur, $taille);

        if ($exemplaire != NULL)
        {
            try {
                $exemplaire->quantite -=  1;
                $this->ModeleExemplaire->save($exemplaire);
            } catch (\Exception) {}
        }
        return $this->returnAdminView('exemplaires');
    }

    public function supprimerTousLesExemplaires(int $idProduit, string $taille, string $couleur) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $exemplaire = $this->ModeleExemplaire->getExemplairesDispoParProduitCouleurTaille($idProduit, $couleur, $taille);
        if ($exemplaire != NULL)
        {
            try {
                $this->ModeleExemplaire->delete($exemplaire->id_exemplaire);
            } catch (\Exception) {}
        }

        return $this->returnAdminView('exemplaires');
    }


    public function creerCollection() {
        
        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $today = date("Ymd");
        $date_limite = $this->request->getPost('date_limite');
        $nom = $this->request->getPost('nom');
        $this->ModeleCollection->creerCollection($nom);

        if ($date_limite > $today) {
            $this->ModeleCollection->update($this->ModeleCollection->getCollectionParNom($nom)->id_collection, array('date_limite' => $date_limite));
        }

        return $this->returnAdminView('exemplaires');
    }


    public function supprimerCollection($idCollection) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        try {
            $this->ModeleCollection->delete($idCollection);
        } catch (\Exception $e) {
        }
        return $this->returnAdminView('collections');
    }
}