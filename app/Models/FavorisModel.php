<?php

namespace App\Models;

use CodeIgniter\Model;

class FavorisModel extends Model
{
    public function favorisClient(int $id) {
        $sql = "CALL GetFavorisClient(?)";
        $query = $this->db->query($sql, [$id]);
        return $query->getCustomResultObject('App\Models\FavorisEntity');
    }


    public function ajouterFavori(int $idClient, int $idProduit) {
        $sql = "CALL CreerFavori(?, ?)";
        $this->db->query($sql, [$idClient, $idProduit]);
    }


    public function supprimerFavori(int $idClient, int $idProduit) {
        $sql = "CALL SupprimerFavori(?, ?)";
        $this->db->query($sql, [$idClient, $idProduit]);
    }
}