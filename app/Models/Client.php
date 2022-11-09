<?php

class Client
{
    /** @var int L'identifiant unique du client
     * 
     * 
     */
    private $id_client;

    /** @var string l'adresse email du client
     * 
     * 
     */
    private $adresse_email;

    /** @var string le nom du client
     * 
     * 
     */
    private $nom;

    /** @var string le prénom du client
     * 
     * 
     */
    private $prenom;

    /** @var string le password du client
     * 
     * 
     */
    private $password;

    /** @var bool  indique si le client est administrateur
     * 
     * 
     */
    private $est_admin;

    /** @var bool  indique si le client est fidèle
     * 
     * 
     */
    private bool $est_fidele;




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
     * Get the value of adresse_email
     */ 
    public function getAdresse_email()
    {
        return $this->adresse_email;
    }

    /**
     * Set the value of adresse_email
     *
     * @return  self
     */ 
    public function setAdresse_email($adresse_email)
    {
        $this->adresse_email = $adresse_email;

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
     * Get the value of prenom
     */ 
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */ 
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of est_admin
     */ 
    public function getEst_admin()
    {
        return $this->est_admin;
    }

    /**
     * Set the value of est_admin
     *
     * @return  self
     */ 
    public function setEst_admin($est_admin)
    {
        $this->est_admin = $est_admin;

        return $this;
    }

    /**
     * Get the value of est_fidele
     */ 
    public function getEst_fidele()
    {
        return $this->est_fidele;
    }

    /**
     * Set the value of est_fidele
     *
     * @return  self
     */ 
    public function setEst_fidele($est_fidele)
    {
        $this->est_fidele = $est_fidele;

        return $this;
    }
}

?>