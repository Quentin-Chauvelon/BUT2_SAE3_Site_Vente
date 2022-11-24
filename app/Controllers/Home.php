<?php

namespace App\Controllers;

class Home extends BaseController
{
    // public function __construct() {        
    // 	parent::__construct();
	// 	$this->load->helper('url');
	// }

<<<<<<< HEAD
    public function index()
    {/*
        $this->load->model('ProductModel');
=======
    private ProductModel $ProductModel;

    public function index(): string
    {
/*        $this->ProductModel = model('ProductModel');
>>>>>>> 122553f2beee608e938dcb7980bfe1777d64face
        $products = $this->ProductModel->findAll();
		$data = array("prods"=>$products);*/

		// Les 3 plus populaires
		// Les images de la dernière collection
        */
        return view('home');
        
    }
}
