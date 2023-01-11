<?php

namespace App\Controllers;

use App\Models\ModeleClient;
use App\Models\ModeleProduit;
use App\Models\ModeleExemplaire;
use App\Models\ModeleCollection;
use App\Models\ModeleCommande;
use App\Models\ModeleCoupon;
use App\Enums\Taille;
use Config\Services;
use Exception;

require_once APPPATH  . 'Controllers' . DIRECTORY_SEPARATOR . 'GetExtensionImage.php';


/**
 * Le contrôleur pour les fonctions d'administrateurs.
 */
class AdminController extends BaseController
{

    public function __construct()
    {
        $this->ModeleClient = ModeleClient::getInstance();
        $this->ModeleProduit = ModeleProduit::getInstance();
        $this->ModeleExemplaire = ModeleExemplaire::getInstance();
        $this->ModeleCollection = ModeleCollection::getInstance();
        $this->ModeleCommande = ModeleCommande::getInstance();
        $this->ModeleCoupon = ModeleCoupon::getInstance();
        
        $this->request = Services::request();
    }

    /** returnAdminView : Redirige vers la vue admin désirée.
     * Ne vérifie pas si l'utilisateur est admin.
     * @param string $notHidden L'onglet de la vue admin qu'on souhaite afficher, comme "utilisateurs" ou "produits".
     * @return string La vue admin avec l'onglet désiré.
     */
    public function returnAdminView(string $notHidden): string
    {
        return view("adminView", array(
            "notHidden" => $notHidden,
            "utilisateurs" => $this->ModeleClient->findAll(),
            "produits" => $this->ModeleProduit->findAll(),
            "collections"=> $this->ModeleCollection->findAll(),
            "exemplaires" => $this->ModeleExemplaire->getExemplairesDispo(),
            "taillesVetements" => Taille::vetements(),
            "taillesPosters" => Taille::posters(),
            "commandes" => $this->ModeleCommande->findAll(),
            "coupons" => $this->ModeleCoupon->findAll()));
    }


    /** adminView : Redirige vers la vue admin.
     * @return string La vue admin.
     */
    public function adminView(): string {

        if (!$this->estAdmin()) { // Retourner sur la page d'accueil si l'utilisateur n'est pas admin
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }
        
        return $this->returnAdminView('utilisateurs');
    }


    /**
     * Retourne la vue home.
     * @return string La vue home.
     */
    public function home(): string {
        return view('home', array(
            "estAdmin" => $this->estAdmin(),
            "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(),
            "session" => $this->getDonneesSession()
        ));
    }

    
    /**
     * setAdmin Donne ou enlève la permission d'admin à un utilisateur.
     * @param int $idClient Le client dont on change le statut.
     * @param bool $est_admin Le nouveau statut du client.
     * @return string La vue admin ou la vue home si l'utilisateur connecté n'est pas admin.
     */
    private function setAdmin(int $idClient, bool $est_admin): string
    {
        if (!$this->estAdmin()) {
            return $this->home();
        }

        try {
            $this->ModeleClient->update($idClient, array("est_admin" => $est_admin));
        } catch (Exception) {}

        return $this->returnAdminView('utilisateurs');
    }


    /**
     * setAdmin Donne la permission d'admin à un utilisateur.
     * @param int $idClient Le client dont on change le statut.
     * @return string La vue admin ou la vue home si l'utilisateur connecté n'est pas admin.
     */
    public function mettreAdmin(int $idClient): string {
        return $this->setAdmin($idClient, true);
    }


    /**
     * setAdmin Enlève la permission d'admin à un utilisateur.
     * @param int $idClient Le client dont on change le statut.
     * @return string La vue admin ou la vue home si l'utilisateur connecté n'est pas admin.
     */
    public function enleverAdmin(int $idClient): string {
        return $this->setAdmin($idClient, false);
    }


    /**
     * supprimerClient Supprime un client.
     * @param int $idClient Le client à supprimer.
     * @return string La vue admin ou la vue home si l'utilisateur connecté n'est pas admin.
     */
    public function supprimerUtilisateur(int $idClient): string {

        if (!$this->estAdmin()) {
            return $this->home();
        }

        try {
            $this->ModeleClient->delete($idClient);
        } catch (Exception) {}

        return $this->returnAdminView('utilisateurs');
    }


