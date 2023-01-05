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

        if ($this->SessionExistante()) {
            $favoris = $this->ModeleFavori->where('id_client', $this->getSessionId())->findAll();

            // on regarde si le produit est en favori
            foreach ($favoris as $favori) {
                $idFavori = $favori->id_produit;

                if ($idFavori == $idProduit) {
                    $produitFavori = true;
                }
            }
        }

        return view('product', array("product" => $product, "exemplaires" => $this->ModeleExemplaire->where('id_produit', $idProduit)->where('est_disponible', true)->findAll(), "ajouteAuPanier" => false, "produitFavori" => $produitFavori, "manqueExemplaire" => false, "session" => $this->getDonneesSession()));
    }


    public function getProduitsRuptureStock($products) {
        $produitSansExemplaires = array();

        // on regarde quelles produits n'ont pas d'exemplaires (sont en rupture de stock)
        foreach ($products as $product) {
            $exemplaires = $this->ModeleExemplaire->getExemplairesDispoParProduit($product->id_produit);
            
            if (count($exemplaires) == 0) {
                $produitSansExemplaires[] = $products->id_produit;
                continue;
            }
            foreach ($exemplaires as $exemplaire) {
                if ($exemplaire->quantite <= 0) {
                    $produitSansExemplaires[] = $products->id_produit;
                    break;
                }
            }
        }
        
        return $produitSansExemplaires;
    }


    public function displayAll()
    {
        try {
            $products =  $this->ModeleProduit->findAll();
        } catch (\Exception $e) {
            $products = array();
        }
        return view('products', array("products" => $products, "produitsRuptureStock" => $this->getProduitsRuptureStock($products), "session" => $this->getDonneesSession()));
    }


    public function trouverToutDeCategorie($categorie)
    {
        try {
            $products =  $this->ModeleProduit->where('categorie', $categorie)->findAll();
        } catch (\Exception $e) {
            $products = array();
        }
        return view('products', array("products" => $products, "produitsRuptureStock" => $this->getProduitsRuptureStock($products), "session" => $this->getDonneesSession()));
    }
}