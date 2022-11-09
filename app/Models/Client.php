<?php

class Client{
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
    private  $est_admin;

    /** @var bool  indique si le client est fidèle
     * 
     * 
    */
    private bool $est_fidele;



}

?>