    /**
     * creerProduit insère un produit dans la base de données.
     * @return string La vue admin ou la vue home si l'utilisateur connecté n'est pas admin.
     */
    public function creerProduit(): string {
        if (!$this->estAdmin()) {
            return $this->home();
        }

        try {
            $nom = $this->request->getPost('nom');
            $prix = $this->request->getPost('prix');
            $description = $this->request->getPost('description');
            $categorie = $this->request->getPost('categorie');
            $idCollection = $this->request->getPost('id_collection');
            $countfiles = count($_FILES['images']['name']);

            $result = false;

            if ($nom != "" && $prix > 0 && $countfiles > 0) {
                $result = $this->ModeleProduit->creerProduit($nom, $prix, $description, $categorie, ($idCollection == "") ? NULL : (int)$this->request->getPost($idCollection));
            }

            if ($result) {
                $produit = $this->ModeleProduit
                    ->where('nom', $nom)
                    ->first();

                if ($produit != NULL) {

                    $idProduit = $produit->id_produit;

                    mkdir("images/produits/" . (string)$idProduit);
                    mkdir("images/produits/" . (string)$idProduit . "/images");
                    mkdir("images/produits/" . (string)$idProduit . "/couleurs");

                    for ($i = 0; $i < $countfiles; $i++) {
                        $filename = $_FILES['images']['tmp_name'][$i];

                        //move_uploaded_file($filename, "images/produits/" . (string)$idProduit . "/images/image_"  . (string)$i . "." . $_FILES['images']['type'][$i]);
                        move_uploaded_file($filename, "images/produits/" . (string)$idProduit . "/images/image_" . (string)($i + 1) . "." . str_replace("image/", "", $_FILES['images']['type'][$i]));
                        //rename("images/produits/" . (string)$idProduit . "/images/" . $filename, site_url() . "images/produits/" . (string)$idProduit . "/images/image_" . (string)$i . "." . $_FILES['images']['type'][$i]);
                    }
                }
            }
        } catch (Exception) {}

        return $this->returnAdminView('produits');
    }


    /**
     * modifierProduitVue Retourne la vue de modification d'un produit.
     * @param int $idProduit L'id du produit à modifier
     * @return string La vue admin ou la vue home si l'utilisateur connecté n'est pas admin.
     */
    public function modifierProduitVue(int $idProduit): string
    {
        if (!$this->estAdmin()) {
            return $this->home();
        }

        $produit = $this->ModeleProduit->find($idProduit);
        if ($produit == NULL)
        {
            return $this->returnAdminView('produits');
        }

        return view('modifierProduitVue', array("produit" => $produit, "collections" => $this->ModeleCollection->findAll(), "session" => $this->getDonneesSession()));
    }


    /**
     * modifierProduit modifie un produit à partir d'un post.
     * @return string La vue du produit.
     */
    public function modifierProduit() {

        if (!$this->estAdmin()) {
            return $this->home();
        }

        $produit = $this->ModeleProduit->find($this->request->getPost('id_produit'));
        
        if ($produit == null) {
            return $this->returnAdminView('produits');
        }

        $idCollection = $this->request->getPost('id_collection');

        $produit->id_collection = ($idCollection == "") ? NULL : (int)$idCollection;
        $produit->prix = $this->request->getPost('prix');
        $produit->nom = $this->request->getPost('nom');
        $produit->description = $this->request->getPost('description');
        $produit->categorie = $this->request->getPost('categorie');
        $produit->reduction = $this->request->getPost('reduction');

        if ($produit->nom != "" && $produit->prix > 0) {
            try{
                $this->ModeleProduit->save($produit);
            } catch (Exception) {}
        }

        return $this->returnAdminView('produits');
    }

