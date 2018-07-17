<?php
require_once 'Framework/Controleur.php';
require_once 'Modele/Variantes.php';

class ControleurNouvellevariante extends Controleur
{
    public function __construct() {
        $this->variantes = new Variantes;
        $this->ouvertures = new Ouvertures;
    }

    public function nouvellevariante()
    {
        $idouverture = $this->requete->getParametre("ouverture");
        
        $ouvertures = $this->ouvertures->getToutesOuvertures();
        $ouverture = $this->ouvertures->get($idouverture);
        $nomouverture = $ouverture->getOuverture();
        $typeouverture = $ouverture->getType();
        
        $this->genererVue(array('ouvertures' => $ouvertures,
                                'nomouverture' => $nomouverture,
                                'typeouverture' => $typeouverture,
                                'idouverture' => $idouverture));
    }
}