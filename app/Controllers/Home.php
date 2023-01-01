<?php

namespace App\Controllers;

class Home extends BaseController

{

    public function index() : string
    {
        return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session"=>$this->getDonneesSession()));
    }
}