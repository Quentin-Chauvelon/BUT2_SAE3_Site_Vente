<?php

namespace App\Models;

use App\Entities\Collection;
use CodeIgniter\Model;

class ModeleCollection extends Model
{

    private static ModeleCollection $instance;

    protected $table            = 'Collection';
    protected $primaryKey       = 'id_collection';
    protected $returnType       = Collection::class;
    protected $allowedFields    = ['id_collection', 'nom', 'parution', 'date_limite'];

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): ModeleCollection
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleCollection();
        }
        return self::$instance;
    }
}
