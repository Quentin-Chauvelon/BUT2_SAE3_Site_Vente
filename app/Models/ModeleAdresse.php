<?php
/**
 * Le modèle de la table Adresse.
 */

namespace App\Models;

use App\Entities\Adresse;
use CodeIgniter\SafeModel;
use Exception;

/**
 * ModeleAdresse est le modèle utilisé pour la table Adresse.
 * Elle a pour champs : *id_adresse*, *code_postal*, *ville*, *rue*.
 * Hérite de SafeModel pour des try/catch automatiques.
 */
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

    /**
     * Retourne la seule instance de la classe, car c'est un singleton.
     * @return ModeleAdresse L'instance du modèle.
     */
    public static function getInstance(): ModeleAdresse
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleAdresse();
        }
        return self::$instance;
    }

    /**
     * getAdressesParClient retourne toutes les adresses où un client a déjà été livré dans une ancienne commande.
     * @param $idClient L'id du client dont on veut les adresses.
     * @return Adresse[] La liste des adresses utilisées. Elle peut être vide.
     */
    function getAdressesParClient($idClient): array
    {
        $sql = "CALL GetAdressesParClient(?)";
        try {
            return $this->db->query($sql, [$idClient])->getResult();
        } catch (Exception) {
            return array();
        }
    }
}
