<?php

namespace App\Controllers;

use App\Models\ModeleClient;
use App\Models\ModeleProduit;
use App\Models\ModeleExemplaire;
use App\Models\ModeleCollection;
use App\Models\ModeleCommande;


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


    public function returnAdminView(string $notHidden) {
        $exemplaires = $this->ModeleExemplaire->findAll();

        $exemplairesParCouleurTailleProduits = array();

        foreach ($exemplaires as $exemplaire) {
            $idProduit = $exemplaire->id_produit;
            $taille = $exemplaire->taille;
            $couleur = $exemplaire->couleur;
            $estDisponible = $exemplaire->est_disponible;

            if (!array_key_exists($exemplaire->id_produit, $exemplairesParCouleurTailleProduits)) {
                $exemplairesParCouleurTailleProduits[$idProduit] = array();
            }

            if (!array_key_exists($taille, $exemplairesParCouleurTailleProduits[$idProduit])) {
                $exemplairesParCouleurTailleProduits[$idProduit][$taille] = array();
            }

            if (array_key_exists($couleur, $exemplairesParCouleurTailleProduits[$idProduit][$taille])) {
                if ($estDisponible) {
                    $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur][0] += 1;
                }

                $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur][1] += 1;
            }

            else {
                if ($estDisponible) {
                    $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur] = array(1, 1);
                } else {
                    $exemplairesParCouleurTailleProduits[$idProduit][$taille][$couleur] = array(0, 1);
                }
            }
        }

        // $notHidden = "exemplaires";
        return view("adminView", array("notHidden" => $notHidden, "utilisateurs" => $this->ModeleClient->findAll(), "produits" => $this->ModeleProduit->findAll(), "collections" => $this->ModeleCollection->findAll(), "exemplaires" => $exemplairesParCouleurTailleProduits, "commandes" => $this->ModeleCommande->findAll()));
    }


    public function adminView() {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        // si la variable de session n'est pas définie, on redirige l'utilisateur vers la page d'inscription
        if (!$this->SessionExistante()) {
            return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
        }

        $email = $this->session->get("email");
        $client =  $this->ModeleClient->where('adresse_email', $email)->first();
        // $hashedPassword = $client->password;

        // // si les mots de passe sont différents, alors on retourne une erreur
        // if (!password_verify($password, $hashedPassword)) {
        //     return view('home', array("estAdmin" => true, "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        // }

        if ($client->est_admin) {
            return $this->returnAdminView('utilisateurs');
        }
    }


    public function mettreAdmin($idClient) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $client = array(
            "est_admin" => true
        );

        $this->ModeleClient->update($idClient, $client);

        return $this->returnAdminView('utilisateurs');
    }

    
    public function enleverAdmin($idClient) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $client = array(
            "est_admin" => false
        );

        $this->ModeleClient->update($idClient, $client);

        return $this->returnAdminView('utilisateurs');
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

        $idCollection = $this->request->getPost('id_collection');

        $produit = array(
            "id_produit" => 0,
            "id_collection" => ($idCollection != "") ? $idCollection : NULL,
            "nom" => $this->request->getPost('nom'),
            "prix" => $this->request->getPost('prix'),
            "reduction" => $this->request->getPost('reduction'),
            "description" => $description = $this->request->getPost('description'),
            "categorie" => $this->request->getPost('categorie'),
            "parution" => date("Ymd")
        );

        $this->ModeleProduit->insert($produit);

        $idProduit = $this->ModeleProduit->getInsertID();


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

        return $this->returnAdminView('produits');
    }


    public function modifierProduitVue($idProduit) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $produit = $this->ModeleProduit->find($idProduit);

        return view('modifierProduitVue', array("produit" => $produit, "collections" => $this->ModeleCollection->findAll(), "session" => $this->getDonneesSession()));
    }
    

    public function modifierProduit() {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $idCollection = $this->request->getPost('id_collection');
        $prix = $this->request->getPost('prix');
        $reduction = $this->request->getPost('reduction');

        $produit = array(
            "id_collection" => ($idCollection != "") ? $idCollection : NULL,
            "nom" => $this->request->getPost('nom'),
            "prix" => $prix,
            "reduction" => $reduction,
            "description" => $this->request->getPost('description'),
            "categorie" => $this->request->getPost('categorie'),
        );

        if ($prix >= 0 && $reduction >= 0) {
            $this->ModeleProduit->update($this->request->getPost('id_produit'), $produit);
        }

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

        $exemplaire = array(
            "id_exemplaire" => 0,
            "id_produit" => $idProduit,
            "id_commande" => NULL,
            "date_obtention" => date("Ymd"),
            "est_disponible" => true,
            "taille" => $this->request->getPost('taille'),
            "couleur" => lcfirst($couleur),
        );

        $this->ModeleExemplaire->insert($exemplaire);

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

        if ($idExemplaire != NULL) {
            $this->ModeleExemplaire->delete($idExemplaire->id_exemplaire);
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

        $this->ModeleExemplaire
            ->where('id_produit', $idProduit)
            ->where('taille', $taille)
            ->where('couleur', $couleur)
            ->where('est_disponible', true)
            ->delete();

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
        $dateLimiteArray = explode("-", $this->request->getPost('date_limite'));

        $dateLimite = $dateLimiteArray[0] . $dateLimiteArray[1] . $dateLimiteArray[2];

        $collection = array(
            "id_collection" => 0,
            "nom" => $this->request->getPost('nom'),
            "parution" => $today,
            "date_limite" => $dateLimite,
        );

        if ($dateLimite > $today) {
            $this->ModeleCollection->insert($collection);
        }

        return $this->returnAdminView('collections');
    }


    public function supprimerCollection($idCollection) {

        if (!$this->estAdmin()) {
            return view('home', array("estAdmin" => $this->estAdmin(), "produitsPlusPopulaires" => $this->ProduitsPlusPopulaires(), "session" => $this->getDonneesSession()));
        }

        $this->ModeleCollection->delete($idCollection);

        return $this->returnAdminView('collections');
    }
}
