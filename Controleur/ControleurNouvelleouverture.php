<?php
require_once 'Framework/Controleur.php';
require_once 'Modele/Ouvertures.php';

class ControleurNouvelleouverture extends Controleur
{
    public function __construct() {
        $this->ouvertures = new Ouvertures();
    }

    public function nouvelleouverture()
    {
        $idtype = $this->requete->getParametre("type");
        $type = $this->ouvertures->getType($idtype);
        $nomtype = $type->getType();
        
        $this->genererVue(array('idtype' => $idtype,
                                'nomtype' => $nomtype));
    }
}