<?php
class Echiquier extends Piece
{
    protected $_position = array();
    protected $_pieces = array('piece' => array(), 'couleur' => array(), 'endroit' => array());
    protected $_nombrepiecesblanches;
    protected $_nombrepiecesnoires;
    protected $_nbcases;
    protected $_contenucase;
    protected $_trait;
    protected $_bascule;
    protected $_lastmove;
    private $_positiondepart;
    protected $_promotion;
    private $_situationpriseenpassant;
    private $_mangeaille;
    private $_touslescoups;
    
    public function __construct()
    {
        $this->positiondepart();
    }

    public function getMangeaille()
    {
        return $this->_mangeaille;
    }
    
    public function positionactuelle($ign)
    {
        $wCastleLong = true;
        $wCastleShort = true;
        $bCastleLong = true;
        $bCastleShort = true;

        $mangeaille = array('blancs' => array(),'noirs' => array());
        $position = $this->position();
        $promotion = '';
        $coups = explode(" ",$ign);

        if ($ign == "")
            return $position;
        else
            $qtecoups = count($coups);
        
        for ($i=0;$i<$qtecoups;$i++)
        {
            $start = substr($coups[$i],0,2);
            $end = substr($coups[$i],2,2);
            if (strlen($coups[$i]) > 4)
                $promotion = substr($coups[$i],4,1);  

            // les coups sous formes de chiffres, par exemple (12-28)
            $istart = $this->moveToCell($start);
            $iend = $this->moveToCell($end);
            
            $lastmove = $istart.'-'.$iend;
            
            // Interdiction ou pas du roque
            if($position[$istart] == 'K')
            {
                $wCastleLong = false;
                $wCastleShort = false;
            }
            if($position[$istart] == 'k')
            {
                $bCastleLong = false;
                $bCastleShort = false;
            }
            
            // Si la tour bouge
            if($istart == 0)
                $wCastleLong = false;
            else if($istart == 7)
                $wCastleShort = false;
            else if($istart == 56)
                $bCastleLong = false;
            else if($istart == 63)
                $bCastleShort = false;
            
            // Si la tour disparait en se faisant manger    
            if($iend == 0)
                $wCastleLong = false;
            else if($iend == 7)
                $wCastleShort = false;
            else if($iend == 56)
                $bCastleLong = false;
            else if($iend == 63)
                $bCastleShort = false;
                
            // Prise en passant pour les blancs
            if ($iend == $istart+7 && $position[$istart+7] == '' && $position[$istart] == 'P')
            {
                $position[$iend] = $position[$iend-8];
                $position[$iend-8] = '';
            }

            if ($iend == $istart+9 && $position[$istart+9] == '' && $position[$istart] == 'P')
            {
                $position[$iend] = $position[$iend-8];
                $position[$iend-8] = '';
            }

            // Prise en passant pour les noirs
            if ($iend == $istart-7 && $position[$istart-7] == '' && $position[$istart] == 'p')
            {
                $position[$iend] = $position[$iend+8];
                $position[$iend+8] = '';
            }

            if ($iend == $istart-9 && $position[$istart-9] == '' && $position[$istart] == 'p')
            {
                $position[$iend] = $position[$iend+8];
                $position[$iend+8] = '';
            }
        
            // Pieces mangees
            if ($position[$iend] != '')
            {
                if ($position[$iend] == strtoupper($position[$iend]))
                    $mangeaille['blancs'][] = $this->imagepiecemangee($position[$iend]);
                else
                    $mangeaille['noirs'][] = $this->imagepiecemangee($position[$iend]);
            }

            $position[$iend] = $position[$istart];
            $position[$istart] = '';

            // roque
            if($istart == 4 && $iend == 6 && $position[$iend] == 'K')
            {
                $position[5] = 'R';
                $position[7] = '';
            }
            else if($istart == 4 && $iend == 2 && $position[$iend] == 'K')
            {
                $position[3] = 'R';
                $position[0] = '';
            }
            else if($istart == 60 && $iend == 62 && $position[$iend] == 'k')
            {
                $position[61] = 'r';
                $position[63] = '';
            }
            else if($istart == 60 && $iend == 58 && $position[$iend] == 'k')
            {
                $position[59] = 'r';
                $position[56] = '';
            }
            // promotion
            if ($iend < 8 && $position[$iend] == 'p')
                $position[$iend] = $promotion;
            if ($iend > 55 && $position[$iend] == 'P')
                $position[$iend] = $promotion; 
        }
        $castling = '';
        if($wCastleShort) $castling .= 'K';
        if($wCastleLong) $castling .= 'Q';
        if($bCastleShort) $castling .= 'k';
        if($bCastleLong) $castling .= 'q';
        if($castling == '') $castling = '-';
        
        $this->_castling = $castling;  

        $this->_mangeaille = $mangeaille;
        $this->_lastmove = $lastmove;
        
        for ($i=0;$i<64;$i++)
        {
            $this->_position[$i] = $position[$i];
        }
        
        $this->lesPieces($this->_position,$piece = -1);
        $this->_positionroiblanc = $this->lesPieces($this->_position,'K');
        $this->_positionroinoir = $this->lesPieces($this->_position,'k');
        /*
        if ($this->getTrait() == 1)
            $this->_roiattaque = $this->caseAttaquee($this->_position,$this->_positionroiblanc,'b');
        else
            $this->_roiattaque = $this->caseAttaquee($this->_position,$this->_positionroinoir,'w');
        */
        
        return $this->_position;
    }

