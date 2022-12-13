<?php

namespace App\Models;

use App\Entities\Coupon;
use CodeIgniter\Model;

class ModeleCoupon extends Model
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

    public static function getInstance(): ModeleCoupon
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleCoupon();
        }
        return self::$instance;
    }
}
