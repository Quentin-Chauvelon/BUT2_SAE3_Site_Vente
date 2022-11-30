<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ProductEntity;


class ProductModel extends Model
{

    public function findById(int $id):?ProductEntity {
        
        $sql = "CALL GetProduitParId(?)";
		$query = $this->db->query($sql, [$id]);
        $result = $query->getRow(0);

        $product = new ProductEntity();
        $product->setId_produit($result->id_produit);
        $product->setId_collection($result->id_collection);
        $product->setNom($result->nom);
        $product->setPrix($result->prix);
        $product->setReduction($result->reduction);
        $product->setDescription($result->description);
        $product->setCategorie($result->categorie);

        return $product;
    }
}
