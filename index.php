<?php
function chargerClasse($classname)
{
    require 'modele/'.$classname.'.php';
}
spl_autoload_register('chargerClasse');

require 'Framework/Routeur.php';

$routeur = new Routeur();
$routeur->routerRequete();