    /**
     * ajouterImageProduit permet la sélection d'un fichier image pour un produit.
     * @return string La vue du produit.
     */
    public function ajouterImageProduit(): string {

        if (!$this->estAdmin()) {
            return $this->home();
        }
	
        
        if (count($_FILES) == 0) {
            return $this->adminView();
        }
        
        $filename = $_FILES['image']['tmp_name'];
        $idProduit = $this->request->getPost("id_produit");
        
        // si l'utilisateur a annulé la sélection d'image, ça envoie une image vide
        if ($filename != "") {
            $nbImages = count(glob("images/produits/" . (string)$idProduit . "/images/" . "*"));

            // 8 images maximum
            if ($nbImages < 8) {
                move_uploaded_file($filename, "images/produits/" . (string)$idProduit . "/images/image_" . (string)($nbImages + 1) . "." . str_replace("image/", "", $_FILES['image']['type']));
            }
        }

        return $this->modifierProduitVue($idProduit);
    }

    /**
     * Change l'ordre des images d'un produit.
     * @return string La vue correspondante.
     */
    public function reordonnerImagesProduits(): string {

        if (!$this->estAdmin()) {
            return $this->home();
        }
        
        $idProduit = (string)$this->request->getPost("id_produit");
        $ordre = array();

        // on récupère l'ordre des images dans le post pour savoir lesquelles échanger.
        for ($i=1; $i < 9; $i++) {
            $produit = $this->request->getPost("produit" . $i);
            
            if ($produit != NULL) {
                $ordre[$i] = (int)$produit;
            }
            else {
                break;
            }
        }

        $imagesDejaInversees = array();

        // on inverse les images si elles ont été réordonnées
        for ($i=1; $i < 9; $i++) {

            if (array_key_exists($i, $ordre) && $ordre[$i] != $i  && !in_array($i, $imagesDejaInversees)) {

                $extensionI = "";
                $extensionOrdreI = "";

                // on récupère l'extension de la première image
                // if (file_exists("images/produits/" . $idProduit . "/images/image_" . $i . ".jpg")) {
                //     $extensionI = ".jpg";
                // }
                // elseif (file_exists("images/produits/" . $idProduit . "/images/image_" . $i . ".jpeg")) {
                //     $extensionI = ".jpeg";
                // }
                // elseif (file_exists("images/produits/" . $idProduit . "/images/image_" . $i . ".png")) {
                //     $extensionI = ".png";
                // }

                // // on récupère l'extension de la deuxième image
                // if (file_exists("images/produits/" . $idProduit . "/images/image_" . $ordre[$i] . ".jpg")) {
                //     $extensionOrdreI = ".jpg";
                // }
                // elseif (file_exists("images/produits/" . $idProduit . "/images/image_" . $ordre[$i] . ".jpeg")) {
                //     $extensionOrdreI = ".jpeg";
                // }
                // elseif (file_exists("images/produits/" . $idProduit . "/images/image_" . $ordre[$i] . ".png")) {
                //     $extensionOrdreI = ".png";
                // }

                $extensionI = getExtensionImage("images/produits/" . $idProduit . "/images/image_" . $i);
                $extensionOrdreI = getExtensionImage("images/produits/" . $idProduit . "/images/image_" . $ordre[$i]);

                // on inverse les deux images
                if ($extensionI != "" && $extensionOrdreI != "") {
                    rename("images/produits/" . $idProduit . "/images/image_" . $i . $extensionI, "images/produits/" . $idProduit . "/images/tmp");
                    rename("images/produits/" . $idProduit . "/images/image_" . $ordre[$i] . $extensionOrdreI, "images/produits/" . $idProduit . "/images/image_" . $i . $extensionOrdreI);
                    rename("images/produits/" . $idProduit . "/images/tmp", "images/produits/" . $idProduit . "/images/image_" . $ordre[$i] . $extensionI);
                }

                $imagesDejaInversees[] = $i;
                $imagesDejaInversees[] = $ordre[$i];
            }
        }

        return $this->modifierProduitVue($idProduit);
    }

