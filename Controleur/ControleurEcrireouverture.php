<?php
require_once 'Framework/Controleur.php';
require_once 'Modele/Ouvertures.php';

class ControleurEcrireouverture extends Controleur
{
    private $type;

    public function __construct() {
        $this->ouvertures = new Ouvertures();
        $this->echiquier = new Echiquier;
    }

    public function ecrireouverture()
    {
        $idtype = $this->requete->getParametre("type");
        $nomouverture = $this->requete->getParametre("ouverture");
        
        if (isset($_GET['lecoup']))
        {
            $lecoup = $_GET['lecoup'];
            $ouverture = $this->ouvertures->OuvertureTemp($lecoup);
        }

        if (isset($_GET['effacer']))
            $this->ouvertures->effacerOuvertureTemp();
        if (isset($_GET['enregistrer']))
            $this->ouvertures->enregistrerOuverture($idtype,$nomouverture);

        
        $ouverture = $this->ouvertures->lecturetable();
        $ignouverture = $ouverture->getTemp();
        
        $flip = false;
        $cliquable = true;
        $couleur = 1;
        $cell1 = -1;
        $cell2 =  -1;
        $totalcoups = 0;
        $lettres = array('a','b','c','d','e','f','g','h');
        
        if (isset($_GET['depart']))
            $cell1 = $_GET['depart'];
        if (isset($_GET['arrivee']))
            $cell2 = $_GET['arrivee'];

        
        $coups = explode(" ", $ignouverture);
        if ($coups[0] != '')
            $totalcoups = sizeof($coups);
        $trait = $ouverture->getTrait();
        
        $this->echiquier->positionarbitraire($ignouverture,$totalcoups);
        $mangeaille = $this->echiquier->getMangeaille();
        $position = $this->echiquier->position();
        $lastmove = $this->echiquier->Lastmove();
        $positions = null;
        
        if($lastmove != '')
        {
            $derniercoup = -1;
            $lmove = explode("-",$lastmove);
            $start = $lmove[0];
            $end = $lmove[1];
            if (($position[$end] == 'p' && $trait == 1) || ($position[$end] == 'P' && $trait == -1))
                $derniercoup = $end;
        }
        else
        {
            $start = -1;
            $end = -1;
            $derniercoup = -1;
        }
    
        if ($cell1 != -1)
        {
            $lapiece = $this->echiquier->trouve($position[$cell1]);
            $lapiece->deplacer($position,$cell1,$trait,$derniercoup);
            $positions = $lapiece->positionsPossibles();
        }

        $this->genererVue(array('idtype' => $idtype,
        'flip' => $flip,
        'couleur' => $couleur,
        'start' => $start,
        'end' => $end,
        'cell1' => $cell1,
        'cell2' => $cell2,
        'position' => $position,
        'cliquable' => $cliquable,
        'trait' => $trait,
        'derniercoup' => $derniercoup,
        'lettres' => $lettres,
        'positions' => $positions,
        'nomouverture' => $nomouverture));
    }
}