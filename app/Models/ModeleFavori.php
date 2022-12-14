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
}