    /**
     * Supprime une image d'un produit.
     * @param int $idProduit L'id du produit.
     * @param int $numeroImage L'index de l'image.
     * @return string La vue.
     */
    public function supprimerImageProduit(int $idProduit, int $numeroImage): string {

        if (!$this->estAdmin()) {
            return $this->home();
        }

        $idProduit = (string)$idProduit;
        $nbImages = count(glob("images/produits/" . $idProduit . "/images/" . "*"));

        if ($nbImages == 1) {
            return $this->modifierProduitVue($idProduit);
        }

        // Trouver l'image avec la bonne extension et la supprimer
        // if (file_exists("images/produits/" . $idProduit . "/images/image_" . $numeroImage . ".jpg")) {
        //     unlink("images/produits/" . $idProduit . "/images/image_" . $numeroImage . ".jpg");
        // }

        // elseif (file_exists("images/produits/" . $idProduit . "/images/image_" . $numeroImage . ".png")) {
        //     unlink("images/produits/" . $idProduit . "/images/image_" . $numeroImage . ".png");
        // }

        // elseif (file_exists("images/produits/" . $idProduit . "/images/image_" . $numeroImage . ".jpeg")) {
        //     unlink("images/produits/" . $idProduit . "/images/image_" . $numeroImage . ".jpeg");
        // }
        $extension = getExtensionImage("images/produits/" . $idProduit . "/images/image_" . $numeroImage);

        if ($extension != "") {
            unlink("images/produits/" . $idProduit . "/images/image_" . $numeroImage . $extension);
        }

        // on change le nom des images pour qu'ils aillent toujours de 1 au nombre d'images (pas de trous)
        for ($i = $numeroImage + 1; $i <= $nbImages; $i++) {
            // if (file_exists("images/produits/" . $idProduit . "/images/image_" . $i . ".jpg")) {
            //     rename("images/produits/" . $idProduit . "/images/image_" . $i . ".jpg", "images/produits/" . $idProduit . "/images/image_" . ($i - 1) . ".jpg");
            // }

            // elseif (file_exists("images/produits/" . $idProduit . "/images/image_" . $i . ".png")) {
            //     rename("images/produits/" . $idProduit . "/images/image_" . $i . ".png", "images/produits/" . $idProduit . "/images/image_" . ($i - 1) . ".png");
            // }

            // elseif (file_exists("images/produits/" . $idProduit . "/images/image_" . $i . ".jpeg")) {
            //     rename("images/produits/" . $idProduit . "/images/image_" . $i . ".jpeg", "images/produits/" . $idProduit . "/images/image_" . ($i - 1) . ".jpeg");
            // }

            $extension = getExtensionImage("images/produits/" . $idProduit . "/images/image_" . $i);

            if ($extension != "") {
                rename("images/produits/" . $idProduit . "/images/image_" . $i . $extension, "images/produits/" . $idProduit . "/images/image_" . ($i - 1) . $extension);
            }
        }

        return $this->modifierProduitVue($idProduit);
    }


    /**
     * Supprime un dossier récursivement à partir de son chemin
     * @param string $dir Le chemin du dossier
     * @return bool True si la suppression a fonctionné.
     */
	function deleteDirectory(string $dir): bool
    {
	    if (!file_exists($dir)) {
		    return true;
	    }

        // Si c'est un fichier il faut le supprimer.
	    if (!is_dir($dir)) {
		    return unlink($dir);
	    }

        // Vider le dossier récursivement
	    foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        // Supprimer le dossier vide
	    return rmdir($dir);
	}

    /**
     * Supprime un produit et ses images.
     * @param int $idProduit L'id du produit.
     * @return string La vue.
     */
    public function supprimerProduit(int $idProduit) {

        if (!$this->estAdmin()) {
            return $this->home();
        }

        $this->ModeleProduit->SupprimerProduit($idProduit);

        // Supprimer les images seulement si le produit a bien été supprimé.
        if (!$this->ModeleProduit->find($idProduit)) {
            if(is_dir("images/produits/" . $idProduit)) {
                $this->deleteDirectory("images/produits/" . $idProduit);
            }
        }

        return $this->returnAdminView('produits');
    }

