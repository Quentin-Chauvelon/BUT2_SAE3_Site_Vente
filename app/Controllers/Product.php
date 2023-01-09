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
    

    public function display(int $idProduit)
    {
        $produit =  $this->ModeleProduit->find($idProduit);

        if ($produit == NULL) {
            return $this->displayAll();
        }

        return view('product', array(
            "product" => $produit,
            "exemplaires" => $this->ModeleExemplaire->getExemplairesDispoParProduit($idProduit),
            "ajouteAuPanier" => false,
            "produitFavori" => ($this->SessionExistante()) ? $this->estEnFavori($idProduit) : NULL,
            "manqueExemplaire" => false,
            "session" => $this->getDonneesSession()
        ));
    }


    public function getProduitsRuptureStock($products) {
        $produitSansExemplaires = array();

        // on regarde quels produits n'ont pas d'exemplaires (sont en rupture de stock)
        foreach ($products as $product) {
            $exemplaires = $this->ModeleExemplaire->getExemplairesDispoParProduit($product->id_produit);
            
            if (count($exemplaires) == 0) {
                $produitSansExemplaires[] = $product->id_produit;
                continue;
            }
            
            foreach ($exemplaires as $exemplaire) {
                if ($exemplaire->quantite <= 0) {
                    $produitSansExemplaires[] = $product->id_produit;
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

        return view('products', array(
            "products" => $products,
            "produitsRuptureStock" => $this->getProduitsRuptureStock($products),
            "session" => $this->getDonneesSession()
        ));
    }


    public function trouverToutDeCategorie(string $categorie)
    {
        try {
            $products =  $this->ModeleProduit->where('categorie', $categorie)->findAll();
        } catch (\Exception $e) {
            $products = array();
        }

        return view('products', array(
            "products" => $products,
            "produitsRuptureStock" => $this->getProduitsRuptureStock($products),
            "session" => $this->getDonneesSession()
        ));
    }
}