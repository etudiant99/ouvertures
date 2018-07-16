<?php
require_once 'Framework/Controleur.php';
require_once 'Modele/Ouvertures.php';

class ControleurOuvertures extends Controleur
{
    public function __construct() {
        $this->ouvertures = new Ouvertures();
    }

    public function ouvertures()
    {
        $idType = $this->requete->getParametre("type");
        $type = $this->ouvertures->getType($idType);
        
        $ouvertures = $type->getOuvertures();
        
        if (isset($_GET['but']))
        {
            $item = $_GET['item'];
            $this->ouvertures->effacerOuverture($item,$idType);
        }
        
        $this->genererVue(array('type' => $type,
        'ouvertures' => $ouvertures));
    }
}