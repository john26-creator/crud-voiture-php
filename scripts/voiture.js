function deleteVoiture(idVoiture) {
    fetch(`src/controllers/FrontController.php?function=deleteVoiture&id=${idVoiture}`)
        .then(result => result.json())
        .then(data => {
            if(data.status === "OK") {
                let element = document.querySelector(`#v${idVoiture}`);
                element.parentNode.removeChild(element);
                flashMessage(true);
            } else {
                flashMessage(false);
            }
        }
        );
}

function chargerPage() {
    fetch(`src/controllers/FrontController.php?function=afficherVoitures`)
        .then(result =>
            result.json())
        .then(data => {
            if (data.status === "OK") {
                document.querySelector("#content").innerHTML = '';
                document.querySelector("#content").appendChild(tableCreate(data.result));
            } else {
                flashMessage(false);
            }
        }
        );
}
chargerPage();

function tableCreate(rows) {
    var tbl = document.createElement('table');
    tbl.setAttribute("style", "width:90%;margin:auto;padding-top:20px;");
    tbl.setAttribute("class", "table table-dark table-sm");
    var thead = document.createElement('thead');
    var trh = document.createElement('tr');
    thead.appendChild(trh);
    addthTotr(trh, 'Immat');
    addthTotr(trh, 'Couleur');
    addthTotr(trh, 'Marque');
    addthTotr(trh, 'Modele');
    addthTotr(trh, 'Supprimer');

    var tbdy = document.createElement('tbody');
    rows.forEach(element => {
        addRow(tbdy, element);
    });
    tbl.appendChild(thead);
    tbl.appendChild(tbdy);
    return tbl;
}

function addTdToTr(tr, name, text) {
    var td = document.createElement('td');
    var input = document.createElement('input');
    input.setAttribute("type", "text");
    input.setAttribute("name", name);
    input.setAttribute("value", text);
    input.setAttribute("onChange", `updateVoiture('${tr.id.substring(1)}',event)`);
    td.appendChild(input);
    tr.appendChild(td);
}

function addthTotr(tr, text) {
    var th = document.createElement('th');
    th.appendChild(document.createTextNode(text));
    tr.appendChild(th);
}

function updateVoiture(id, event) {
    let name = event.target.name;
    var payload = { "name": name, "value": event.target.value, "id": id };

    var data = new FormData();
    data.append("json", JSON.stringify(payload));

    fetch(`src/controllers/FrontController.php?function=modifierVoiture`, { //Et si il est valide alors du fait une requete ajax
        method: "POST", // En post
        body: data //Avec le formulaire
    })
        .then(result => result.json())
        .then(data => {
            if (data.status === "OK") {
                flashMessage(true);
            } else {
                flashMessage(false);
            }
        });
}


function ajouterVoiture(event) {
    event.preventDefault();
    fetch(`src/controllers/FrontController.php?function=ajouterVoiture`, { //Et si il est valide alors du fait une requete ajax
        method: "POST", // En post
        body: new FormData(document.querySelector('form')) //Avec le formulaire
    })
        .then(result => result.json())
        .then(data => {
            if (data.status === "OK") {
                let tbody = document.querySelector("tbody");
                addRow(tbody, data.result);
                flashMessage(true);
            } else {
                flashMessage(false);
            }
        });
}

function addRow(tbody, row) {

    var tr = document.createElement('tr');
    tr.setAttribute("id", `v${row['id']}`);
    tbody.appendChild(tr);
    addTdToTr(tr, "immat", row['immat']);
    addTdToTr(tr, "couleur", row['couleur']);
    addTdToTr(tr, "marque", row['marque']);
    addTdToTr(tr, "modele", row['modele']);
    var tdButton = document.createElement('td');
    var deleteButton = document.createElement('button');
    deleteButton.setAttribute("onClick", `deleteVoiture(${row['id']},event)`);
    deleteButton.setAttribute("class", "btn btn-danger");
    deleteButton.setAttribute("type", "button");
    deleteButton.appendChild(document.createTextNode("Supprimer"));
    tdButton.appendChild(deleteButton);
    tr.appendChild(tdButton);
    tbody.appendChild(tr);
}



function flashMessage(ok = true) {
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