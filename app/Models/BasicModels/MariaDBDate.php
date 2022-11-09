<?php
class MariaDBDate extends DateTime{

    /** @var int L'année de la date
     * 
     * 
     */
    private $annee;

    /** @var int Le mois de la date
     * 
     * 
     */
    private $mois;


    /** @var int Le jour de la date
     * 
     * 
     */
    private $jour;

    /** @var string Le jour de la date
     * 
     * 
     */
    private $date;

    public function __construct(){
        // On considère que le site est seulement utilisé à l'heure française
        $this->date = parent::__construct($datetime = "now", $timezone = new \DateTimeZone("Europe/Paris"));
        // On enlève l'heure de par exemple '2022-11-9 00:17'
        $this->date = substr($this->date,'0','10');
    }

}