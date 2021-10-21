<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <!-- <script src="scripts/App.js"></script> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <title>Magasin voitures</title>
</head>

<body>
    <h1>Magasin de voitures</h1>
    <div id="content">
        <table class="table table-dark table-sm" style="width:90%;margin:auto;padding-top:20px;">
            <thead>
                <th>immat</th>
                <th>couleur</th>
                <th>modele</th>
                <th>marque</th>
                <th>supprimer</th>
            </thead>
            <tbody>
                <tr v-for="car in cars" :key="car.id" :id="car.id">
                    <td><input type="text" name="immat" :value="car.immat" @change="updateVoiture"></td>
                    <td><input type="text" name="couleur" :value="car.couleur" @change="updateVoiture"></td>
                    <td><input type="text" name="marque" :value="car.marque" @change="updateVoiture"></td>
                    <td><input type="text" name="modele" :value="car.modele" @change="updateVoiture"></td>
                    <td><button type="button" class="btn btn-danger" @click="deleteVoiture({car})">Supprimer</button></td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <form class="form" style="width:50%; margin:auto" @submit="ajouterVoiture">

            <input type="text" class="form-control mb-2 mr-sm-2" name="Immatriculation" placeholder="LL-NNN-LL">
            <input type="text" class="form-control mb-2 mr-sm-2" name="Couleur" placeholder="Couleur">
            <input type="text" class="form-control mb-2 mr-sm-2" name="Marque" placeholder="Marque">
            <input type="text" class="form-control mb-2 mr-sm-2" name="Modele" placeholder="Modele">

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <span class="alert alert-danger" style="display:none">Une erreur est survenue</span>
        <span class="alert alert-success" style="display:none">Base de données à jour</span>
    </div>

</body>
<script>
    var app = new Vue({
        el: '#content',
        data: {
            cars: []
        },
        methods: {
            loadCars: function() {
                fetch(`src/controllers/FrontController.php?function=afficherVoitures`)
                    .then(result =>
                        result.json())
                    .then(data => {
                        if (data.status === "OK") {
                            this.cars = data.result;
                        }
                    });
            },
            updateVoiture: function(event) {

                console.log(event.target.parentNode.parentNode);
                var payload = {
                    "name": event.target.name,
                    "value": event.target.value,
                    "id": event.target.parentNode.parentNode.id
                };

                var data = new FormData();
                data.append("json", JSON.stringify(payload));

                fetch(`src/controllers/FrontController.php?function=modifierVoiture`, { //Et si il est valide alors du fait une requete ajax
                        method: "POST", // En post
                        body: data //Avec le formulaire
                    })
                    .then(result => result.json())
                    .then(data => {
                        this.flashMessage(data.status === "OK");
                    });
            },
            deleteVoiture: function(car) {
                let currentCar = car.car;

                fetch(`src/controllers/FrontController.php?function=deleteVoiture&id=${currentCar.id}`)
                    .then(result => result.json())
                    .then(data => {
                        if (data.status === "OK") {
                            this.cars.splice(this.cars.indexOf(currentCar), 1)
                            this.flashMessage(true);
                        } else {
                            this.flashMessage(false);
                        }
                    });
            },
            ajouterVoiture: function(event) {
                event.preventDefault();
                console.log(this.cars);
                fetch(`src/controllers/FrontController.php?function=ajouterVoiture`, { //Et si il est valide alors du fait une requete ajax
                        method: "POST", // En post
                        body: new FormData(document.querySelector('form')) //Avec le formulaire
                    })
                    .then(result => result.json())
                    .then(data => {
                        if (data.status === "OK") {
                            //console.log(data.result);
                            this.cars.push({
                                couleur: data.result.couleur,
                                id: data.result.id,
                                immat: data.result.immat,
                                marque: data.result.marque,
                                modele: data.result.modele
                            })
                            this.flashMessage(true);
                        } else {
                            this.flashMessage(false);
                        }
                    });
            },
            flashMessage: function(ok = true) {
                if (ok) {
                    document.querySelector('.alert-success').style.display = "block";
                    setInterval(() => {
                        document.querySelector('.alert-success').style.display = "none";
                    }, 5000);
                } else {
                    document.querySelector('.alert-danger').style.display = "block";
                    setInterval(() => {
                        document.querySelector('.alert-danger').style.display = "none";
                    }, 5000);
                }
            }
        },
        beforeMount() {
            this.loadCars()
        }
    })
</script>

</html>