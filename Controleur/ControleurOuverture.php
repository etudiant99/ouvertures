<?php
require_once 'Modele/Ouvertures.php';
class ControleurOuverture extends Controleur
{
    public function __construct() {
        $this->ouvertures = new Ouvertures();
    }

    public function ouverture()
    {
        header('Location: ./');
    }
}