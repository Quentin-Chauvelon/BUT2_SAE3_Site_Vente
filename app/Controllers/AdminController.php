<?php

namespace App\Controllers;

use App\Models\ModeleClient;
use App\Models\ModeleProduit;
use App\Models\ModeleExemplaire;
use App\Models\ModeleCollection;
use App\Models\ModeleCommande;
use App\Entities\Taille;
require_once APPPATH  . 'Entities' . DIRECTORY_SEPARATOR . 'Taille.php';


class AdminController extends BaseController
{

    public function __construct()
    {
        $this->ModeleClient = ModeleClient::getInstance();
        $this->ModeleProduit = ModeleProduit::getInstance();
        $this->ModeleExemplaire = ModeleExemplaire::getInstance();
        $this->ModeleCollection = ModeleCollection::getInstance();
        $this->ModeleCommande = ModeleCommande::getInstance();
        
        $this->request = \Config\Services::request();
    }

    /** returnAdminView : Redirige vers la vue admin désirée.
     * Ne vérifie pas si l'utilisateur est admin.
     * @param string $notHidden L'onglet de la vue admin qu'on souhaite afficher, comme "utilisateurs" ou "produits".
     * @return string La vue admin avec l'onglet désiré.
     */
    public function returnAdminView(string $notHidden): string
    {
        $exemplaires = $this->ModeleExemplaire->getExemplairesDispo();

        return view("adminView", array("notHidden" => $notHidden, "utilisateurs" => $this->ModeleClient->findAll(), "produits" => $this->ModeleProduit->findAll(), "collections"=> $this->ModeleCollection->findAll(), "exemplaires" => $exemplaires, "tailles" => array("XS", "S", "M", "L", "XL", "XXL"), "commandes" => $this->ModeleCommande->findAll()));
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

    private function setAdmin(int $idClient, bool $est_admin): string
    {
        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        try {
            $this->ModeleClient->update($idClient, array("est_admin" => $est_admin));
        } catch (\Exception $e) {
        }
        return $this->returnAdminView('utilisateurs');
    }


    public function mettreAdmin($idClient) {
        return $this->setAdmin($idClient, true);
    }


    public function enleverAdmin($idClient) {
        return $this->setAdmin($idClient, false);
    }


    public function supprimerUtilisateur($idClient) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $this->ModeleClient->delete($idClient);

        return $this->returnAdminView('utilisateurs');
    }


    public function creerProduit() {
        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $nom = $this->request->getPost('nom');
        $prix = $this->request->getPost('prix');
        $description = $this->request->getPost('description');
        $categorie = $this->request->getPost('categorie');
        $idCollection = $this->request->getPost('id_collection');
    
        $result = false;

        if ($nom != "" && $prix > 0) {
            $result = $this->ModeleProduit->creerProduit($nom, $prix, $description, $categorie, ($idCollection == "") ? NULL : (int)$this->request->getPost($idCollection));
        }

        if ($result) {
            $produit = NULL;

            try {
                $produit = $this->ModeleProduit
                    ->where('nom', $nom)
                    ->first();
            } catch (\Exception) {
                
            }

            if ($produit != NULL) {
                    
                $idProduit = $produit->id_produit;

                mkdir("images/produits/" . (string)$idProduit);
                mkdir("images/produits/" . (string)$idProduit . "/images");
                mkdir("images/produits/" . (string)$idProduit . "/couleurs");

                // Count total files
                $countfiles = count($_FILES['images']['name']);
                
                // Looping all files
                for($i=0;$i<$countfiles;$i++){
                    $filename = $_FILES['images']['tmp_name'][$i];
            
                    // Upload file
                    //move_uploaded_file($filename, "images/produits/" . (string)$idProduit . "/images/image_"  . (string)$i . "." . $_FILES['images']['type'][$i]);
                    move_uploaded_file($filename, "images/produits/" . (string)$idProduit . "/images/image_" . (string)($i + 1) . "." . str_replace("image/", "", $_FILES['images']['type'][$i]));
                    //rename("images/produits/" . (string)$idProduit . "/images/" . $filename, site_url() . "images/produits/" . (string)$idProduit . "/images/image_" . (string)$i . "." . $_FILES['images']['type'][$i]);
                }
            }
        }

        return $this->returnAdminView('produits');
    }


