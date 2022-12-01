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


    public function display($id)
    {
        $product =  $this->ProductModel->findById((int)$id);
        return view('product', array("product"=>$product));
    }


    public function displayAll()
    {
        $products =  $this->ProductModel->chercherTout();
        return view('products', array("products"=>$products));
    }

    public function displayAllOfCategorie($categorie)
    {
        $products =  $this->ProductModel->findAllOfCategorie($categorie);
        return view('products', array("product"=>$products));
    }
}