<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ClientEntity;


class ClientModel extends Model
{

    public function creerCompte(ClientEntity $client) {
        $sql = "CALL CreerClient(?, ?, ?, ?)";
		$query = $this->db->query($sql, [$client->getAdresse_email(), $client->getNom(), $client->getPrenom(), $client->getPassword()]);
    }


    public function clientAvecEmail(string $email) {
        $sql = "CALL GetClientParEmail(?)";
		$query = $this->db->query($sql, [$email]);
        return $query->getCustomRowObject(0, 'App\Models\ClientEntity');
    }


    public function modifierCompteClient(ClientEntity $client) {
        $sql = "CALL ModifierClient(?, ?, ?, ?, ?, ?)";
		$this->db->query($sql, [$client->getId_client(), $client->getAdresse_email(), $client->getNom(), $client->getPrenom(), $client->getPassword(), false]);
    }


    public function favorisClient(int $id) {
        $sql = "CALL GetFavorisClient(?)";
        $query = $this->db->query($sql, [$id]);
        return $query->getCustomResultObject('App\Models\FavorisEntity');
    }


    public function ajouterFavori(int $idClient, int $idProduit) {
        $sql = "CALL CreerFavori(?, ?)";
        $this->db->query($sql, [$idClient, $idProduit]);
    }


    public function supprimerFavori(int $idClient, int $idProduit) {
        $sql = "CALL SupprimerFavori(?, ?)";
        $this->db->query($sql, [$idClient, $idProduit]);
    }
}
