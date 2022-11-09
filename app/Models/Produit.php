<?php
class Produit
{
    /** @var int L'identifiant unique du produit
     * 
     * 
     */
    private $id_produit;

    /** @var int L'identifiant de la collection
     * 
     * 
     */
    private $id_collection;

    /** @var string L'identifiant de la collection
     * 
     * 
     */
    private $nom;

    /** @var int Prix du produit !! En Centimes !! 
     * description
     * 
     */
    private $prix;

    /** @var int Réduction s'il y en a une
     * 
     * 
     */
    private $reduction;

    /** @var string Description du produit
     * 
     * 
     */
    private $description;

    /** @var string Catégorie du produit dans {POSTER,ACCESSOIRE,PANTALON,SWEAT,TSHIRT}
     * 
     * 
     */
    private $categorie;



    /**
     * Get the value of id_produit
     */ 
    public function getId_produit() : int
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
     * Get the value of id_collection
     */ 
    public function getId_collection() : int
    {
        return $this->id_collection;
    }

    /**
     * Set the value of id_collection
     *
     * @return  self
     */ 
    public function setId_collection($id_collection)
    {
        $this->id_collection = $id_collection;

        return $this;
    }

    /**
     * Get the value of nom
     */ 
    public function getNom() : string
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
     * Get description
     */ 
    public function getPrix() : int
    {
        return $this->prix;
    }

    /**
     * Set description
     *
     * @return  self
     */ 
    public function setPrix($prix) 
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get the value of reduction
     */ 
    public function getReduction() : int
    {
        return $this->reduction;
    }

    /**
     * Set the value of reduction
     *
     * @return  self
     */ 
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of categorie
     */ 
    public function getCategorie() : string
    {
        return $this->categorie;
    }

    /**
     * Set the value of categorie within the haystack
     *
     * @return  self
     */ 
    public function setCategorie($categorie)
    {
        $haystack = ["POSTER","ACCESSOIRE","PANTALON","SWEAT","TSHIRT"];

        if(!in_array($categorie,$haystack)){
            $this->categorie = $categorie;
        }

        return $this;
    }
}