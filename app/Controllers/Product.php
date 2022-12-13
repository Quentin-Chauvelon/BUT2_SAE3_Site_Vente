<?php

namespace App\Controllers;

use App\Models\ProductEntity;
use App\Models\ProductModel;
use App\Models\ClientModel;
use App\Models\FavorisModel;
use App\Models\ExemplaireModel;

class Product extends BaseController
{
    // public function __construct() {        
    // 	parent::__construct();
	// 	$this->load->helper('url');
	// }

    public function __construct()
    {
        $this->ProductModel = new ProductModel();
        $this->ClientModel = new ClientModel();
        $this->FavorisModel = new FavorisModel();
        $this->ExemplaireModel = new ExemplaireModel();
    }


    public function getDonneesSession() {
        return array (
            "prenom" => $this->session->get("prenom"),
            "nom" => $this->session->get("nom"),
            "email" => $this->session->get("email")
        );
    }


    public function SessionExistante() {
        return $this->session->has('id') && $this->session->get('id') != NULL;
    }


    public function display($id)
    {
        $product =  $this->ProductModel->findById((int)$id);
        
        $produitFavori = false;

        if ($this->SessionExistante()) {
            $favoris = $this->FavorisModel->favorisClient($this->session->get('id'));

            // on regarde si le produit est en favori
            foreach ($favoris as $favori) {
                $idFavori = $favori->getId_produit();

                if ($idFavori == $id) {
                    $produitFavori = true;
                }
            }
        }

        return view('product', array("product" => $product, "exemplaires" => $this->ExemplaireModel->getExemplairesDispoParProduit($id), "ajouteAuPanier" => true, "produitFavori" => $produitFavori, "session" => $this->getDonneesSession()));
    }


    public function displayAll()
    {
        $products =  $this->ProductModel->chercherTout();
        return view('products', array("products" => $products, "session" => $this->getDonneesSession()));
    }

    public function trouverToutDeCategorie($categorie)
    {
        $products =  $this->ProductModel->trouverToutDeCategorie($categorie);
        return view('products', array("products" => $products, "session" => $this->getDonneesSession()));
    }
}