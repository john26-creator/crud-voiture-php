<?php

namespace App;
require_once '../../vendor/autoload.php';
use App\controllers\VoitureController;

$result = "";
$controller = new VoitureController();
//var_dump($_POST);
header('Content-type:application/json;charset=utf-8');

if (isset($_GET["function"]) && ! empty($_GET["function"])) {
    switch ($_GET["function"]) {
        case "afficherVoitures" : $result =  $controller->afficherVoitures();break;
        case "deleteVoiture" : $result =  $controller->deleteVoiture($_GET["id"]);break;
        case "modifierVoiture" : 
            $_POST = json_decode($_POST['json'],true);
            $result = $controller->modifierVoiture($_POST["id"]);
            break;
        case "ajouterVoiture" : 
            $result =  $controller->ajouterVoiture($_POST);
            break;
    }
}

function utf8ize($d) {
    if (is_array($d) || is_object($d)) {
        foreach ($d as &$v) $v = utf8ize($v);
    } else {
        $enc   = mb_detect_encoding($d);

        $value = iconv($enc, 'UTF-8', $d);
        return $value;
    }

    return $d;
}

echo json_encode($result);