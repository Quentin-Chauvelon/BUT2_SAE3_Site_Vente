<?php

namespace App\Models;

use App\Entities\Commande;
use CodeIgniter\Model;

class ModeleCommande extends Model
{
    private static ModeleCommande $instance;

    protected $table            = 'Commande';
    protected $primaryKey       = 'id_commande';
    protected $returnType       = Commande::class;
    protected $allowedFields    = ['id_commande', 'id_client', 'id_adresse', 'date_commande', 'date_livraison_estimee', 'date_livraison',
    'id_coupon', 'est_validee', 'montant'];

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): ModeleCommande
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleCommande();
        }
        return self::$instance;
    }
}