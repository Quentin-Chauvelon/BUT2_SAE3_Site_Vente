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


    public function estAdmin() {
        $estAdmin = false;

        if ($this->SessionExistante()) {
            $email = $this->session->get("email");

            $estAdmin = $this->ModeleClient
                ->where('adresse_email', $email)
                ->first()
                ->est_admin;
        }

        return $estAdmin;
    }

    public function ProduitsPlusPopulaires() {
        $produitsPlusPopulaires = array();
        $idProduitMinimum = -1;

        foreach ($this->ModeleProduit->findAll() as $produit) {
            $idProduit = $produit->id_produit;

            $quantiteProduitsVendus = count(
                $this->ModeleExemplaire
                    ->where('id_produit', $idProduit)
                    ->where('est_disponible', false)
                    ->findAll()
            );

            // si le tableau n'est pas encore plein, on met l'article dedans
            if (count($produitsPlusPopulaires) < 3) {
                $produitsPlusPopulaires[$idProduit] = $quantiteProduitsVendus;

                $idProduitMinimum = $this->ProduitsLeMoinsVenduParmiLesPlusPopulaires($produitsPlusPopulaires);

                continue;
            }

            if ($quantiteProduitsVendus > $produitsPlusPopulaires[$idProduitMinimum]) {
                unset($produitsPlusPopulaires[$idProduitMinimum]);
                $produitsPlusPopulaires[$idProduit] = $quantiteProduitsVendus;

                $idProduitMinimum = $this->ProduitsLeMoinsVenduParmiLesPlusPopulaires($produitsPlusPopulaires);
            }
        }

        arsort($produitsPlusPopulaires);

        $produitsPlusVendus = array();

        foreach ($produitsPlusPopulaires as $idProduit => $quantite) {
            $produitsPlusVendus[] = $this->ModeleProduit->find($idProduit);
        }

        return $produitsPlusVendus;
    }

    public function ProduitsLeMoinsVenduParmiLesPlusPopulaires($produitsPlusPopulaires) {
        $quantiteProduitMoinsVendu = 1000000;
        $idProduitMoinsVendu = -1;

        foreach($produitsPlusPopulaires as $idProduit => $quantite) {

            if ($quantite < $quantiteProduitMoinsVendu) {
                $quantiteProduitMoinsVendu = $quantite;
                $idProduitMoinsVendu = $idProduit;
            }
        }

        return $idProduitMoinsVendu;
    }
}
