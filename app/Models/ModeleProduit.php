<?php

namespace App\Models;

use App\Entities\Produit;
use CodeIgniter\Model;
use CodeIgniter\SafeModel;

class ModeleProduit extends SafeModel
{
    private static ModeleProduit $instance;

    protected $table            = 'Produit';
    protected $primaryKey       = 'id_produit';
    protected $returnType       = Produit::class;
    protected $allowedFields    = ['id_produit', 'id_collection', 'nom', 'prix', 'reduction', 'description', 'categorie', 'parution'];

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): ModeleProduit
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleProduit();
        }
        return self::$instance;
    }

    public function creerProduit(string $nom, int $prix, string $description, string $categorie, ?int $collection): bool
    {
        $sql = "CALL CreerProduit(?, ?, ?, ?, ?)";
        
        try {
            $this->db->query($sql, [$nom, $prix, $description, $categorie, $collection]);
            return true;
        } catch (\Exception) {
            return false;
        }
    }

    public function getAllProduitsPlusVendus()
    {
        $sql = "CALL GetAllProduitsPlusVendus()";
        try {
            return $this->db->query($sql)->getResult();
        } catch (\Exception) {
            return array();
        }
    }

    public function SupprimerProduit(int $id_produit): bool
    {
        $sql = "CALL SupprimerProduit(?)";
        try {
            $this->db->query($sql, [$id_produit]);
            return true;
        } catch (\Exception) {
            return false;
        }
    }
}