    public function getPositionDepart()
    {
        return $this->_position;
    }

    public function positiondepart()
    {            
        $this->_lastmove = '';
        $position = array();
        
        $position[0] = 'R';
        $position[1] = 'N';
        $position[2] = 'B';
        $position[3] = 'Q';
        $position[4] = 'K';
        $position[5] = 'B';
        $position[6] = 'N';
        $position[7] = 'R';
        
        for ($i=8;$i<16;$i++) $position[$i]  = 'P';
        for ($i=16;$i<48;$i++) $position[$i] = '';
        for ($i=48;$i<56;$i++) $position[$i] = 'p';
        
        $position[56] = 'r';
        $position[57] = 'n';
        $position[58] = 'b';
        $position[59] = 'q';
        $position[60] = 'k';
        $position[61] = 'b';
        $position[62] = 'n';
        $position[63] = 'r';

        for ($i=0;$i<64;$i++)
        {
            $this->_position[$i] = $position[$i];
        }
        $this->_trait = 1;
        
        return $this->_position;
    }

    public function piecesEchiquier()
    {   
        $pieces = array('1' => array(),'0' => array());
        $endroit = array();
        $couleur = array();
        $retour = array();
        $position = $this->_position;
        
        for ($i=0;$i<64;$i++)
        {
            if ($position[$i] != '')
            {
                if ($position[$i] == strtoupper($position[$i]))
                    $pieces['1'][$i] = $position[$i];
                else
                    $pieces['0'][$i] = $position[$i];
            }
        }
        $this->_nombrepiecesblanches = count($pieces['1']);
        $this->_nombrepiecesnoires = count($pieces['0']);
    }

    public function position()
    {
        return $this->_position;
    }
    
    private function contenuCase($indice)
    {
        $position = $this->position();
        return $position[$indice];
    }
    
    public function getSituationPriseEnPassant()
    {
        return $this->_situationpriseenpassant;
    }
    
    public function NombrePiecesBlanches()
    {
        return $this->_nombrepiecesblanches;
    }
    
    public function NombrePiecesNoires()
    {
        return $this->NombrePiecesNoires();
    }
        
    public function dateenlettres($date)
    {
        switch(substr($date,5,2))
        {
            case '01':
                $mois = 'janvier';
                break;
            case '02':
                $mois = 'février';
                break;
            case '03':
                $mois = 'mars';
                break;
            case '04':
                $mois = 'avril';
                break;
            case '05':
                $mois = 'mai';
                break;
            case '06':
                $mois = 'juin';
                break;
            case '07':
                $mois = 'juillet';
                break;
            case '08':
                $mois = 'août';
                break;
            case '09':
                $mois = 'septembre';
                break;
            case '10':
                $mois = 'octobre';
                break;
            case '11':
                $mois = 'novembre';
                break;
            case '12':
                $mois = 'décembre';
                break;
            default:
                $mois = '';
        }
        
        $dateformatee = substr($date,8,2).' '.$mois;
        
        return $dateformatee;
    }

    public function moveToText($move)
    {
        $sortie = '';
        $ligne = $this->parseInt($move/8);
	    $colonne = $move - $ligne*8;
        
        if($colonne == 0) $sortie .= 'a';
        else if($colonne == 1) $sortie .= 'b';
        else if($colonne == 2) $sortie .= 'c';
        else if($colonne == 3) $sortie .= 'd';
        else if($colonne == 4) $sortie .= 'e';
        else if($colonne == 5) $sortie .= 'f';
        else if($colonne == 6) $sortie .= 'g';
        else if($colonne == 7) $sortie .= 'h';
        
        $ligne ++;
        $sortie .= $ligne;
        
        return $sortie;
    }
    
    public function formate_date($date)
    {
        $date_formatee = substr($date,8,2)."/".substr($date,5,2)."/".substr($date,0,4);
    
        if ($date == '')
            return '';
        else
            return $date_formatee;
    }
    
    public function getPromotion()
    {
        return $this->_promotion;
    }

    public function Lastmove()
    {
        return $this->_lastmove;
    }
    
    public function Montrait()
    {
        return $this->_trait;
    }
    
    public function setPromotion($id)
    {
        $this->_promotion = $id;
    }

    public function parseInt($string)
    {
        if(preg_match('/(\d+)/', $string, $array))
            return $array[1];
        else
            return 0;
    }

