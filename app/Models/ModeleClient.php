<?php

namespace App\Models;

use App\Entities\Client;
use CodeIgniter\Model;
use CodeIgniter\SafeModel;

class ModeleClient extends SafeModel
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
    
    function getClientParEmail(string $email): ?Client
    {
        $sql = "CALL GetClientParEmail(?)";
        try {
            return $this->db->query($sql, [$email])->getFirstRow('App\Entities\Client');
        } catch (\Exception) {
            return null;
        }
    }

    public function getClientParCodeMDPOublie($codeMDPOublie): ?Client
    {
        foreach ($this->findAll() as $client) {
            // var_dump("code", $codeMDPOublie);
            if (password_verify($client->password, $codeMDPOublie)) {
                return $client;
            }
        }
        return null;
    }
}
