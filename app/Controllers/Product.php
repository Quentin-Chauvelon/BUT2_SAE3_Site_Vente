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
        //$product =  $this->ProductModel->findById((int)$id);
        $product =  $this->ProductModel->findById((int)$id);
        return view('product', array("product"=>$product));
    }


    public function displayAll()
    {
        //$products =  $this->ProductModel->findAll();
        $products =  $this->ProductModel->findById(1);
        return view('product', array("product"=>$products));
    }

    public function displayAllOfCategorie($categorie)
    {
        $products =  $this->ProductModel->findAllOfCategorie($categorie);
        return view('products', array("product"=>$products));
    }
}