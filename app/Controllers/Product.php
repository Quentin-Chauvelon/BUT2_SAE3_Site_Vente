<?php

namespace App\Controllers;

use App\Models\ModeleProduit;
use App\Models\ModeleClient;
use App\Models\ModeleFavori;
use App\Models\ModeleExemplaire;

class Product extends BaseController
{

    public function __construct()
    {
        $this->ModeleProduit = ModeleProduit::getInstance();
        $this->ModeleClient = ModeleClient::getInstance();
        $this->ModeleFavori = ModeleFavori::getInstance();
        $this->ModeleExemplaire = ModeleExemplaire::getInstance();
    }
    

    public function display($idProduit)
    {
        $product =  $this->ModeleProduit->find((int)$idProduit);
        
        $produitFavori = false;

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        $favoris = $this->ModeleFavori->where('id_client', $this->getSessionId())->findAll();

        // on regarde si le produit est en favori
        foreach ($favoris as $favori) {
            $idFavori = $favori->id_produit;

            if ($idFavori == $idProduit) {
                $produitFavori = true;
            }
        }

        return view('product', array("product" => $product, "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => $produitFavori, "manqueExemplaire" => false, "session" => $this->getDonneesSession()));
    }


    public function getProduitsRuptureStock($products) {
        $produitSansExemplaires = array();

        // on regarde quelles produits n'ont pas d'exemplaires (sont en rupture de stock)
        foreach ($products as $product) {
            $idProduit = $product->id_produit;
            
            $exemplaires = $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll();
            
            if (count($exemplaires) == 0) {
                $produitSansExemplaires[] = $idProduit;
            }
        }
        
        return $produitSansExemplaires;
    }


    public function displayAll()
    {
        $products =  $this->ModeleProduit->findAll();

        return view('products', array("products" => $products, "produitsRuptureStock" => $this->getProduitsRuptureStock($products), "session" => $this->getDonneesSession()));
    }


    public function trouverToutDeCategorie($categorie)
    {
        $products =  $this->ModeleProduit->where('categorie', $categorie)->findAll();
        return view('products', array("products" => $products, "produitsRuptureStock" => $this->getProduitsRuptureStock($products), "session" => $this->getDonneesSession()));
    }
}