    /**
     * creerExemplaire ajoute des exemplaires d'un produit à la base de données.
     * @return string La vue.
     */
    public function creerExemplaire(): string {

        if (!$this->estAdmin()) {
            return $this->home();
        }

        $idProduit = $this->request->getPost('id_produit');
        $couleur = $this->request->getPost('couleur');
        $taille = $this->request->getPost('taille');
        $quantite = (int)$this->request->getPost('quantite');

        $produit = $this->ModeleProduit->find($idProduit);

        if ($produit == NULL || $couleur == "" || $taille == "") {
            return $this->returnAdminView('exemplaires');
        }

        if ($quantite == null || $quantite <= 0) {
            $quantite = 1;
        }

        $tailleEnum = Taille::tryFrom($taille);

        if ($tailleEnum == NULL) {
            return $this->returnAdminView('exemplaires');
        }

        // on s'assure que la taille choisie pour l'exemplaire correspond bien à une taille possible en fonction de la catégorie du produit
        if ($produit->categorie == "poster") {
            if (!$tailleEnum->estPoster()) {
                return $this->returnAdminView('exemplaires');
            }
        }
        
        elseif ($produit->categorie == "accessoire") {
            if (!$tailleEnum->estAccessoire()) {
                return $this->returnAdminView('exemplaires');
            }
        }

        else {
            if (!$tailleEnum->estVetement()) {
                return $this->returnAdminView('exemplaires');
            }
        }

        if ($taille == "Standard") {
            $taille = NULL;
        }

        $this->ModeleExemplaire->creerExemplaire((int)$idProduit, $couleur, $taille, $quantite);

        // if (!file_exists("images/produits/" . $idProduit . "/couleurs/" . $couleur . ".jpg") && !file_exists("images/produits/" . $idProduit . "/couleurs/" . $couleur . ".png") && !file_exists("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".jpeg")) {
        if (getExtensionImage("images/produits/" . $idProduit . "/couleurs/" . $couleur) == "") {
            $filename = $_FILES['image']['tmp_name'];

            move_uploaded_file($filename, "images/produits/" . $idProduit . "/couleurs/" . $couleur . "." . str_replace("image/", "", $_FILES['image']['type']));
        }

        return $this->returnAdminView('exemplaires');
    }

    /**
     * modifierExemplaireImagesVue charge la vue pour modifier des images d'un exemplaire.
     * @return string La vue.
     */
    public function modifierExemplaireImagesVue(int $idProduit): string
    {
        
        if (!$this->estAdmin()) {
            return $this->home();
        }

        return view('modifierExemplaireImagesVue', array("idProduit" => $idProduit, "session" => $this->getDonneesSession()));
    }

    /**
     * modifierImageExemplaire modifie les images d'un exemplaire dans la vue admin.
     * @return string La vue.
     */
    public function modifierImageExemplaire() {
        
        if (!$this->estAdmin()) {
            return $this->home();
        }

        $idProduit = $this->request->getPost("id_produit");
        $couleur = $this->request->getPost("couleur");
        $filename = $_FILES['image']['tmp_name'];

        // si l'utilisateur a annulé la sélection d'image, ça envoie une image vide
        if ($filename != "") {

            if ($idProduit == NULL) {
                return $this->adminView();
            }

            if ($couleur == "") {
                return $this->modifierExemplaireImagesVue($idProduit);
            }

            $extensionImage = "";

            // on détermine l'extension de l'image
            // if (file_exists("images/produits/" . $idProduit . "/couleurs/" . $couleur . ".jpg")) {
            //     $extensionImage = ".jpg";
            // }

            // elseif (file_exists("images/produits/" . $idProduit . "/couleurs/" . $couleur . ".jpeg")) {
            //     $extensionImage = ".jpeg";
            // }

            // elseif (file_exists("images/produits/" . $idProduit . "/couleurs/" . $couleur . ".png")) {
            //     $extensionImage = ".png";
            // }

            $extensionImage = getExtensionImage("images/produits/" . $idProduit . "/couleurs/" . $couleur);

            if ($extensionImage != "") {
                // on supprime l'ancienne image
                unlink("images/produits/" . $idProduit . "/couleurs/" . $couleur . $extensionImage);

                // on la remplace par la nouvelle
                move_uploaded_file($filename, "images/produits/" . $idProduit . "/couleurs/" . $couleur . "." . str_replace("image/", "", $_FILES['image']['type']));
            }
        }

        return $this->modifierExemplaireImagesVue($idProduit);
    }

