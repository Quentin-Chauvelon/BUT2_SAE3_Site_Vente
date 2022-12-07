<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ClientEntity;


class ClientModel extends Model
{

    public function creerCompte(ClientEntity $client) {

        $sql = "CALL CreerClient(?, ?, ?, ?)";
		$query = $this->db->query($sql, [$client->getAdresse_email(), $client->getNom(), $client->getPrenom(), $client->getPassword()]);

        // return true;
    }


    public function clientAvecEmail(string $email) {
        $sql = "CALL GetClientParEmail(?)";
		$query = $this->db->query($sql, [$email]);
        $result = $query->getCustomRowObject(0, 'App\Models\ClientEntity');

        return $result;
    }
}