    public function modifierProduitVue($idProduit)
    {
        if (!$this->estAdmin())
        {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $produit = $this->ModeleProduit->find($idProduit);

        return view('modifierProduitVue', array("produit" => $produit, "collections" => $this->ModeleCollection->findAll(), "session" => $this->getDonneesSession()));
    }
    

    public function modifierProduit() {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
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

        try{
            $this->ModeleProduit->save($produit);
        } catch (\Exception $e) {}

        return $this->returnAdminView('produits');
    }


    public function ajouterImageProduit() {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $filename = $_FILES['image']['tmp_name'];

        // si l'utilisateur a annulé la sélection d'image, ça envoie une image vide
        if ($filename != "") {
            $idProduit = $this->request->getPost("id_produit");

            $nbImages = count(glob("images/produits/" . (string)$idProduit . "/images/" . "*"));
            //$nbImages = count(scandir("images/produits/" . $idProduit . "/images"))-2;

            // 8 images maximum
            if ($nbImages < 8) {
                move_uploaded_file($filename, "images/produits/" . (string)$idProduit . "/images/image_" . (string)($nbImages + 1) . "." . str_replace("image/", "", $_FILES['image']['type']));
            }
        }

        return $this->modifierProduitVue($idProduit);
    }


    public function reordonnerImagesProduits() {

        $idProduit = (string)$this->request->getPost("id_produit");

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $ordre = array();

        for ($i=1; $i < 9; $i++) {
            $produit = $this->request->getPost("produit" . (string)$i);

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

            if (array_key_exists($i, $ordre) && $ordre[$i] != $i) {

                // changer la valeur du tableau pour pas le rééchanger une 2ème fois les images
                if (file_exists("images/produits/" . (string)$idProduit . "/images/image_" . $i . ".jpg") && file_exists("images/produits/" . (string)$idProduit . "/images/image_" . $ordre[$i] . ".jpg")) {
                    rename("images/produits/" . (string)$idProduit . "/images/image_" . $i . ".jpg", "images/produits/" . (string)$idProduit . "/images/tmp");
                    rename("images/produits/" . (string)$idProduit . "/images/image_" . $ordre[$i] . ".jpg", "images/produits/" . (string)$idProduit . "/images/image_" . $i . ".jpg");
                    rename("images/produits/" . (string)$idProduit . "/images/tmp", "images/produits/" . (string)$idProduit . "/images/image_" . $ordre[$i] . ".jpg");
                }

                if (file_exists("images/produits/" . (string)$idProduit . "/images/image_" . $i . ".png") && file_exists("images/produits/" . (string)$idProduit . "/images/image_" . $ordre[$i] . ".png")) {
                    var_dump("ici");
                    rename("images/produits/" . (string)$idProduit . "/images/image_" . $i . ".png", "images/produits/" . (string)$idProduit . "/images/tmp");
                    rename("images/produits/" . (string)$idProduit . "/images/image_" . $ordre[$i] . ".png", "images/produits/" . (string)$idProduit . "/images/image_" . $i . ".png");
                    rename("images/produits/" . (string)$idProduit . "/images/tmp", "images/produits/" . (string)$idProduit . "/images/image_" . $ordre[$i] . ".png");
                }

                // $imagesDejaInversees[] = $i;
                // $imagesDejaInversees[] = $ordre[i];
            }
        }

        // return $this->modifierProduitVue($idProduit);
    }


    public function supprimerImageProduit($idProduit, $numeroImage) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }


        $idProduit = (string)$idProduit;
        $nbImages = count(glob("images/produits/" . $idProduit . "/images/" . "*"));

        if ($nbImages == 1) {
            return $this->modifierProduitVue($idProduit);
        }

        if (file_exists("images/produits/" . $idProduit . "/images/image_" . $numeroImage . ".jpg")) {
            unlink("images/produits/" . $idProduit . "/images/image_" . $numeroImage . ".jpg");
        }

        if (file_exists("images/produits/" . $idProduit . "/images/image_" . $numeroImage . ".png")) {
            unlink("images/produits/" . $idProduit . "/images/image_" . $numeroImage . ".png");
        }

        if (file_exists("images/produits/" . $idProduit . "/images/image_" . $numeroImage . ".jpeg")) {
            unlink("images/produits/" . $idProduit . "/images/image_" . $numeroImage . ".jpeg");
        }

        // on change le nom des images pour qu'ils aillent toujours de 1 au nombre d'images (pas de trous)
        for ($i = $numeroImage + 1; $i <= $nbImages; $i++) {
            if (file_exists("images/produits/" . $idProduit . "/images/image_" . $i . ".jpg")) {
                rename("images/produits/" . $idProduit . "/images/image_" . $i . ".jpg", "images/produits/" . $idProduit . "/images/image_" . ($i - 1) . ".jpg");
            }

            if (file_exists("images/produits/" . $idProduit . "/images/image_" . $i . ".png")) {
                rename("images/produits/" . $idProduit . "/images/image_" . $i . ".png", "images/produits/" . $idProduit . "/images/image_" . ($i - 1) . ".png");
            }

            if (file_exists("images/produits/" . $idProduit . "/images/image_" . $i . ".jpeg")) {
                rename("images/produits/" . $idProduit . "/images/image_" . $i . ".jpeg", "images/produits/" . $idProduit . "/images/image_" . ($i - 1) . ".jpeg");
            }
        }

