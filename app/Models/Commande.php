<?php
class Coupon
{
    /** @var int L'identifiant unique de la commande
     * 
     * 
     */
    private $id_commande;

    /** @var int L'identifiant unique de client associé à la commande
     * 
     * 
     */
    private $id_client;


    /** @var MariaDBDate La date de la commande
     * 
     * 
     */
    private $date_commande;

    /** @var MariaDBDate La date estimée de la livraison de la commande
     * 
     * 
     */
    private $date_livraison_estimee;

    /** @var MariaDBDate  La date de livraison de la commande
     * 
     * 
     */
    private $date_livraison;

    /** @var string  identifiant d'un coupon associé à la commande
     * 
     * 
     */
    private $id_coupon;

}

?>