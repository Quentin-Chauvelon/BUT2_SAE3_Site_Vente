<?php

namespace App\Models;

use App\Entities\Adresse;
use CodeIgniter\Model;
use CodeIgniter\SafeModel;

class ModeleAdresse extends SafeModel
{
    private static ModeleAdresse $instance;

    protected $table            = 'Adresse';
    protected $primaryKey       = 'id_adresse';
    protected $returnType       = Adresse::class;
    protected $allowedFields    = ['id_adresse', 'code_postal', 'ville', 'rue'];

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): ModeleAdresse
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleAdresse();
        }
        return self::$instance;
    }


    function getAdressesParClient($idClient): array
    {
        $sql = "CALL GetAdressesParClient(?)";
        try {
            return $this->db->query($sql, [$idClient])->getResult();
        } catch (\Exception) {
            return array();
        }
    }
}
