<?php
require_once 'Framework/Controleur.php';
require_once 'Modele/Variantes.php';

class ControleurVariantes extends Controleur
{
    private $ouverture;
    private $variantes;
    
    public function __construct() {
        $this->ouvertures = new Ouvertures;
        $this->variantes = new Variantes;
    }

    public function variantes()
    {
        $idOuverture = $this->requete->getParametre("id");
        
        if (isset($_GET['but']))
        {
            $idVariante = $this->requete->getParametre("variante");
            $this->variantes->effacerVariante($idVariante);
        }
        
        $ouverture = $this->ouvertures->get($idOuverture);
        $variantes = $this->variantes->getVariantes($idOuverture);
        
        $this->genererVue(array(
            'ouverture' => $ouverture,
            'variantes' => $variantes));
        
        
        //$coupsouverture = $this->coups->getCoups($idOuverture);
        
        //$this->genererVue(array('ouverture' => $ouverture, 'variantes' => $variantes, 'coupsouverture' => $coupsouverture));
    }

}