    private function moveToCell($move)
    {
        $a = substr($move,0,1);
        $b = substr($move,1,1);
        $colonne = 0;

        switch($a)
        {
            case "a":
                $colonne = 0;
                break;
            case "b":
                $colonne = 1;
                break;
            case "c":
                $colonne = 2;
                break;
            case "d":
                $colonne = 3;
                break;
            case "e":
                $colonne = 4;
                break;
            case "f":
                $colonne = 5;
                break;
            case "g":
                $colonne = 6;
                break;
            case "h":
                $colonne = 7;
                break;

        }

	   return($colonne+8*(intval($b)-1));
    }        

    public function positionarbitraire($ign,$qtecoups)
    {
        $lasuite = array();
        $mangeaille = array('blancs' => array(),'noirs' => array());
        $this->positiondepart();
        $position = $this->getPositionDepart();
        $promotion = '';
        
        $coups = explode(" ",$ign);

        if ($ign == "")
            return $position;
        
        for ($i=0;$i<$qtecoups;$i++)
        {
            if (strlen($coups[$i]) == 4 || strlen($coups[$i]) == 5)
            {
                $start = substr($coups[$i],0,2);
                $end = substr($coups[$i],2,2);
            }

            if (strlen($coups[$i]) > 4)
                $promotion = substr($coups[$i],4,1);  

            // les coups sous formes de chiffres, par exemple (12-28)
            $istart = $this->moveToCell($start);
            $iend = $this->moveToCell($end);
            
            $lastmove = $istart.'-'.$iend;
            
            // Prise en passant pour les blancs
            if ($iend == $istart+7 && $position[$istart+7] == '' && $position[$istart] == 'P')
            {
                $position[$iend] = $position[$iend-8];
                $position[$iend-8] = '';
            }

            if ($iend == $istart+9 && $position[$istart+9] == '' && $position[$istart] == 'P')
            {
                $position[$iend] = $position[$iend-8];
                $position[$iend-8] = '';
            }

            // Prise en passant pour les noirs
            if ($iend == $istart-7 && $position[$istart-7] == '' && $position[$istart] == 'p')
            {
                $position[$iend] = $position[$iend+8];
                $position[$iend+8] = '';
            }

            if ($iend == $istart-9 && $position[$istart-9] == '' && $position[$istart] == 'p')
            {
                $position[$iend] = $position[$iend+8];
                $position[$iend+8] = '';
            }

            // Pieces mangees
            if ($position[$iend] != '')
            {
                if ($position[$iend] == strtoupper($position[$iend]))
                    $mangeaille['blancs'][] = $this->imagepiecemangee($position[$iend]);
                else
                    $mangeaille['noirs'][] = $this->imagepiecemangee($position[$iend]);
            }
            
            switch ($position[$istart])
            {
                case 'R':
                case 'r':
                    $piece = 'T';
                    break;
                case 'N':
                case 'n':
                    $piece = 'C';
                    break;
                case 'B':
                case 'b':
                    $piece = 'F';
                    break;
                case 'Q':
                case 'q':
                    $piece = 'D';
                    break;
                case 'K':
                case 'k':
                    $piece = 'R';
                    break;
                default:
                    $piece = '';
            }
            
            switch ($piece.$coups[$i])
            {
                case 'Re1g1':
                case 'Re8g8':
                    $lasuite[] = '0-0';
                    break;
                case 'e1c1':
                case 'e8c8':
                   $lasuite[] = '0-0-0';
                   break;
                default:
                    if ($position[$iend] != '')
                        $lasuite[] = $piece.substr($coups[$i], 0, 2).'x'.substr($coups[$i], 2, 2);
                    else
                        $lasuite[] = $piece.substr($coups[$i], 0, 2).'-'.substr($coups[$i], 2, 2);
            }
            
            $position[$iend] = $position[$istart];
            $position[$istart] = '';

            // roque
            if($istart == 4 && $iend == 6 && $position[$iend] == 'K')
            {
                $position[5] = 'R';
                $position[7] = '';
            }
            else if($istart == 4 && $iend == 2 && $position[$iend] == 'K')
            {
                $position[3] = 'R';
                $position[0] = '';
            }
            else if($istart == 60 && $iend == 62 && $position[$iend] == 'k')
            {
                $position[61] = 'r';
                $position[63] = '';
            }
            else if($istart == 60 && $iend == 58 && $position[$iend] == 'k')
            {
                $position[59] = 'r';
                $position[56] = '';
            }
            // promotion
            if ($iend < 8 && $position[$iend] == 'p')
                $position[$iend] = $promotion;
            if ($iend > 55 && $position[$iend] == 'P')
                $position[$iend] = $promotion;      
        }
        
        $this->_mangeaille = $mangeaille;
        if (isset($lastmove))
            $this->_lastmove = $lastmove;
        
        for ($i=0;$i<64;$i++)
        {
            $this->_position[$i] = $position[$i];
        }
        $this->_touslescoups = $lasuite;
    }

    public function getTouslescoups()
    {
        return $this->_touslescoups;
    }
}