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

/* funzioni di delete */
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
            location.reload();
        }
    };

    let parameters = "functionName=deleteIngredientiQry" +
                 "&numeroRicetta=" + encodeURIComponent(numeroRicetta) +
                 "&numero=" + encodeURIComponent(numero) +
                 "&ingrediente=" + encodeURIComponent(ingrediente) +
                 "&quantita=" + encodeURIComponent(quantita);

    xhr.send(parameters);
}


/* funzioni di update*/
let rowToUpdate = []

function openPopupUpdate($numeroRicetta, $numero, $ingrediente, $quantita){ 
    rowToUpdate[0] = $numeroRicetta;
    rowToUpdate[1] = $numero;
    rowToUpdate[2] = $ingrediente;
    rowToUpdate[3] = $quantita;
    var popUpDelete = document.getElementById("popupUpdate")
    popUpDelete.classList.add("open-popupUpdate")
    var formQuant = document.getElementById("quantitaUp")
    formQuant.value = $quantita
    var formNome = document.getElementById("nomeIngredienteUp")
    formNome.value = $ingrediente
}

function handleFormSubmit(event) {
    event.preventDefault();
    var quantitaUp = document.getElementById("quantitaUp").value;
    var nomeIngredienteUp = document.getElementById("nomeIngredienteUp").value;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "https://programmazioneweblg.altervista.org/ProgettoRicettario/dbQuery.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                // Ricarica la pagina solo dopo che la richiesta è stata completata con successo
                location.reload();
            } else {
                console.error("Errore durante l'aggiornamento dei dati");
            }
        }
    };

    let parameters = "functionName=updateIngredientiQry" +
                     "&numeroRicetta=" + encodeURIComponent(rowToUpdate[0]) +
                     "&numero=" + encodeURIComponent(rowToUpdate[1]) +
                     "&ingrediente=" + encodeURIComponent(nomeIngredienteUp) +
                     "&quantita=" + encodeURIComponent(quantitaUp);

    xhr.send(parameters);
    return false;
}

function annulaUpdate(){
    var popUpUpdate = document.getElementById("popupUpdate")
    popUpUpdate.classList.remove("open-popupUpdate")
}

/* funzioni di insert*/

function openPopupInsert(){ 
    var popUpInsert = document.getElementById("popupInsert")
    popUpInsert.classList.add("open-popupInsert")
}

function handleFormInsert(event) {
    event.preventDefault();
    var numeroRicettaIns = document.getElementById("numeroRicettaIns").value
    console.log(numeroRicettaIns)
    var quantitaIns = document.getElementById("quantitaIns").value;
    console.log(quantitaIns)
    var nomeIngredienteIns = document.getElementById("nomeIngredienteIns").value;
    console.log(nomeIngredienteIns)


    let xhr = new XMLHttpRequest();
    xhr.open("POST", "https://programmazioneweblg.altervista.org/ProgettoRicettario/dbQuery.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                // Ricarica la pagina solo dopo che la richiesta è stata completata con successo
                location.reload();
            } else {
                console.error("Errore durante l'aggiornamento dei dati");
            }
        }
    };

    let parameters = "functionName=insertIngredientiQry" +
                     "&numeroRicetta=" + encodeURIComponent(numeroRicettaIns) +
                     "&ingrediente=" + encodeURIComponent(nomeIngredienteIns) +
                     "&quantita=" + encodeURIComponent(quantitaIns);

    xhr.send(parameters);
    return false;
}

function annulaInsert(){
    var popUpInsert = document.getElementById("popupInsert")
    popUpInsert.classList.remove("open-popupInsert")
}

