<?php
class Coupon
{
    /** @var string L'identifiant unique du coupon
     * 
     * 
     */
    private $code;

    /** @var string le nom du coupon
     * 
     * 
     */
    private $nom;


    /** @var int le montant du coupon
     * 
     * 
     */
    private $montant;

    /** @var bool indique si le coupon est en pourcentage
     * 
     * 
     */
    private $est_pourcentage;

    /** @var bool  indique si le coupon est valable
     * 
     * 
     */
    private $est_valable;





    /**
     * Get the value of code
     */ 
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @return  self
     */ 
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of nom
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of montant
     */ 
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set the value of montant
     *
     * @return  self
     */ 
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get the value of est_pourcentage
     */ 
    public function getEst_pourcentage()
    {
        return $this->est_pourcentage;
    }

    /**
     * Set the value of est_pourcentage
     *
     * @return  self
     */ 
    public function setEst_pourcentage($est_pourcentage)
    {
        $this->est_pourcentage = $est_pourcentage;

        return $this;
    }

    /**
     * Get the value of est_valable
     */ 
    public function getEst_valable()
    {
        return $this->est_valable;
    }

    /**
     * Set the value of est_valable
     *
     * @return  self
     */ 
    public function setEst_valable($est_valable)
    {
        $this->est_valable = $est_valable;

        return $this;
    }
}

?>