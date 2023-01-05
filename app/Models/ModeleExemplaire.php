<?php

namespace App\Models;

use App\Entities\Exemplaire;
use CodeIgniter\Model;

class ModeleExemplaire extends Model
{
    private static ModeleExemplaire $instance;

    protected $table            = 'Exemplaire';
    protected $primaryKey       = 'id_exemplaire';
    protected $returnType       = Exemplaire::class;
    protected $allowedFields    = ['id_exemplaire', 'id_produit', 'id_commande', 'est_disponible', 'taille', 'couleur', 'quantite'];

    function creerExemplaire($id_produit, $couleur, $taille, $quantite): bool
    {
        $sql = "CALL CreerExemplaire(?, ?, ?, ?)";
        try {
            $this->db->query($sql, [$id_produit, $couleur, $taille, $quantite]);
        } catch (\Exception) {
            return false;
        }
        return true;
    }

    function ajouterExemplaireCommande($id_commande, $id_exemplaire, $quantite): bool
    {
        $sql = "CALL AjouterExemplaireCommande(?, ?, ?)";
        try {
            $this->db->query($sql, [$id_commande, $id_exemplaire, $quantite]);
        } catch (\Exception) {
            return false;
        }
        return true;
    }

    function getExemplairesDispo(): array
    {
        $sql = "CALL GetAllExemplairesDispo()";
        try {
            return $this->db->query($sql)->getResult();
        } catch (\Exception) {
            return array();
        }
    }

    function getNbExemplairesVendusParProduit($id_produit)
    {
        $sql = "CALL GetNbExemplairesVendusParProduit(?)";
        try {
            return $this->db->query($sql, [$id_produit])->getResult()[0];
        } catch (\Exception) {
            return 0;
        }
    }

    function getExemplairesDispoParProduitCouleurTaille(int $id_produit, string $couleur, string $taille): ?Exemplaire
    {
        $sql = "CALL GetExemplairesDispoParProduitCouleurTaille(?, ?, ?)";
        try {
            return $this->db->query($sql, [$id_produit, $couleur, $taille])->getResult()[0];
        } catch (\Exception) {return null;}
    }

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): ModeleExemplaire
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleExemplaire();
        }
        return self::$instance;
    }

    function getExemplairesDispoParProduit(int $id_produit): array
    {
        $sql = "CALL GetExemplairesDispoParProduit(?)";
        try {
            return $this->db->query($sql, [$id_produit])->getResult('array(App\Entities\Exemplaire)');
        } catch (\Exception) {
            return array();
        }
    }
}
