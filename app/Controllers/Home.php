<?php

namespace App\Controllers;

class Home extends BaseController
{
    // public function __construct() {        
    // 	parent::__construct();
	// 	$this->load->helper('url');
	// }

    public function index()
    {
        $this->load->model('ProductModel');
        $products = $this->ProductModel->findAll();
		$data = array("prods"=>$products);

		// Les 3 plus populaires
		// Les images de la dernière collection

        return view('home');
    }
}