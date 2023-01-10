<?php
/**
 * Le modèle de la table Produit.
 */
namespace App\Models;

use App\Entities\Produit;
use CodeIgniter\SafeModel;
use Exception;

/*
 * ModeleProduit est le modèle utilisé pour la table Produit.
 * Elle a pour champs : *id_produit*, *id_collection*, *nom*, *prix*, *reduction*, *description*, *categorie*, *parution*.
 * Hérite de SafeModel pour des try/catch automatiques.
 */
class ModeleProduit extends SafeModel
{
    private static ModeleProduit $instance;

    protected $table            = 'Produit';
    protected $primaryKey       = 'id_produit';
    protected $returnType       = Produit::class;
    protected $allowedFields    = ['id_produit', 'id_collection', 'nom', 'prix', 'reduction', 'description', 'categorie', 'parution'];

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * Retourne la seule instance de la classe, car c'est un singleton.
     * @return ModeleProduit L'instance de la classe.
     */
    public static function getInstance(): ModeleProduit
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleProduit();
        }
        return self::$instance;
    }

    /**
     * creerProduit ajoute un produit dans la base de données.
     * @param string $nom Le nom du produit, unique.
     * @param int $prix Le prix du produit en centimes.
     * @param string $description La description du produit, max 500.
     * @param string $categorie Le nom de la catégorie du produit.
     * @param int|null $collection L'id de la collection du produit, null si pas de collection.
     * @return bool True si le produit a été créé, false sinon.
     */
    public function creerProduit(string $nom, int $prix, string $description, string $categorie, ?int $collection): bool
    {
        $sql = "CALL CreerProduit(?, ?, ?, ?, ?)";
        
        try {
            $this->db->query($sql, [$nom, $prix, $description, $categorie, $collection]);
            return true;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * getAllProduitsPlusVendus retourne la liste des produits triés par leur nombre de ventes.
     * @return Produit[] La liste des produits. Peut être vide.
     */
    public function getAllProduitsPlusVendus(): array
    {
        $sql = "CALL GetAllProduitsPlusVendus()";
        try {
            return $this->db->query($sql)->getResult();
        } catch (Exception) {
            return array();
        }
    }

    /**
     * SupprimerProduit enlève un produit dans la base de données en cascade.
     * Il vide les stocks et enlève les favoris.
     * Le produit ne sera finalement supprimé que s'il n'a jamais été commandé.
     * Sinon, il sera considéré en rupture de stock.
     * @param int $id_produit L'identifiant du produit à supprimer.
     * @return bool
     */
    public function SupprimerProduit(int $id_produit): bool
    {
        $sql = "CALL SupprimerProduit(?)";
        try {
            $this->db->query($sql, [$id_produit]);
            return true;
        } catch (Exception) {
            return false;
        }
    }
}
