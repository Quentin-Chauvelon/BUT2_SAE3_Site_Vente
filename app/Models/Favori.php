<?php
class Favori
{

    /** @var int L'identifiant unique du client
     * 
     * 
     */
    private $id_client;

    /** @var int L'identifiant unique du produit
     * 
     * 
     */
    private $id_produit;

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
     * Get the value of id_produit
     */ 
    public function getId_produit()
    {
        return $this->id_produit;
    }

    /**
     * Set the value of id_produit
     *
     * @return  self
     */ 
    public function setId_produit($id_produit)
    {
        $this->id_produit = $id_produit;

        return $this;
    }
}

?>