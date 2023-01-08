<?php

namespace App\Models;

use App\Entities\Collection;
use CodeIgniter\Model;
use CodeIgniter\SafeModel;

class ModeleCollection extends SafeModel
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

    function creerCollection($nom): bool
    {
        $sql = "CALL CreerCollection(?)";
        try {
            $this->db->query($sql, [$nom]);
        } catch (\Exception) {
            return false;
        }
        return true;
    }

    public function getCollectionParNom(string $nom): ?Collection
    {
        $sql = "CALL GetCollectionParNom(?)";
        try {
            return $this->db->query($sql, [$nom])->getResult()[0];
        } catch (\Exception) {
            return null;
        }
    }
}
