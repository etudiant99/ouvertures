<?php
class Varianteproposee
{
    private $_trait = 1;
    private $_temp;
    
    public function __construct(array $donnees)
    {
        //var_dump($donnees);
        $this->hydrate($donnees);
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

    public function setTemp($id)
    {
        $monign = '';
        if (sizeof($id) == 0)
            $this->_trait = 1;
        if (sizeof($id) % 2 == 1)
            $this->_trait = -1;
        if (sizeof($id) % 2 == 0)
            $this->_trait = 1;
        
        foreach($id as $item)
        {
            $monign .= $item.' ';
        }
        $monign = substr($monign, 0, -1);
        
        $this->_temp = $monign;
    }
    public function getTemp()
    {
        return $this->_temp;
    }

    public function getTrait()
    {
        return $this->_trait;
    }
}