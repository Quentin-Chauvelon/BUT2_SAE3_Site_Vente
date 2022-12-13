<?php

namespace App\Controllers;

class Home extends BaseController

{

    public function index(): string
    {
        $session = array (
            "prenom" => $this->session->get("prenom"),
            "nom" => $this->session->get("nom"),
            "email" => $this->session->get("email")
        );

        return view('home', array("session"=>$session));
    }
}
