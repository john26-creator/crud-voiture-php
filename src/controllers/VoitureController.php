<?php
namespace App\controllers;

use App\models\VoitureDao;

class VoitureController {

    private $voitureDAO;

    function __construct() { $this->voitureDAO = new VoitureDao;}

    public function afficherVoitures()
    {
        $voitures = $this->voitureDAO->findAll();
        $result = [];
        foreach ($voitures as $voiture) {
            array_push($result, ["id" => $voiture->getId(),
                                 "couleur" => $voiture->getCouleur(),
                                 "marque" => $voiture->getMarque(),
                                 "modele" => $voiture->getModele(),
                                 "immat" => $voiture->getImmat() ]);
        }
        
        return ["status" => "OK", "result" => $result];
    }

    public function deleteVoiture ($id) {
        $isDeleted = ($this->voitureDAO->deleteVoiture($id))? "OK" : "KO";
        return ["status" => $isDeleted];
    }

    public function modifierVoiture ($id) {
        $voiture = $this->voitureDAO->findById($id);
        
        switch ($_POST["name"]) {
            case "immat" : $voiture->setImmat($_POST["value"]); break;
            case "couleur" : $voiture->setCouleur($_POST["value"]); break;
            case "marque" : $voiture->setMarque($_POST["value"]); break;
            case "modele" : $voiture->setModele($_POST["value"]); break;
        }
        $isModified = ($this->voitureDAO->updateVoiture($voiture))? "OK" : "KO";
        return ["status" => $isModified];
    }

    public function ajouterVoiture ($datas) {
        $id = $this->voitureDAO->insertVoiture($datas["Immatriculation"], $datas["Couleur"], $datas["Marque"], $datas["Modele"]);
        return ["status" => ($id)? "OK" : "KO", 
                "result" => ["id" => $id,
                             "immat" => $datas["Immatriculation"],
                             "couleur" => $datas["Couleur"],
                             "marque" => $datas["Marque"],
                             "modele" => $datas["Modele"]]];
    }
    
}