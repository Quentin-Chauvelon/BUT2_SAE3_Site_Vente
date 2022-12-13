<?php

namespace App\Models;

use App\Entities\Client;
use CodeIgniter\Model;

class ModeleClient extends Model
{
    private static ModeleClient $instance;

    protected $table            = 'Client';
    protected $primaryKey       = 'id_client';
    protected $useAutoIncrement = true;
    protected $returnType       = Client::class;
    protected $allowedFields    = ['id_client', 'adresse_email', 'nom', 'prenom', 'password', 'est_admin'];

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): ModeleClient
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleClient();
        }
        return self::$instance;
    }
}
