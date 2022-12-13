<?php

namespace App\Models;

use CodeIgniter\Model;

class CommandeModel extends Model {
    
    public function getCommandes() {
        $sql = "CALL GetAllCommandes()";
        $query = $this->db->query($sql);
        return $query->getCustomResultObject('App\Models\CommandeEntity');
    }


    public function creerCommande(int $idClient) {
        $sql = "CALL CreerCommande(?)";
        $query = $this->db->query($sql, [$idClient]);
    }


    public function getContenuCommande(int $idCommande) {
        $sql = "CALL GetContenuCommande(?)";
        $query = $this->db->query($sql, [$idCommande]);
        return $query->getCustomResultObject('App\Models\ExemplaireEntity');
    }
}