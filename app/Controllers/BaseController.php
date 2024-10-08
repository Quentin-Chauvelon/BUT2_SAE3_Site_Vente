<?php

namespace App\Controllers;

use App\Entities\Produit;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\ModeleClient;
use App\Models\ModeleProduit;
use App\Models\ModeleExemplaire;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    protected $session;


    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['url', 'session'];

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $routes = \Config\Services::routes();

        // Preload any models, libraries, etc, here.
        $this->session = \Config\Services::session();
        $this->session->start();

        $this->ModeleClient = ModeleClient::getInstance();
        $this->ModeleProduit = ModeleProduit::getInstance();
        $this->ModeleExemplaire = ModeleExemplaire::getInstance();
        $this->ModeleProduit = ModeleProduit::getInstance();

        // on connecte l'utilisateur si le cookie de rester connecte est définie
        if (isset($_COOKIE["idClient"]) && isset($_COOKIE["password"])) {

            $client =  $this->ModeleClient->find((int)$_COOKIE["idClient"]);

            if ($client == NULL) {
                return view("creerCompte", array("compteDejaExistant" => false, "passwordsDifferents" => false, "session" => $this->getDonneesSession()));
            }

            $hashedPassword = $client->password;

            // si les mots de passes sont identiques, on connecte l'utilisateur
            if ($_COOKIE["password"] == $hashedPassword) {
                $session = $this->getDonneesSession();

                if ($session["id"] == 0 && $session["prenom"] == "") {
                    $this->setDonneesSession(
                        $client->id_client,
                        $client->prenom,
                        $client->nom,
                        $client->adresse_email,
                    );
                }
            }
        }
    }


    public function setDonneesSession(int $id, string $prenom, string $nom, string $email) {
        $donneesClient = [
            'panier'  => array(),
            'id' => $id,
            'prenom'  => $prenom,
            'nom'     => $nom,
            'email' => $email
        ];

        $this->session->set($donneesClient);
    }


    public function getDonneesSession(): array
    {
        return array(
            'panier'  => $this->session->get("panier"),
            'id'  => $this->session->get("id"),
            'prenom' => $this->session->get("prenom"),
            'nom' => $this->session->get("nom"),
            'email' => $this->session->get("email")
        );
    }

    /**
     * Retourne l'id de la bd de l'utilisateur, NULL sans connexion.
     * @return int|null L'identifiant de l'utilisateur connecté
     */
    public function getSessionId(): ?int {
        return $this->session->get('id');
    }

    /**
     * @return bool True si quelqu'un est connecté, false sinon
     */
    public function SessionExistante(): bool
    {
        return $this->session->has('id') && $this->session->get('id') != NULL;
    }

    /**
     * @return bool true si l'utilisateur actuel est admin
     */
    public function estAdmin(): bool {
        
        if (!$this->SessionExistante()) {
            return false;
        }

        $email = $this->session->get("email");
        
        if ($email == NULL) {
            return false;
        }

        try{
            $client = $this->ModeleClient->getClientParEmail($email);
            
            if ($client == null) {
                return false;
            }

            return $client->est_admin;
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Retourne les produits qui ont le plus de ventes.
     * @return Produit[] Les trois produits les plus vendus.
     */
    public function ProduitsPlusPopulaires(): array {
        $produits = $this->ModeleProduit->getAllProduitsPlusVendus();

        if (count($produits) > 3) {
            return array_slice($produits, 0, 3);
        }

        return $produits;
    }

    /**
     * estEnFavori retourne vrai si le produit est en favori pour l'utilisateur connecté.
     * @return bool Vrai si le produit est en favori pour l'utilisateur connecté.
     */
    public function estEnFavori(int $idProduit): bool {
        return $this->ModeleFavori->estEnFavori($this->getSessionId(), $idProduit);
    }
}
