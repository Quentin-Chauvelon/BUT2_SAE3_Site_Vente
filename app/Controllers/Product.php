<?php

namespace App\Controllers;

use App\Entities\Produit;
use App\Models\ModeleProduit;
use App\Models\ModeleClient;
use App\Models\ModeleFavori;
use App\Models\ModeleExemplaire;
use Exception;

/**
 * Product est le contrôleur utilisé pour afficher les produits sur la partie client du site.
 */
class Product extends BaseController
{

    public function __construct()
    {
        $this->ModeleProduit = ModeleProduit::getInstance();
        $this->ModeleClient = ModeleClient::getInstance();
        $this->ModeleFavori = ModeleFavori::getInstance();
        $this->ModeleExemplaire = ModeleExemplaire::getInstance();
    }
    

    /**
     * Affiche la page d'un produit
     * @param int $idProduit L'identifiant de ce produit
     * @return string La vue à afficher.
     */
    public function display(int $idProduit): string
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


    /**
     * getProduitsRuptureStock retourne les produits en rupture de stock parmi une liste de produits
     * @param Produit[] $products
     * @return Produit[] Les produits parmi ceux au-dessus qui n'ont plus d'exemplaires en stock.
     */
    public function getProduitsRuptureStock($products): array
    {
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


    /**
     * Affiche la page de tous les produits
     * @return string La vue à afficher.
     */
    public function displayAll(): string
    {
        try {
            $products =  $this->ModeleProduit->findAll();
        } catch (Exception) {
            $products = array();
        }

        return view('products', array(
            "products" => $products,
            "produitsRuptureStock" => $this->getProduitsRuptureStock($products),
            "session" => $this->getDonneesSession()
        ));
    }

    /**
     * Affiche la page avec tous les produits d'une catégorie.
     * @param string $categorie La catégorie du vêtement
     * @return string
     */
    public function trouverToutDeCategorie(string $categorie): string
    {
        try {
            $products =  $this->ModeleProduit->where('categorie', $categorie)->findAll();
        } catch (Exception) {
            $products = array();
        }

        return view('products', array(
            "products" => $products,
            "produitsRuptureStock" => $this->getProduitsRuptureStock($products),
            "session" => $this->getDonneesSession()
        ));
    }
}