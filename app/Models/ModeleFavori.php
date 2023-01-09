<?php
/**
 * Le modèle de la table Favori.
 */
namespace App\Models;

use App\Entities\Favori;
use CodeIgniter\SafeModel;
use Exception;

/**
 * ModeleFavori est le modèle utilisé pour la table Favori.
 * Elle a pour champs : *id_client*, *id_produit*.
 * Hérite de SafeModel pour des try/catch automatiques.
 */
class ModeleFavori extends SafeModel
{
    private static ModeleFavori $instance;

    protected $table            = 'Favori';
    protected $returnType       = Favori::class;
    protected $allowedFields    = ['id_client', 'id_produit'];

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * Retourne la seule instance de la classe, car c'est un singleton.
     * @return ModeleFavori
     */
    public static function getInstance(): ModeleFavori
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleFavori();
        }
        return self::$instance;
    }

    /**
     * getFavorisParClient retourne tous les favoris d'un client.
     * @param int $id_client L'id du client dont on veut les favoris.
     * @return Favori[] La liste des favoris. Elle peut être vide.
     */
    public function getFavorisClient(int $id_client): array
    {
        $sql = "CALL GetFavorisClient(?)";
        try {
            return $this->db->query($sql, [$id_client])->getResult();
        } catch (Exception) {
            return [];
        }
    }

    /**
     * estEnFavori indique si un client a mis un produit en favori
     * @param int $id_client L'id du client.
     * @param int $id_produit L'id du produit.
     * @return bool True si le produit est en favori, false sinon.
     */
    public function estEnFavori(int $id_client, int $id_produit): bool
    {
        $liste = $this->getFavorisClient($id_client);
        foreach ($liste as $favori) {
            if ($favori->id_produit == $id_produit) {
                return true;
            }
        }
        return false;
    }

    /**
     * ajouterFavori ajoute un produit en favori pour un client.
     * @param int $id_client L'id du client.
     * @param int $id_produit L'id du produit.
     * @return bool True si l'ajout a réussi, false sinon.
     */
    function ajouterFavori(int $id_client, int $id_produit): bool
    {
        $sql = "CALL CreerFavori(?, ?)";
        try {
            $this->db->query($sql, [$id_client, $id_produit]);
            return true;
        } catch (Exception) {
            return false;
        }
    }
}
