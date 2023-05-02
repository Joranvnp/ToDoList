// var tacheInput = document.getElementById('tache');
// var tacheListe = document.getElementById('listeTache');
// var taches = [];


const form = document.querySelector('form');
const tbody = document.querySelector('#taches');

$(document).ready(function() {

    afficherTache();
    
    $(document).on("click", "#ajoutTache", function () {
        $.ajax({
            method: "POST",
            url: "http://172.19.0.2/TP ToDoList/API/index.php?action=tache",
            data: JSON.stringify({ 
              tache: $("#tache").val(), 
              priorite: $("#priorite").val(), 
              categorie: $("#categorie").val() 
            })
        })
        .done(function(data, textStatus, jqXHR) {
            $('#taches').empty();
            afficherTache();
			console.log(data); 
			console.log(textStatus);
			console.log(jqXHR);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
			alert("Erreur !");
			console.log(jqXHR); 
			console.log(textStatus); 
			console.log(errorThrown);
        })
    });

});

function afficherPriorite(priorite) {

    var priorite = parseInt(priorite);
    
    switch (priorite) {
        case 1:
            return "Très Important";
        case 2:
            return "Important";
        case 3:
            return "Peu Important";
        default:
           return "Priorité Inconnue";
    }
}


function afficherTache() {
    $.ajax({
        method: "GET",
        url: "http://172.19.0.2/TP ToDoList/API/index.php?action=tache",
    })
    .done(function(data) {

        data.sort(function(a, b) {
            return a.priorite - b.priorite;
        });        

        data.forEach(function(tache) {
            var row = document.createElement("tr");
            tbody.appendChild(row);

            var tacheCell = document.createElement("td");
            tacheCell.textContent = tache.tache;
            row.appendChild(tacheCell);

            var categorieCell = document.createElement("td");
            categorieCell.textContent = tache.categorie;
            row.appendChild(categorieCell);

            var prioriteCell = document.createElement("td");
            prioriteCell.textContent = afficherPriorite(tache.priorite);
            row.appendChild(prioriteCell);

            var id = tache.id;

            var supprimerCell = document.createElement("td");
            var supprimerButton = document.createElement("button");
            supprimerButton.textContent = "Supprimer";
            supprimerButton.setAttribute("class", "butsupprimer");
            supprimerButton.setAttribute("data-id", id);
            supprimerCell.appendChild(supprimerButton);
            row.appendChild(supprimerCell);
            
            var modifierCell = document.createElement("td");
            var modifierButton = document.createElement("button");
            modifierButton.setAttribute("class", "butmodifier");
            modifierButton.setAttribute("data-id", id);
            modifierButton.textContent = "Modifier";
            modifierCell.appendChild(modifierButton);
            row.appendChild(modifierCell);
        });
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        alert("Erreur !");
        console.log(jqXHR); 
        console.log(textStatus); 
        console.log(errorThrown);
    });
}

$(document).on("click", ".butsupprimer", function () {
    var id = $(this).data("id");
    var donnees = {id: id};
    if (confirm("Êtes-vous sûr de vouloir supprimer cette tâche ?")) {
        $.ajax({
            method: "DELETE",
            url: "http://172.19.0.2/TP ToDoList/API/index.php?action=tache&id=" + id,
            // data: JSON.stringify({ id: id }),
            data: JSON.stringify(donnees),
            contentType: "application/json; charset=utf-8"
        })
        .done(function(data, textStatus, jqXHR) {
            $('#taches').empty();
            afficherTache();
            console.log(data);
            console.log(textStatus);
            console.log(jqXHR);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            alert("Erreur !");
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    }
});

//MODIFIER

$(document).on("click", ".butmodifier", function () {
    var id = $(this).data("id");
    console.log(id);

    var tache = $(this).closest('tr').find('td:eq(0)').text();
    var categorie = $(this).closest('tr').find('td:eq(1)').text();
    var priorite = $(this).closest('tr').find('td:eq(2) select').val();
    var prioriteActuelle = $(this).closest('tr').find('td:eq(2)').text();

    $(this).closest('tr').find('td:eq(0)').html('<input type="text" class="modinput" id="tache" value="' + tache + '">');
    $(this).closest('tr').find('td:eq(1)').html('<input type="text" class="modinput" id="categorie" value="' + categorie + '">');
    $(this).closest('tr').find('td:eq(2)').html('<select id="priorite" class="modselect" name="priorite"><option value="3">Peu Important</option><option value="2">Important</option><option value="1">Très Important</option></select>');

    var select = $(this).closest('tr').find('td:eq(2)').find('select');
    select.val(priorite);
    select.find('option').each(function() {
        if ($(this).text() == prioriteActuelle) {
            $(this).attr('selected', 'selected');
        }
    });
        
    $(this).closest('tr').find('td:eq(3)').html('<button class="butvalider" data-id="' + id + '">Confirmer</button>');
    $(this).closest('tr').find('td:eq(4)').html('<button class="butannuler" data-id="' + id + '">Annuler</button>');

    disableEditButtons($(this));

});

function disableEditButtons(except) {
    $('.butmodifier').not(except).prop('disabled', true);
}

function enableEditButtons() {
    $('.butmodifier').prop('disabled', false);
}

$(document).on("click", ".butannuler", function () {
    $('#taches').empty();
    afficherTache();
    enableEditButtons();
});

$(document).on("click", ".butvalider", function () {
    var id = $(this).data("id");

    var tache = $(this).closest('tr').find('td:eq(0) input').val();
    var priorite = $(this).closest('tr').find('td:eq(2) select').val();
    var categorie = $(this).closest('tr').find('td:eq(1) input').val();

    var donnees = {tache: tache, priorite: priorite, categorie: categorie, id: id};

    $.ajax({
        method: "PUT",
        url: "http://172.19.0.2/TP ToDoList/API/index.php?action=tache&id=" + id,
        data: JSON.stringify(donnees),
        contentType: "application/json; charset=utf-8"
    })
    .done(function(data, textStatus, jqXHR) {
        $('#taches').empty();
        afficherTache();
        console.log(data);
        console.log(textStatus);
        console.log(jqXHR);
        enableEditButtons();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        alert("Erreur !");
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
        enableEditButtons();
    });
});
