<?php
/**
 * Le modèle de la table Coupon.
 */
namespace App\Models;

use App\Entities\Coupon;
use CodeIgniter\SafeModel;

/**
 * ModeleCoupon est le modèle utilisé pour la table Coupon.
 * Elle a pour champs : *id_coupon*, *code*, *montant*, *est_pourcentage*, *'est_valable*, *date_limite*, *utilisations_max*.
 * Hérite de SafeModel pour des try/catch automatiques.
 */
class ModeleCoupon extends SafeModel
{
    private static ModeleCoupon $instance;

    protected $table            = 'Coupon';
    protected $primaryKey       = 'id_coupon';
    protected $returnType       = Coupon::class;
    protected $allowedFields    = ['id_coupon', 'nom', 'montant', 'est_pourcentage', 'est_valable', 'date_limite', 'utilisations_max'];

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * Retourne la seule instance de la classe, car c'est un singleton.
     * @return ModeleCoupon
     */
    public static function getInstance(): ModeleCoupon
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleCoupon();
        }
        return self::$instance;
    }
}
