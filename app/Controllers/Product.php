<?php

namespace App\Controllers;

use App\Models\ProductEntity;
use App\Models\ProductModel;

class Product extends BaseController
{
    // public function __construct() {        
    // 	parent::__construct();
	// 	$this->load->helper('url');
	// }

    public function __construct()
    {
        $this->ProductModel = new ProductModel();
    }


    public function index()
    {
        return view('product');
    }


    public function getDonneesSession() {
        return array (
            "prenom" => $this->session->get("prenom"),
            "nom" => $this->session->get("nom"),
            "email" => $this->session->get("email")
        );
    }


    public function display($id)
    {
        $product =  $this->ProductModel->findById((int)$id);
        return view('product', array("product" => $product, "session" => $this->getDonneesSession()));
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