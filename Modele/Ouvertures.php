<?php
require_once 'Framework/Modele.php';
class Ouvertures extends Modele
{
    public function get($idOuverture)
    {
        $sql = 'select o.id,ouverture,type from ouvertures o inner join types t on o.id_type=t.id where o.id=?';
        $q = $this->executerRequete($sql, array($idOuverture));
        $donnees = $q->fetch(PDO::FETCH_ASSOC);
        $donnees['listecoups'] = $this->getCoups($idOuverture);
        $ouverture = new Ouverture($donnees);
        
        return $ouverture;
    }
    
    public function getType($idType)
    {
        $type = null;
        $donnees = array();
        $sql = 'select * from types where id=? order by id';
        $q = $this->executerRequete($sql, array($idType));
        $donnees = $q->fetch(PDO::FETCH_ASSOC);
        $donnees['ouvertures'] = $this->getOuvertures($idType);
        $type = new Type($donnees);
        
        return $type;
    }
    
    public function getTypes()
    {
        $types = array();
        $sql = 'select * from types order by id';
        $q = $this->executerRequete($sql);
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $types[] = new Type($donnees);
        }
        
        return $types;
    }
    
    public function getCoups($idOuverture)
    {
        $liste = array();
        $sql = 'select coup from coups_ouverture c inner join lescoups l on c.id_coup=l.id where c.id_ouverture=?';
        $q = $this->executerRequete($sql, array($idOuverture));
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $liste[] = $donnees;
        }

        return $liste;
    }
    
    public function getOuverture($idVariante)
    {
        $sql = 'SELECT o.ouverture FROM ouverture_variantes ov inner join ouvertures o where ov.id_ouverture=o.id and id_variante =?';
        $q = $this->executerRequete($sql, array($idVariante));
        $donnees = $q->fetch(PDO::FETCH_ASSOC);
        
        return $donnees['ouverture'];
    }
    
    public function getOuvertures($idOuverture)
    {
        $liste = array();
        $sql = 'SELECT id,ouverture FROM ouvertures where id_type =? order by ouverture';
        $q = $this->executerRequete($sql, array($idOuverture));
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $liste[] = $donnees;
        }
        
        return $liste;
    }
    
    public function getToutesOuvertures()
    {
        $liste = array();
        $sql = 'SELECT id,ouverture FROM ouvertures order by ouverture';
        $q = $this->executerRequete($sql);
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $liste[] = $donnees;
        }
        
        return $liste;
    }
    
    public function OuvertureTemp($coup)
    {
        $sql = 'insert into ouvertureproposee (lecoup) values(?)';
        $q = $this->executerRequete($sql, array($coup));
    }
    
    public function lecturetable()
    {
        $liste = array();
        $sql = 'select lecoup from ouvertureproposee';
        $q = $this->executerRequete($sql);
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $liste['temp'][] = $donnees['lecoup'];
        }
        $resultat = new Ouvertureproposee($liste);
        
        return $resultat;
    }

    public function effacer()
    {
        $sql = 'truncate table ouvertureproposee';
        $q = $this->executerRequete($sql);
    }
    
    public function effacerOuverture($idOuverture,$idType)
    {
        $sql = 'select * from ouverture_variantes where id_ouverture=?';
        $q = $this->executerRequete($sql, array($idOuverture));
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $idVariante = $donnees['id_variante'];
            $sql = 'delete from coups_variante where id_variante=?';
            $this->executerRequete($sql, array($idVariante));
            $sql2 = 'delete from variantes where id=?';
            $this->executerRequete($sql2, array($idVariante));
        }
        $sql = 'delete from coups_ouverture where id_ouverture=?';
        $q = $this->executerRequete($sql, array($idOuverture));
        $sql = 'delete from ouvertures where id=?';
        $q = $this->executerRequete($sql, array($idOuverture));
        
        header('Location: /ouvertures/?action=ouvertures&type='.$idType);
    }

    public function effacerOuvertureTemp()
    {
        $sql = 'truncate table ouvertureproposee';
        $q = $this->executerRequete($sql);
    }

    public function enregistrerOuverture($idType,$nomouverture)
    {
        $echiquier = new Echiquier;
        $resultat = $this->lecturetable();
        $ign = $resultat->getTemp();
        $coups = explode(" ",$ign);
        $totalcoups = sizeof($coups);
        $echiquier->positionarbitraire($ign,$totalcoups);
        $touslescoups = $echiquier->getTouslescoups();
        for ($i=0;$i<sizeof($touslescoups);$i++)
        {
            if ($i% 2 == 0)
                $blancs[] = $touslescoups[$i];
            if ($i% 2 == 1)
                $noirs[] = $touslescoups[$i];
        }
        for ($i=0;$i<sizeof($blancs);$i++)
        {
            if (isset($noirs[$i]))
                $lescoups[] = $blancs[$i].' '.$noirs[$i];
            else
                $lescoups[] = $blancs[$i];
        }
        $sql = 'insert into ouvertures (ouverture,id_type) values (?,?)';
        $q = $this->executerRequete($sql, array($nomouverture,$idType));
        $sql = "select max(id) as maximum from ouvertures order by id";
        $q = $this->executerRequete($sql);
        $donnees = $q->fetch(PDO::FETCH_ASSOC);
        $idOuverture = $donnees['maximum'];
        
        foreach ($lescoups as $coup)
        {
            $sql = 'select * from lescoups where coup=?';
            $q = $this->executerRequete($sql, array($coup));
            $donnees = $q->fetch(PDO::FETCH_ASSOC);
            if ($q->rowCount() > 0)
            {
                $idCoup = $donnees['id'];
                $sql = 'insert into coups_ouverture (id_ouverture,id_coup) values(?,?)';
                $q = $this->executerRequete($sql, array($idOuverture,$idCoup));
            }
            else
            {
                $sql = 'insert into lescoups (coup) values(?)';
                $q = $this->executerRequete($sql, array($coup));
                $sql = "select max(id) as maximum from lescoups order by id";
                $q = $this->executerRequete($sql);
                $donnees = $q->fetch(PDO::FETCH_ASSOC);
                
                $sql = 'insert into coups_ouverture (id_ouverture,id_coup) values(?,?)';
                $q = $this->executerRequete($sql, array($idOuverture,$donnees['maximum']));
            }
        }
        $sql = 'truncate table ouvertureproposee';
        $q = $this->executerRequete($sql);
    }

}