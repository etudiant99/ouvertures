<?php
require_once 'Framework/Controleur.php';
require_once 'Modele/Ouvertures.php';

class ControleurNouvelleouverture extends Controleur
{
    private $type;

    public function __construct() {
        $this->ouvertures = new Ouvertures();
    }

    public function nouvelleouverture()
    {
        $idtype = $this->requete->getParametre("type");
        
        
        $this->genererVue(array('idtype' => $idtype));
    }
}