<?php
/**
 * Le modèle de la table Commande.
 */
namespace App\Models;

use App\Entities\Commande;
use CodeIgniter\SafeModel;
use Exception;

/**
 * ModeleCommande est le modèle utilisé pour la table Commande.
 * Elle a pour champs : *id_commande*, *id_client*, *id_adresse*, *date_commande*, *date_livraison_estimee*, *date_livraison*, *id_coupon*, *est_validee*, *montant*.
 * Hérite de SafeModel pour des try/catch automatiques.
 */
class ModeleCommande extends SafeModel
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

    /**
     * Retourne la seule instance de la classe, car c'est un singleton.
     * @return ModeleCommande L'instance du modèle.
     */
    public static function getInstance(): ModeleCommande
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleCommande();
        }
        return self::$instance;
    }

    /**
     * CalculerMontant modifie la colonne **montant** d'une commande dans la base de données pour correspondre à son contenu et ses réductions.
     * @param $idCommande L'id du client dont on veut les commandes.
     * @return bool True si la requête a réussi.
     */
    public function CalculerMontant(int $idCommande): bool{
        $sql = "CALL CalculerMontant(?)";
        try {
            $this->db->query($sql, [$idCommande])->getResult();
            return true;
        } catch (Exception) {
            return false;
        }
    }
}
