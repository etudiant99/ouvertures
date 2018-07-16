<?php
require_once 'Framework/Controleur.php';
require_once 'Modele/Ouvertures.php';

class ControleurAccueil extends Controleur
{
    private $type;

    public function __construct() {
        $this->ouvertures = new Ouvertures();
    }

    public function index()
    {
        $types = $this->ouvertures->getTypes();
        
        $this->genererVue(array('types' => $types));
    }
}