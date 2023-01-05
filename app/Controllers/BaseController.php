<?php

namespace App\Controllers;

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

        // Preload any models, libraries, etc, here.
        $this->session = \Config\Services::session();
        $this->session->start();

        $this->ModeleClient = ModeleClient::getInstance();
        $this->ModeleProduit = ModeleProduit::getInstance();
        $this->ModeleExemplaire = ModeleExemplaire::getInstance();
        $this->ModeleProduit = ModeleProduit::getInstance();
    }


    public function getDonneesSession() {
        return array(
            'panier'  => $this->session->get("panier"),
            'id'  => $this->session->get("id"),
            'prenom' => $this->session->get("prenom"),
            'nom' => $this->session->get("nom"),
            'email' => $this->session->get("email")
        );
    }

    public function getSessionId() {
        return $this->session->get('id');
    }

    public function SessionExistante() {
        return $this->session->has('id') && $this->session->get('id') != NULL;
    }


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

    public function ProduitsPlusPopulaires() {
        $produits = $this->ModeleProduit->getAllProduitsPlusVendus();
        if (count($produits) > 3) {
            return array_slice($produits, 0, 3);
        }
        return $produits;
    }
}
