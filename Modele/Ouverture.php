<?php
class Ouverture
{
    private $_id;
    private $_id_variable;
    private $_ouverture;
    private $_type;
    private $_variante;
    private $_listecoups;
    private $_ign = '';
    
    public function __construct(array $donnees)
    {
        //var_dump($donnees);
        $this->hydrate($donnees);
        $this->setIgn();
    }
    
    private function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value)
        {
            $method = 'set'.ucfirst($key);
            
            if (method_exists($this, $method))
                $this->$method($value);
        }
    }

    public function setId($id)
    {
        $this->_id = $id;
    }
    public function getId()
    {
        return $this->_id;
    }

    public function setId_variable($id)
    {
        $this->_id_variable = $id;
    }
    public function getId_variable()
    {
        return $this->_id_variable;
    }

    public function setType($id)
    {
        $this->_type = $id;
    }
    public function getType()
    {
        return $this->_type;
    }

    public function setOuverture($id)
    {
        $this->_ouverture = $id;
    }
    public function getOuverture()
    {
        return $this->_ouverture;
    }

    public function setVariante($id)
    {
        $this->_variante = $id;
    }
    public function getVariante()
    {
        return $this->_variante;
    }
    
    public function setIgn()
    {
        $monign = '';
        $coups = $this->getListecoups();
        if (sizeof($coups) == 0)
            return;
                   
        foreach($coups as $item)
        { 
            $coupEntier = explode(" ",$item);
            for ($i=0;$i<sizeof($coupEntier);$i++)
            {
                if ($i % 2 == 1 and strlen($coupEntier[$i]) == 3)
                    $coupEntier[$i] = str_replace('0-0', 'e8g8', $coupEntier[$i]);
                else if ($i % 2 == 0 and strlen($coupEntier[$i]) == 3)
                    $coupEntier[$i] = str_replace('0-0', 'e1g1', $coupEntier[$i]);
                if ($i % 2 == 1 and strlen($coupEntier[$i]) == 5)
                    $coupEntier[$i] = str_replace('0-0-0', 'e8c8', $coupEntier[$i]);
                else if ($i % 2 == 0 and strlen($coupEntier[$i]) == 5)
                    $coupEntier[$i] = str_replace('0-0-0', 'e1c1', $coupEntier[$i]);
                    
                $coupEntier[$i] = str_replace('-', '', $coupEntier[$i]);
                $coupEntier[$i] = str_replace('x', '', $coupEntier[$i]);
                $coupEntier[$i] = str_replace('T', '', $coupEntier[$i]);
                $coupEntier[$i] = str_replace('C', '', $coupEntier[$i]);
                $coupEntier[$i] = str_replace('F', '', $coupEntier[$i]);
                $coupEntier[$i] = str_replace('D', '', $coupEntier[$i]);
                $coupEntier[$i] = str_replace('R', '', $coupEntier[$i]);
                $monign .= $coupEntier[$i].' ';
            }
        }
        $monign = substr($monign, 0, -1);
        $this->_ign = $monign;
    }

    public function getIgn()
    {
        return $this->_ign;
    }

    public function setListecoups($id)
    {
        foreach($id as $item)
        {
            $liste[] = $item['coup'];
        }
        $this->_listecoups = $liste;
    }
    public function getListecoups()
    {
        return $this->_listecoups;
    }

    public function setCoupsvariantes($id)
    {
        $this->_coupsvariantes = $id;
    }
    public function getCoupsvariantes()
    {
        return $this->_coupsvariantes;
    }

}