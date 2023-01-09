<?php
/**
 * Le modèle de la table Client.
 */
namespace App\Models;

use App\Entities\Client;
use CodeIgniter\SafeModel;
use Exception;

/**
 * ModeleClient est le modèle utilisé pour la table Client.
 * Elle a pour champs : *id_client*, *adresse_email*, *nom*, *prenom*, *password*, *est_admin*.
 * Hérite de SafeModel pour des try/catch automatiques.
 */
class ModeleClient extends SafeModel
{
    private static ModeleClient $instance;

    protected $table            = 'Client';
    protected $primaryKey       = 'id_client';
    protected $useAutoIncrement = true;
    protected $returnType       = Client::class;
    protected $allowedFields    = ['id_client', 'adresse_email', 'nom', 'prenom', 'password', 'est_admin'];

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * Retourne la seule instance de la classe, car c'est un singleton.
     * @return ModeleClient L'instance du modèle.
     */
    public static function getInstance(): ModeleClient
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModeleClient();
        }
        return self::$instance;
    }

    /**
     * getClient retourne le client dont l'adresse email est passée en paramètre.
     * @param $email L'adresse email du client.
     * @return Client|null Le client ou null si aucun client n'a cette adresse email.
     */
    function getClientParEmail(string $email): ?Client
    {
        $sql = "CALL GetClientParEmail(?)";
        try {
            return $this->db->query($sql, [$email])->getFirstRow('App\Entities\Client');
        } catch (Exception) {
            return null;
        }
    }

    /**
     * getClientParCodeMDPOublie retourne le client pour lequel le code de mot de passe oublié correspond.
     * @param $codeMDPOublie string Le code de mot de passe oublié. Est un hash du mot de passe encrypté dans la bd.
     * @return Client|null Le client ou null si aucun client n'a ce code.
     */
    public function getClientParCodeMDPOublie(string $codeMDPOublie): ?Client
    {
        foreach ($this->findAll() as $client) {
            if (password_verify($client->password, $codeMDPOublie)) {
                return $client;
            }
        }
        return null;
    }
}
