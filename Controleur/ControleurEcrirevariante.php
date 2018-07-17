<?php
require_once 'Modele/Echiquier.php';

class ControleurEcrirevariante extends Controleur
{
    public function __construct() {
        $this->ouvertures = new Ouvertures;
        $this->echiquier = new Echiquier;
        $this->variantes = new Variantes;
    }

    public function ecrirevariante()
    {
        $idouverture = $this->requete->getParametre("ouverture");
        $nomvariante = $this->requete->getParametre("variante");
        $ouverture = $this->ouvertures->get($idouverture);
        $nomouverture = $ouverture->getOuverture();
        $ouverture = '';
        
    if (isset($_GET['lecoup']))
    {
        $lecoup = $_GET['lecoup'];
        $variante = $this->variantes->VarianteTemp($lecoup);
    }

    if (isset($_GET['effacer']))
        $this->variantes->effacerVarianteTemp();
    if (isset($_GET['enregistrer']))
        $this->variantes->enregistrerVariante($idouverture,$nomvariante);

    $variante = $this->variantes->lecturetable();
    $ignouverture = $variante->getTemp();
    
    $lettres = array('a','b','c','d','e','f','g','h');
    $flip = false;
    $cliquable = true;
    $couleur = 1;
    $cell1 = -1;
    $cell2 =  -1;
    
    if (isset($_GET['depart']))
        $cell1 = $_GET['depart'];
    
    if (isset($_GET['arrivee']))
        $cell2 = $_GET['arrivee'];

    $coups = explode(" ", $ignouverture);
    $totalcoups = sizeof($coups);
    $trait = $variante->getTrait();
    
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

        $this->genererVue(array(
            'nomouverture' => $nomouverture,
            'couleur' => $couleur,
            'position' => $position,
            'ignouverture' => $ignouverture,
            'mangeaille' => $mangeaille,
            'lastmove' => $lastmove,
            'flip' => $flip,
            'totalcoups' => $totalcoups,
            'cliquable' => $cliquable,
            'trait' => $trait,
            'cell1' => $cell1,
            'cell2' => $cell2,
            'derniercoup' => $derniercoup,
            'positions' => $positions,
            'idouverture' => $idouverture,
            'lettres' => $lettres,
            'nomvariante' => $nomvariante));
    }

}