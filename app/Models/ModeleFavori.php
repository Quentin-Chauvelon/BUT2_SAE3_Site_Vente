<?php

namespace App\Models;

use App\Entities\Favori;
use CodeIgniter\Model;

class ModeleFavori extends Model
{
    private static ModeleFavori $instance;

    protected $table            = 'Favori';
    protected $returnType       = Favori::class;
    protected $allowedFields    = ['id_client', 'id_produit'];

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): ModeleFavori
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleFavori();
        }
        return self::$instance;
    }

    public function getFavorisClient(int $id_client)
    {
        $sql = "CALL GetFavorisClient(?)";
        try {
            return $this->db->query($sql, [$id_client])->getResult();
        } catch (\Exception) {
            return [];
        }
    }

    public function estEnFavori(int $id_client, int $id_produit): bool
    {
        $liste = $this->getFavorisClient($id_client);
        foreach ($liste as $favori) {
            if ($favori->id_produit == $id_produit) {
                return true;
            }
        }
        return false;
    }

    function ajouterFavori(int $id_client, int $id_produit): bool
    {
        $sql = "CALL CreerFavori(?, ?)";
        try {
            $this->db->query($sql, [$id_client, $id_produit]);
            return true;
        } catch (\Exception) {
            return false;
        }
    }
}
