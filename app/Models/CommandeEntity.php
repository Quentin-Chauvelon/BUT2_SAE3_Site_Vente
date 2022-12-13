<?php

namespace App\Models;

use CodeIgniter\Model;

class CommandeEntity extends Model
{
    /** @var int L'identifiant unique de la commande
     * 
     * 
     */
    private $id_commande;

    /** @var int L'identifiant unique de client associé à la commande
     * 
     * 
     */
    private $id_client;


    /** @var MariaDBDate La date de la commande
     * 
     * 
     */
    private $date_commande;

    /** @var MariaDBDate La date estimée de la livraison de la commande
     * 
     * 
     */
    private $date_livraison_estimee;

    /** @var MariaDBDate  La date de livraison de la commande
     * 
     * 
     */
    private $date_livraison;

    /** @var string  identifiant d'un coupon associé à la commande
     * 
     * 
     */
    private $id_coupon;


    /**
     * Get the value of id_commande
     */ 
    public function getId_commande()
    {
        return $this->id_commande;
    }

    /**
     * Set the value of id_commande
     *
     * @return  self
     */ 
    public function setId_commande($id_commande)
    {
        $this->id_commande = $id_commande;

        return $this;
    }

    /**
     * Get the value of id_client
     */ 
    public function getId_client()
    {
        return $this->id_client;
    }

    /**
     * Set the value of id_client
     *
     * @return  self
     */ 
    public function setId_client($id_client)
    {
        $this->id_client = $id_client;

        return $this;
    }

    /**
     * Get the value of date_commande
     */ 
    public function getDate_commande()
    {
        return $this->date_commande;
    }

    /**
     * Set the value of date_commande
     *
     * @return  self
     */ 
    public function setDate_commande($date_commande)
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    /**
     * Get the value of date_livraison_estimee
     */ 
    public function getDate_livraison_estimee()
    {
        return $this->date_livraison_estimee;
    }

    /**
     * Set the value of date_livraison_estimee
     *
     * @return  self
     */ 
    public function setDate_livraison_estimee($date_livraison_estimee)
    {
        $this->date_livraison_estimee = $date_livraison_estimee;

        return $this;
    }

    /**
     * Get the value of date_livraison
     */ 
    public function getDate_livraison()
    {
        return $this->date_livraison;
    }

    /**
     * Set the value of date_livraison
     *
     * @return  self
     */ 
    public function setDate_livraison($date_livraison)
    {
        $this->date_livraison = $date_livraison;

        return $this;
    }

    /**
     * Get the value of id_coupon
     */ 
    public function getId_coupon()
    {
        return $this->id_coupon;
    }

    /**
     * Set the value of id_coupon
     *
     * @return  self
     */ 
    public function setId_coupon($id_coupon)
    {
        $this->id_coupon = $id_coupon;

        return $this;
    }
}

?>