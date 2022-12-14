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


    public function getDonneesSession() {
        return array (
            'panier'  => $this->session->get("panier"),
            'id'  => $this->session->get("id"),
            "prenom" => $this->session->get("prenom"),
            "nom" => $this->session->get("nom"),
            "email" => $this->session->get("email")
        );
    }


    public function getSessionId() {
        return $this->session->get('id');
    }


    public function SessionExistante() {
        return $this->session->has('id') && $this->session->get('id') != NULL;
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


    public function displayAll()
    {
        $products =  $this->ModeleProduit->findAll();
        return view('products', array("products" => $products, "session" => $this->getDonneesSession()));
    }

    public function trouverToutDeCategorie($categorie)
    {
        $products =  $this->ModeleProduit->where('categorie', $categorie)->findAll();
        return view('products', array("products" => $products, "session" => $this->getDonneesSession()));
    }
}