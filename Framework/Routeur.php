<?php
require_once 'Controleur.php';
require_once 'Requete.php';
require_once 'Vue.php';

class Routeur {

    public function routerRequete() {
        try {
            $requete = new Requete(array_merge($_GET, $_POST));
            $controleur = $this->creerControleur($requete);//par défaut $controleur=ControleurAccueil ou (ControleurBillet)
            $action = $this->creerAction($requete);
            $controleur->executerAction($action); //par défaut $action = 'index'
        }
        catch (Exception $e) {
            $this->gererErreur($e);
        }
    }

    private function creerControleur(Requete $requete) {
        $controleur = "Accueil";  // Contrôleur par défaut
        if ($requete->existeParametre('action')) {
            $controleur = $requete->getParametre('action');
            $controleur = ucfirst(strtolower($controleur));
        }
        
        $classeControleur = "Controleur" . $controleur;
        $fichierControleur = "Controleur/" . $classeControleur . ".php";
        if (file_exists($fichierControleur)) {
            require($fichierControleur);
            $controleur = new $classeControleur();
            $controleur->setRequete($requete);
            return $controleur;
        }
        else {
            throw new Exception("Fichier '$fichierControleur' introuvable");
        }
    }

    private function creerAction(Requete $requete) {
        $action = "index";  // Action par défaut
        if ($requete->existeParametre('action')) {
            $action = $requete->getParametre('action');
        }

        return $action;
    }

    private function gererErreur(Exception $exception) {
        $vue = new Vue('erreur');
        $vue->generer(array('msgErreur' => $exception->getMessage()));
    }
}