    /**
     * Supprimer1Exemplaire diminue de 1 la quantité d'un exemplaire.
     * @param int $idProduit L'id du produit.
     * @param string $taille La taille de l'exemplaire.
     * @param string $couleur La couleur de l'exemplaire.
     * @return string La vue admin
     */
    public function supprimer1Exemplaire(int $idProduit, string $taille, string $couleur): string {
        
        if (!$this->estAdmin()) {
            return $this->home();
        }

        $exemplairesDispoParProduitCouleurTaille = $this->ModeleExemplaire->getExemplairesDispoParProduitCouleurTaille($idProduit, $couleur, $taille);
        $exemplaire = NULL;

        if (count($exemplairesDispoParProduitCouleurTaille) > 0) {
            $exemplaire = $exemplairesDispoParProduitCouleurTaille[0];
        }

        $exemplaire->quantite = (int)$exemplaire->quantite;
        // On supprime l'exemplaire si c'est le dernier du stock, sinon on diminue la quantité.
        try {
            if ($exemplaire != NULL)
            {
                if ($exemplaire->quantite <= 1) {
                    $this->ModeleExemplaire->delete($exemplaire->id_exemplaire);
                }
                else {
                    $exemplaire->quantite -=  1;
                    $this->ModeleExemplaire->save($exemplaire);
                }
            }
        } catch (Exception){}

        return $this->returnAdminView('exemplaires');
    }

    /**
     * supprimerTousLesExemplaires vide le stock pour un produit précis
     * @param int $idProduit L'id du produit
     * @param string $taille La taille du produit
     * @param string $couleur La couleur du produit
     * @return string La vue
     */
    public function supprimerTousLesExemplaires(int $idProduit, string $taille, string $couleur) {

        if (!$this->estAdmin()) {
            return $this->home();
        }

        $exemplairesDispoParProduitCouleurTaille = $this->ModeleExemplaire->getExemplairesDispoParProduitCouleurTaille($idProduit, $couleur, $taille);
        $exemplaire = NULL;

        if (count($exemplairesDispoParProduitCouleurTaille) > 0) {
            $exemplaire = $exemplairesDispoParProduitCouleurTaille[0];
        }

        if ($exemplaire != NULL)
        {
            try {
                $this->ModeleExemplaire->delete($exemplaire->id_exemplaire);
            } catch (Exception) {}
        }

        return $this->returnAdminView('exemplaires');
    }


    /**
     * creerCollection ajoute une collection vide de produits dans la base de données.
     * @return string La vue
     */
    public function creerCollection(): string {
        
        if (!$this->estAdmin()) {
            return $this->home();
        }

        $today = date("Y-m-d");
        $date_limite = $this->request->getPost('date_limite');
        $nom = $this->request->getPost('nom');
        $this->ModeleCollection->creerCollection($nom);

        if ($date_limite > $today) {
            try {
                $this->ModeleCollection->update($this->ModeleCollection->getCollectionParNom($nom)->id_collection, array("date_limite" => $date_limite));
            } catch (Exception) {}
            }

        return $this->returnAdminView('collections');
    }

    /**
     * modifierCollectionVue retourne la vue de modification d'une collection
     * @param int $idCollection L'id de la collection désirée.
     * @return string La vue.
     */
    public function modifierCollectionVue(int $idCollection): string
    {
        if (!$this->estAdmin())
        {
            return $this->home();
        }

        $collection = $this->ModeleCollection->find($idCollection);

        return view('modifierCollectionVue', array(
            "collection" => $collection,
            "session" => $this->getDonneesSession()
        ));
    }

    /**
     * modifierCollection change les données d'une collection.
     * @return string La vue de la collection modifiée.
     */
    public function modifierCollection(): string {

        if (!$this->estAdmin()) {
            return $this->home();
        }

        $idCollection = $this->request->getPost('id_collection');

        if ($idCollection == NULL || $idCollection == "") {
            return $this->adminView();
        }

        $collection = $this->ModeleCollection->find($idCollection);
        
        if ($collection == null) {
            return $this->returnAdminView('collections');
        }

        $idCollection = $this->request->getPost('id_collection');

        $collection->id_collection = ($idCollection == "") ? NULL : (int)$idCollection;
        $collection->nom = $this->request->getPost('nom');
        $collection->parution = $this->request->getPost('parution');
        $collection->date_limite = $this->request->getPost('date_limite');

        // Vérifier si le modèle existe et s'il est trouvé
        if ($collection->id_collection != NULL && $collection->nom != "" && $collection->parution < date("Y-m-d") && $collection->date_limite >= date("Y-m-d")) {
            try{
                $this->ModeleCollection->save($collection);
            } catch (Exception) {}
        }

        return $this->returnAdminView('collections');
    }

