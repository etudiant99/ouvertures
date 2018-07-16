<?php

class ControleurCoupvariante extends Controleur
{
    private $ouvertures;
    private $echiquier;
    
    public function __construct() {
        $this->variantes = new Variantes;
        $this->echiquier = new Echiquier;
    }
    
    public function coupvariante()
    {
        $idvariante = $this->requete->getParametre("id");
        $move = $this->requete->getParametre("move");
        $choix = $this->requete->getParametre("t");
        $flip = $this->requete->getParametre("f");
        if ($choix == '')
            $choix = 0;
        
        $variante = $this->variantes->getVariante($idvariante);
        $nomouverture = $variante->getOuverture();
        $nomvariante = $variante->getVariante();
        $type = $variante->getType();
        
        switch ($move) {
            case 'debut':
                $couleur = 1;
                $ignvariante = $variante->getIgn();
                $coups = explode(" ", $ignvariante);
                $ignvariante = '';
                $totalcoups = 0;
                $this->echiquier->positionarbitraire($variante->getIgn(),$totalcoups);
                $mangeaille = $this->echiquier->getMangeaille();
                $position = $this->echiquier->positionactuelle($ignvariante);
                $lastmove = $this->echiquier->Lastmove();
                break;
            case 'precedent':
                $couleur = 1;
                $ignvariante = $variante->getIgn();
                $coups = explode(" ", $ignvariante);
                $totalcoups = sizeof($coups);
                $choix--;
                if ($choix <= 0)
                    $choix = 0;
                $totalcoups = $choix;
                $this->echiquier->positionarbitraire($ignvariante,$choix);
                $mangeaille = $this->echiquier->getMangeaille();
                $position = $this->echiquier->position();
                $lastmove = $this->echiquier->Lastmove();
                break;
            case 'suivant':
                $couleur = 1;
                $ignvariante = $variante->getIgn();
                $coups = explode(" ", $ignvariante);
                $totalcoups = sizeof($coups);
                $choix++;
                if ($choix >= $totalcoups)
                    $choix = $totalcoups;
                else
                    $totalcoups = $choix;
                $this->echiquier->positionarbitraire($ignvariante,$choix);
                $mangeaille = $this->echiquier->getMangeaille();
                $position = $this->echiquier->position();
                $lastmove = $this->echiquier->Lastmove();
                break;
            case 'fin':
                $couleur = 1;
                $ignvariante = $variante->getIgn();
                $coups = explode(" ", $ignvariante);
                $totalcoups = sizeof($coups);
                $this->echiquier->positionarbitraire($ignvariante,$totalcoups);
                $mangeaille = $this->echiquier->getMangeaille();
                $position = $this->echiquier->position();
                $lastmove = $this->echiquier->Lastmove();
                break;
            case 'tourner':
                $couleur = 1;
                $ignvariante = $variante->getIgn();
                $coups = explode(" ", $ignvariante);
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
                $this->echiquier->positionarbitraire($ignvariante,$totalcoups);
                $mangeaille = $this->echiquier->getMangeaille();
                $position = $this->echiquier->position();
                $lastmove = $this->echiquier->Lastmove();
                break;
            default:
                $couleur = 1;
                $ignvariante = $variante->getIgn();
                $coups = explode(" ", $ignvariante);
                $totalcoups = sizeof($coups);
                $this->echiquier->positionarbitraire($ignvariante,$totalcoups);
                $mangeaille = $this->echiquier->getMangeaille();
                $position = $this->echiquier->position();
                $lastmove = $this->echiquier->Lastmove();
        }

        $this->genererVue(array(
            'couleur' => $couleur,
            'position' => $position,
            'nomouverture' => $nomouverture,
            'nomvariante' => $nomvariante,
            'type' => $type,
            'mangeaille' => $mangeaille,
            'flip' => $flip,
            'totalcoups' => $totalcoups,
            'coups' => $coups));
    }
}