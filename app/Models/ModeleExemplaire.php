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
    protected $allowedFields    = ['id_exemplaire', 'id_produit', 'id_commande', 'date_obtention', 'est_disponible', 'taille', 'couleur'];

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

}