    /**
     * supprimerCollection s'auto-documente.
     * @param int $idCollection L'id de la collection
     * @return string La vue des collections.
     */
    public function supprimerCollection(int $idCollection): string {

        if (!$this->estAdmin()) {
            return $this->home();
        }

        try {
            $this->ModeleCollection->delete($idCollection);
        } catch (Exception) {}

        return $this->returnAdminView('collections');
    }

    /**
     * creerCoupon ajoute un coupon à la base de données à partir d'un post.
     * @return string
     */
    public function creerCoupon(): string {

        if (!$this->estAdmin()) {
            return $this->home();
        }

        $today = date("Y-m-d");
        $date_limite = $this->request->getPost('date_limite');
        $idCoupon = $this->request->getPost('code_promo');
        $nom = $this->request->getPost('nom');
        $codePromo = $this->request->getPost('code_promo');
        $montant = $this->request->getPost('montant');
        $estPourcentage = $this->request->getPost('est_pourcentage') == "est_pourcentage";
        $estValable = $this->request->getPost('est_valable') == "est_valable";
        $utilisationsMax = $this->request->getPost('utilisations_max');

        $dateLimiteArray = explode("-", $this->request->getPost('date_limite'));
        $dateLimite = $dateLimiteArray[0] . $dateLimiteArray[1] . $dateLimiteArray[2];

        $coupon = array(
            "id_coupon" => $codePromo,
            "nom" => $nom,
            "montant" => (int)$montant,
            "est_pourcentage" => $estPourcentage,
            "est_valable" => $estValable,
            "date_limite" => $dateLimite,
            "utilisations_max" => (int)$utilisationsMax
        );

        try {
            $this->ModeleCoupon->insert($coupon);
        }
        catch (Exception){}

        return $this->returnAdminView('coupons');
    }

    /**
     * modifierCouponVue retourne la vue de modification d'un coupon.
     * @param string $idCoupon L'id du coupon désiré.
     * @return string La vue.
     */
    public function modifierCouponVue(string $idCoupon): string {

        if (!$this->estAdmin())
        {
            return $this->home();
        }

        $coupon = $this->ModeleCoupon->find($idCoupon);

        return view('modifierCouponVue', array(
            "coupon" => $coupon,
            "session" => $this->getDonneesSession()
        ));
    }

    /**
     * modifierCoupon change les données d'un coupon.
     * @return string La vue du coupon modifié.
     */
    public function modifierCoupon(): string {

        if (!$this->estAdmin()) {
            return $this->home();
        }

        $idCoupon = $this->request->getPost('id_coupon');

        if ($idCoupon == NULL || $idCoupon == "") {
            return $this->returnAdminView('coupons');
        }
        
        $coupon = $this->ModeleCoupon->find($idCoupon);
        
        if ($coupon == null) {
            return $this->returnAdminView('coupons');
        }

        $coupon->id_coupon = $idCoupon;
        $coupon->nom = $this->request->getPost('nom');
        $coupon->montant = $this->request->getPost('montant');
        $coupon->est_pourcentage = $this->request->getPost('est_pourcentage') == "est_pourcentage";
        $coupon->est_valable = $this->request->getPost('est_valable') == "est_valable";
        $coupon->date_limite = $this->request->getPost('date_limite');
        $coupon->utilisations_max = $this->request->getPost('utilisations_max');

        if ($coupon->montant >= 0 && $coupon->date_limite >= date("Y-m-d") && $coupon->utilisations_max >= 0) {
            try{
                $this->ModeleCoupon->save($coupon);
            } catch (Exception) {}
        }

        return $this->returnAdminView('coupons');
    }

    /**
     * supprimerCoupon supprime un coupon de la base de données.
     * @param int $idCoupon L'identifiant du coupon à supprimer.
     * @return string La vue des coupons
     */
    public function supprimerCoupon(int $idCoupon): string {
        if (!$this->estAdmin()) {
            return $this->home();
        }

        try {
            $this->ModeleCoupon->delete($idCoupon);
        } catch (Exception) {}
        
        return $this->returnAdminView('coupons');
    }
}