        return $this->modifierProduitVue($idProduit);
    }


	function deleteDirectory($dir) {
	    if (!file_exists($dir)) {
		    return true;
	    }

	    if (!is_dir($dir)) {
		    return unlink($dir);
	    }

	    foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

	    return rmdir($dir);
	}



    public function supprimerProduit($idProduit) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        try {
            $this->ModeleProduit->delete($idProduit);
        } catch (\Exception $e) {}
        $this->ModeleProduit->delete($idProduit);

        if(is_dir("images/produits/" . (string)$idProduit)) {
		    $this->deleteDirectory("images/produits/" . (string)$idProduit);
        }

        return $this->returnAdminView('produits');
    }


    public function creerExemplaire() {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }
        $idProduit = $this->request->getPost('id_produit');
        $couleur = $this->request->getPost('couleur');
        $taille = $this->request->getPost('taille');
        $quantite = (int)$this->request->getPost('quantite');

        if ($quantite == null || $quantite <= 0)
        {
            $quantite = 1;
        }
        
        $this->ModeleExemplaire->creerExemplaire($idProduit, $couleur, $taille, $quantite);

        // $idProduit = $this->request->getPost('id_produit');
        // $couleur = $this->request->getPost('couleur');

        // $exemplaire = array(
        //     "id_exemplaire" => 0,
        //     "id_produit" => $idProduit,
        //     "id_commande" => NULL,
        //     "date_obtention" => date("Ymd"),
        //     "est_disponible" => true,
        //     "taille" => $this->request->getPost('taille'),
        //     "couleur" => lcfirst($couleur),
        // );

        // $this->ModeleExemplaire->insert($exemplaire);

        if (!file_exists("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".jpg") && !file_exists("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".png")) {
            $filename = $_FILES['image']['tmp_name'];

            move_uploaded_file($filename, "images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . "." . str_replace("image/", "", $_FILES['image']['type']));
        }

        return $this->returnAdminView('exemplaires');
    }


    public function supprimer1Exemplaire(int $idProduit, string $taille, string $couleur) {
        
        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $idExemplaire = $this->ModeleExemplaire
            ->where('id_produit', $idProduit)
            ->where('taille', $taille)
            ->where('couleur', $couleur)
            ->where('est_disponible', true)
            ->first();

        if ($exemplaire != NULL)
        {
            try {
                $exemplaire->quantite -=  1;
                $this->ModeleExemplaire->save($exemplaire);
            } catch (\Exception) {}
        }

        // on supprimer l'image de la couleur s'il n'y a plus d'exemplaire avec la couleur donnée
        if (count($this->ModeleExemplaire
            ->where('id_produit', $idProduit)
            ->where('couleur', $couleur)
            ->where('est_disponible', true)
            ->findAll()
        ) == 0) {

            if (file_exists("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".jpg")) {
                unlink("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".jpg");
            }

            if (file_exists("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".png")) {
                unlink("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".png");
            }

            if (file_exists("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".jpeg")) {
                unlink("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".jpeg");
            }
        }

        return $this->returnAdminView('exemplaires');
    }

    public function supprimerTousLesExemplaires(int $idProduit, string $taille, string $couleur) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $exemplaire = $this->ModeleExemplaire->getExemplairesDispoParProduitCouleurTaille($idProduit, $couleur, $taille);
        if ($exemplaire != NULL)
        {
            try {
                $this->ModeleExemplaire->delete($exemplaire->id_exemplaire);
            } catch (\Exception) {}
        }

        // on supprimer l'image de la couleur s'il n'y a plus d'exemplaire avec la couleur donnée
        if (count($this->ModeleExemplaire
            ->where('id_produit', $idProduit)
            ->where('couleur', $couleur)
            ->where('est_disponible', true)
            ->findAll()
        ) == 0) {

            if (file_exists("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".jpg")) {
                unlink("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".jpg");
            }

            if (file_exists("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".png")) {
                unlink("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".png");
            }

            if (file_exists("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".jpeg")) {
                unlink("images/produits/" . (string)$idProduit . "/couleurs/" . $couleur . ".jpeg");
            }
        }

        return $this->returnAdminView('exemplaires');
    }


    public function creerCollection() {
        
        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $today = date("Ymd");
        $date_limite = $this->request->getPost('date_limite');
        $nom = $this->request->getPost('nom');
        $this->ModeleCollection->creerCollection($nom);
        $dateLimiteArray = explode("-", $this->request->getPost('date_limite'));

        $dateLimite = $dateLimiteArray[0] . $dateLimiteArray[1] . $dateLimiteArray[2];

        $collection = array(
            "id_collection" => 0,
            "nom" => $this->request->getPost('nom'),
            "parution" => $today,
            "date_limite" => $dateLimite,
        );

        if ($date_limite > $today) {
            $this->ModeleCollection->update($this->ModeleCollection->getCollectionParNom($nom)->id_collection, array('date_limite' => $date_limite));
        }

        return $this->returnAdminView('collections');
    }


    public function supprimerCollection($idCollection) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        try {
            $this->ModeleCollection->delete($idCollection);
        } catch (\Exception $e) {
        }
        return $this->returnAdminView('collections');
    }
}