<?php
require_once 'Modele/Echiquier.php';

class ControleurEchiquier extends Controleur
{
    private $ouvertures;
    private $echiquier;
    
    public function __construct() {
        $this->ouvertures = new Ouvertures;
        $this->echiquier = new Echiquier;
    }

    public function echiquier()
    {
        $idouverture = $this->requete->getParametre("id");
        $move = $this->requete->getParametre("move");
        $choix = $this->requete->getParametre("t");
        $flip = $this->requete->getParametre("f");
        if ($choix == '')
            $choix = 0;
        
        $ouverture = $this->ouvertures->get($idouverture);
        $nomouverture = $ouverture->getOuverture();
        $type = $ouverture->getType();
        
        switch ($move) {
            case 'debut':
                $couleur = 1;
                $ignouverture = $ouverture->getIgn();
                $coups = explode(" ", $ignouverture);
                $ignouverture = '';
                $totalcoups = 0;
                $this->echiquier->positionarbitraire($ouverture->getIgn(),$totalcoups);
                $mangeaille = $this->echiquier->getMangeaille();
                $position = $this->echiquier->positionactuelle($ignouverture);
                $lastmove = $this->echiquier->Lastmove();
                break;
            case 'precedent':
                $couleur = 1;
                $ignouverture = $ouverture->getIgn();
                $coups = explode(" ", $ignouverture);
                $totalcoups = sizeof($coups);
                $choix--;
                if ($choix <= 0)
                    $choix = 0;
                $totalcoups = $choix;
                $this->echiquier->positionarbitraire($ignouverture,$choix);
                $mangeaille = $this->echiquier->getMangeaille();
                $position = $this->echiquier->position();
                $lastmove = $this->echiquier->Lastmove();
                break;
            case 'suivant':
                $couleur = 1;
                $ignouverture = $ouverture->getIgn();
                $coups = explode(" ", $ignouverture);
                $totalcoups = sizeof($coups);
                $choix++;
                if ($choix >= $totalcoups)
                    $choix = $totalcoups;
                else
                    $totalcoups = $choix;
                $this->echiquier->positionarbitraire($ignouverture,$choix);
                $mangeaille = $this->echiquier->getMangeaille();
                $position = $this->echiquier->position();
                $lastmove = $this->echiquier->Lastmove();
                break;
            case 'fin':
                $couleur = 1;
                $ignouverture = $ouverture->getIgn();
                $coups = explode(" ", $ignouverture);
                $totalcoups = sizeof($coups);
                $this->echiquier->positionarbitraire($ignouverture,$totalcoups);
                $mangeaille = $this->echiquier->getMangeaille();
                $position = $this->echiquier->position();
                $lastmove = $this->echiquier->Lastmove();
                break;
            case 'tourner':
                $couleur = 1;
                $ignouverture = $ouverture->getIgn();
                $coups = explode(" ", $ignouverture);
                $totalcoups = sizeof($coups);
                if($flip == 0)
                    $flip = 1;
                else
                    $flip = 0;
                if ($choix <= 0)
                    $choix = 0;
                if ($choix >= $totalcoups)
                    $choix = $totalcoups;
                $totalcoups = $choix;
                $this->echiquier->positionarbitraire($ignouverture,$totalcoups);
                $mangeaille = $this->echiquier->getMangeaille();
                $position = $this->echiquier->position();
                $lastmove = $this->echiquier->Lastmove();
                break;
            default:
                $couleur = 1;
                $ignouverture = $ouverture->getIgn();
                $coups = explode(" ", $ignouverture);
                $totalcoups = sizeof($coups);
                $this->echiquier->positionarbitraire($ignouverture,$totalcoups);
                $mangeaille = $this->echiquier->getMangeaille();
                $position = $this->echiquier->position();
                $lastmove = $this->echiquier->Lastmove();
        }

        
        $this->genererVue(array(
            'couleur' => $couleur,
            'position' => $position,
            'ignouverture' => $ignouverture,
            'mangeaille' => $mangeaille,
            'lastmove' => $lastmove,
            'idouverture' => $idouverture,
            'nomouverture' => $nomouverture,
            'type' => $type,
            'flip' => $flip,
            'totalcoups' => $totalcoups,
            'coups' => $coups));
    }

}