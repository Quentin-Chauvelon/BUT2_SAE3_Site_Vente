<?php

namespace App\Models;

use App\Entities\Adresse;
use CodeIgniter\Model;

class ModeleAdresse extends Model
{
    private static ModeleAdresse $instance;

    protected $table            = 'Adresse';
    protected $primaryKey       = 'id_adresse';
    protected $returnType       = Adresse::class;
    protected $allowedFields    = ['id_adresse', 'code_postal', 'ville', 'rue'];
    protected $validationRules  = [
        'id_adresse'    => 'required|numeric|is_unique[Adresse.id_adresse]',
        'code_postal' => 'required|numeric|greater_than[9999]|less_than[100000]',
        'ville'       => 'required|alpha_space',
        'rue'         => 'required|alpha_dash',
    ];

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
}
