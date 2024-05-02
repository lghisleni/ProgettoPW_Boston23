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