<?php
/**
 * Le modèle de la table Collection.
 */
namespace App\Models;

use App\Entities\Collection;
use CodeIgniter\SafeModel;
use Exception;

/**
 * ModeleCollection est le modèle utilisé pour la table Collection.
 * Elle a pour champs : *id_collection*, *nom*, *parution*, *date_limite*.
 * Hérite de SafeModel pour des try/catch automatiques.
 */
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

    /**
     * Retourne la seule instance de la classe, car c'est un singleton.
     * @return ModeleCollection L'instance du modèle.
     */
    public static function getInstance(): ModeleCollection
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleCollection();
        }
        return self::$instance;
    }

    /**
     * creerCollection ajoute une nouvelle collection de produits à la base de données.
     * La date de parution est le jour de la création par défaut et la date limite est nulle.
     * @param $nom string Le nom de la collection.
     * @return bool True si la création a réussi, false sinon.
     */
    function creerCollection(string $nom): bool
    {
        $sql = "CALL CreerCollection(?)";
        try {
            $this->db->query($sql, [$nom]);
        } catch (Exception) {
            return false;
        }
        return true;
    }

    /**
     * getCollectionParNom retourne la collection dont le nom est passé en paramètre.
     * @param $nom string Le nom de la collection à retourner.
     * @return Collection|null La collection trouvée, ou null si elle n'existe pas.
     */
    public function getCollectionParNom(string $nom): ?Collection
    {
        $sql = "CALL GetCollectionParNom(?)";
        try {
            return $this->db->query($sql, [$nom])->getFirstRow("App\Entities\Collection");
        } catch (Exception) {
            return null;
        }
    }
}
