function setupPagina(element_id) {
    // Seleziona l'elemento HTML
    var home_id = "nav1";
    var home_element = document.getElementById(home_id)
    var elemento = document.getElementById(element_id);
    
    // Verifica se l'elemento esiste
    if (elemento) {
        // Cambia la classe
        home_element.classList.remove("active");
        elemento.className = "active";
    } else {
        console.error("L'elemento con ID " + elementId + " non esiste.");
    }

    // Cambiamo il titolo della header
    if(element_id == "nav2") {
        setH1regioni();
    } else if(element_id == "nav3") {
        setH1ricette();
    } else if(element_id == "nav4") {
        setH1libri();
    }
}

function setH1regioni() {       
	$("#titoloHeader").eq(0).html("Regioni");
}

function setH1ricette() {       
	$("#titoloHeader").eq(0).html("Ricette");
}

function setH1libri() {       
	$("#titoloHeader").eq(0).html("Libri");
}

let rowToDelete = []

function openPopupDelete($numeroRicetta, $numero, $ingrediente, $quantita){ 
    rowToDelete[0] = $numeroRicetta;
    rowToDelete[1] = $numero;
    rowToDelete[2] = $ingrediente
    rowToDelete[3] = $quantita;
    var popUpDelete = document.getElementById("popupDelete")
    popUpDelete.classList.add("open-popupDelete")
}

function annulaEliminazione(){
    var popUpDelete = document.getElementById("popupDelete")
    popUpDelete.classList.remove("open-popupDelete")
}


function eliminaDefinitivamente() {
    var popUpDelete = document.getElementById("popupDelete")
    popUpDelete.classList.remove("open-popupDelete")
    var numeroRicetta = rowToDelete[0];
    var numero = rowToDelete[1];
    var ingrediente = rowToDelete[2];
    var quantita = rowToDelete[3];
    
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "https://programmazioneweblg.altervista.org/ProgettoRicettario/dbQuery.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
        }
    };

    let parameters = "functionName=deleteIngredientiQry" +
                 "&numeroRicetta=" + encodeURIComponent(numeroRicetta) +
                 "&numero=" + encodeURIComponent(numero) +
                 "&ingrediente=" + encodeURIComponent(ingrediente) +
                 "&quantita=" + encodeURIComponent(quantita);

    xhr.send(parameters);
}
