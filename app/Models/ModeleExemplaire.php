<?php
/**
 * Le modèle de la table Exemplaire.
 */
namespace App\Models;

use App\Entities\Exemplaire;
use App\Entities\Taille;
use CodeIgniter\SafeModel;
use Exception;

/**
 * ModeleExemplaire est le modèle utilisé pour la table Exemplaire.
 * Elle a pour champs : *id_exemplaire*, *id_produit*, *id_commande*, *est_disponible*, *taille*, *couleur*, *quantite*.
 * Hérite de SafeModel pour des try/catch automatiques.
 */
class ModeleExemplaire extends SafeModel
{
    private static ModeleExemplaire $instance;

    protected $table            = 'Exemplaire';
    protected $primaryKey       = 'id_exemplaire';
    protected $returnType       = Exemplaire::class;
    protected $allowedFields    = ['id_exemplaire', 'id_produit', 'id_commande', 'est_disponible', 'taille', 'couleur', 'quantite'];

    /**
     * creerExemplaire ajoute un nouvel exemplaire à la base de données.
     * Par défaut, il n'est associé à aucune commande et est disponible.
     * Si un exemplaire similaire existé déjà alors il sera ajouté au stock.
     * @param $id_produit int L'id du produit auquel l'exemplaire est associé.
     * @param $couleur  string La couleur des exemplaires.
     * @param $taille string La taille des exemplaires. Doit correspondre aux tailles de l'énumération
     * @param $quantite int Le nombre d'exemplaires à ajouter.
     * @return bool True si la création a réussi, false sinon.
     */
    function creerExemplaire(int $id_produit, string $couleur, ?string $taille, int $quantite): bool
    {
        $sql = "CALL CreerExemplaire(?, ?, ?, ?)";
        try {
            $this->db->query($sql, [$id_produit, $couleur, $taille, $quantite]);
        } catch (Exception) {
            return false;
        }
        return true;
    }

    /**
     * ajouterExemplaireCommande ajoute un exemplaire à une commande, dans une quantité donnée.
     * @param int $id_commande L'id de la commande.
     * @param int $id_exemplaire L'id de l'exemplaire.
     * @param int $quantite La quantité à ajouter.
     * @return bool True si l'ajout a réussi, false sinon.
     */
    function ajouterExemplaireCommande(int $id_commande, int $id_exemplaire, int $quantite): bool
    {
        $sql = "CALL AjouterExemplaireCommande(?, ?, ?)";
        try {
            $this->db->query($sql, [$id_commande, $id_exemplaire, $quantite]);
        } catch (Exception) {
            return false;
        }
        return true;
    }

    /**
     * getExemplairesDispo retourne tous les exemplaires disponibles et en stock.
     * @return Exemplaire[] Les exemplaires disponibles. Vide en cas d'erreur.
     */
    function getExemplairesDispo(): array
    {
        $sql = "CALL GetAllExemplairesDispo()";
        try {
            return $this->db->query($sql)->getResult();
        } catch (Exception) {
            return array();
        }
    }

    /** getNbExemplairesVendusParProduit retourne le nombre de ventes pour chaque produit.
     * @param int $id_produit L'id du produit.
     * @return int Le nombre de ventes. 0 en cas d'erreur.
     */
    function getNbExemplairesVendusParProduit(int $id_produit): int
    {
        $sql = "CALL GetNbExemplairesVendusParProduit(?)";
        try {
            return $this->db->query($sql, [$id_produit])->getResult()[0];
        } catch (Exception) {
            return 0;
        }
    }

    /** getExemplairesDispoParProduitCouleurTaille retourne le stock disponible d'un produit pour une couleur et une taille donnée.
     * @param int $id_produit L'id du produit.
     * @param string $couleur La couleur.
     * @param Taille $taille La taille.
     * @return Exemplaire[] Les exemplaires disponibles. Vide en cas d'erreur.
     */
    function getExemplairesDispoParProduitCouleurTaille(int $id_produit, string $couleur, Taille $taille): array
    {
        $sql = "CALL GetExemplairesDispoParProduitCouleurTaille(?, ?, ?)";
        try {
            return $this->db->query($sql, [$id_produit, $couleur, $taille])->getResult();
        } catch (Exception) {return array();}
    }

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * Retourne la seule instance de la classe, car c'est un singleton.
     * @return ModeleExemplaire L'instance du modèle.
     */
    public static function getInstance(): ModeleExemplaire
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleExemplaire();
        }
        return self::$instance;
    }

    /**
     * getExemplairesDispoParProduit retourne tous les exemplaires disponibles d'un produit
     * @param int $id_produit L'id du produit.
     * @return Exemplaire[] Les exemplaires de la commande. Vide en cas d'erreur.
     */
    function getExemplairesDispoParProduit(int $id_produit): array
    {
        $sql = "CALL GetExemplairesDispoParProduit(?)";
        try {
            return $this->db->query($sql, [$id_produit])->getResult();
        } catch (Exception) {
            return array();
        }
    }
}
