<?php

namespace App\Models;

use CodeIgniter\Model;

class ExemplaireEntity extends Model
{

    /** @var int L'identifiant unique de la commande
     * 
     * 
     */
    private $id_exemplaire;

    /** @var int L'identifiant unique de la commande
     * 
     * 
     */
    private $id_produit;
    
    /** @var int L'identifiant unique de la commande
     * 
     * 
     */
    private $id_commande;

    /** @var string L'identifiant unique de la commande
     * 
     * 
     */
    private $date_obtention;

    /** @var bool L'identifiant unique de la commande
     * 
     * 
     */
    private $est_disponible;

    /** @var string L'identifiant unique de la commande
     * 
     * 
     */
    private $taille;

    /** @var string L'identifiant unique de la commande
     * 
     * 
     */
    private $couleur;


    /**
     * Get the value of id_exemplaire
     */ 
    public function getId_exemplaire()
    {
        return $this->id_exemplaire;
    }

    /**
     * Set the value of id_exemplaire
     *
     * @return  self
     */ 
    public function setId_exemplaire($id_exemplaire)
    {
        $this->id_exemplaire = $id_exemplaire;

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
     * Get the value of date_obtention
     */ 
    public function getDate_obtention() : string
    {
        return $this->date_obtention;
    }

    /**
     * Set the value of date_obtention
     *
     * @return  self
     */ 
    public function setDate_obtention($date_obtention)
    {
        $this->date_obtention = $date_obtention;

        return $this;
    }

    /**
     * Get the value of est_disponible
     */ 
    public function getEst_disponible()
    {
        return $this->est_disponible;
    }

    /**
     * Set the value of est_disponible
     *
     * @return  self
     */ 
    public function setEst_disponible($est_disponible)
    {
        $this->est_disponible = $est_disponible;

        return $this;
    }

    /**
     * Get the value of taille
     */ 
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * Set the value of taille
     *
     * @return  self
     */ 
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get the value of couleur
     */ 
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set the value of couleur
     *
     * @return  self
     */ 
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;

        return $this;
    }
}


?>