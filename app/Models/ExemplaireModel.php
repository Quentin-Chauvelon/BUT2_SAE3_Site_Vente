<?php

namespace App\Models;

use CodeIgniter\Model;

class ExemplaireModel extends Model {

    public function getExemplairesDispoParProduit(int $idProduit) {
        $sql = "CALL GetExemplairesDispoParProduit(?)";
        $query = $this->db->query($sql, [$idProduit]);
        return $query->getCustomResultObject('App\Models\ExemplaireEntity');
    }


    public function getExemplairesDispoParProduitCouleurTaille(int $idProduit, string $couleur, string $taille) {
        $sql = "CALL GetExemplairesDispoParProduitCouleurTaille(?, ?, ?)";
        $query = $this->db->query($sql, [$idProduit, $couleur, $taille]);
        return $query->getCustomResultObject('App\Models\ExemplaireEntity');
    }


    public function modifierExemplaire(int $idExemplaire, int $idProduit, string $couleur, string $taille, bool $estDisponible, $dateObtention, int $idCommande) {
        $sql = "CALL ModifierExemplaire(?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($sql, [$idExemplaire, $idProduit, $couleur, $taille, $estDisponible, $dateObtention, $idCommande]);
    }
}