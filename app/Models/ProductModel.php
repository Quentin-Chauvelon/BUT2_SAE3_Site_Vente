<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ProductEntity;


class ProductModel extends Model
{

    public function findById(int $id):?ProductEntity {
        
        $sql = "CALL GetProduitParId(?)";
		$query = $this->db->query($sql, [$id]);
        $product = $query->getCustomRowObject(0, 'App\Models\ProductEntity');
        
        //$result = $query->getRow(0);

        // $product = new ProductEntity();
        // $product->setId_produit($result->id_produit);
        // $product->setId_collection($result->id_collection);
        // $product->setNom($result->nom);
        // $product->setPrix($result->prix);
        // $product->setReduction($result->reduction);
        // $product->setDescription($result->description);
        // $product->setCategorie($result->categorie);

        return $product;
    }


    public function chercherTout() {
        $sql = "CALL GetAllProduits()";
		$query = $this->db->query($sql);
        $result = $query->getCustomResultObject('App\Models\ProductEntity');

        return $result;
    }


    public function trouverToutDeCategorie(string $categorie) {
        
        $sql = "";

        switch ($categorie) {
            case "sweat":
                $sql = "CALL GetAllSweats()";
                break;

            case "tshirt":
                $sql = "CALL GetAllTshirts()";
                break;

            case "pantalon":
                $sql = "CALL GetAllPantalons()";
                break;

            case "accessoire":
                $sql = "CALL GetAllAccessoires()";
                break;

            case "poster":
                $sql = "CALL GetAllPosters()";
                break;
        }

		$query = $this->db->query($sql, [$categorie]);
        return $query->getCustomResultObject('App\Models\ProductEntity');
    }
}