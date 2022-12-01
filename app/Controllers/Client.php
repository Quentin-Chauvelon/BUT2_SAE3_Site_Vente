<?php

namespace App\Controllers;

use App\Models\ClientModel;

class Utilisateur extends BaseController
{

    public function __construct()
    {
        $this->ClientModel = new ClientModel();
    }


    public function creerCompte()
    {
        $result =  $this->ClientModel->findById((int)$id);
        
        if ($result) {
            $this->load->view("succesCreationCompteClient.php");
        } 
        else {
            $this->load->view("echecCreationCompteClient.php");
        }
    }
}
