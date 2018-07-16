<?php
class Type
{
    private $_id;
    private $_type;
    private $_ouvertures;
    
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

    public function setId($id)
    {
        $this->_id = $id;
    }
    public function getId()
    {
        return $this->_id;
    }

    public function setType($id)
    {
        $this->_type = $id;
    }
    public function getType()
    {
        return $this->_type;
    }

    public function setOuvertures($id)
    {
        $this->_ouvertures = $id;
    }
    public function getOuvertures()
    {
        return $this->_ouvertures;
    }

}
