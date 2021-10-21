<?php

use App\models\VoitureDao;

require_once realpath('vendor/autoload.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="scripts/voiture.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <title>Magasin voitures</title>
</head>

<body>
    <h1>Magasin de voitures</h1>
    <div id="content"></div>
    <br><br>
    <form class="form" style="width:50%; margin:auto" onsubmit="ajouterVoiture(event)">
       
        <input type="text" class="form-control mb-2 mr-sm-2" name="Immatriculation" placeholder="LL-NNN-LL">
        <input type="text" class="form-control mb-2 mr-sm-2" name="Couleur" placeholder="Couleur">
        <input type="text" class="form-control mb-2 mr-sm-2" name="Marque" placeholder="Marque">
        <input type="text" class="form-control mb-2 mr-sm-2" name="Modele" placeholder="Modele">

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <span class="alert alert-danger" style="display:none">Une erreur est survenue</span>
    <span class="alert alert-success" style="display:none">Base de données à jour</span>

    <?php
    $voiture = new VoitureDao();
    ?>
</body>

</html>