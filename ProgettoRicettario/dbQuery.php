<?php
function getRicettaQry($numero, $titolo, $tipo): string {
    $numeroLibri = "(SELECT Ricetta.numero AS numero1, COUNT(DISTINCT Libro.codISBN) AS nlibri1 " .
                    "FROM Ricetta " .
                    "JOIN RicettaPubblicata ON Ricetta.numero = RicettaPubblicata.numeroRicetta " . 
                    "JOIN Pagina ON RicettaPubblicata.libro = Pagina.libro " . 
                    "JOIN Libro ON Pagina.libro = Libro.codISBN " .
                    "GROUP BY Ricetta.numero) AS Ricetta1";

    $qry = "SELECT Ricetta.numero AS numero, Ricetta.titolo AS titolo, Ricetta.tipo AS tipo, Libro.titolo AS titololibro, Ricetta1.nlibri1 AS nlibri " .
           "FROM Ricetta " .
           "JOIN RicettaPubblicata ON Ricetta.numero = RicettaPubblicata.numeroRicetta " . 
           "JOIN Pagina ON RicettaPubblicata.libro = Pagina.libro " . 
           "JOIN Libro ON Pagina.libro = Libro.codISBN " .
           "JOIN " . $numeroLibri . " ON Ricetta.numero = Ricetta1.numero1 " .
           "WHERE 1=1 ";

    if ($numero != "") {
        $qry .= "AND Ricetta.numero = '$numero' ";
    }
    if ($titolo != "") {
        $qry .= "AND Ricetta.titolo LIKE '%$titolo%' ";
    }
    if ($tipo != "") {
        $qry .= "AND Ricetta.tipo = '$tipo' ";
    }
    $qry .= "GROUP BY Ricetta.numero, Ricetta.titolo, Ricetta.tipo, Libro.titolo " .
            "ORDER BY Ricetta.numero";

    return $qry;
}


function getLibriQry($codISBN, $titolo, $anno): string {
    $qry = "SELECT Libro.codISBN AS codISBN, Libro.titolo AS titolo, Libro.anno AS anno, COUNT(DISTINCT Ricetta.numero) AS nRicette, COUNT(DISTINCT Pagina.numeroPagina) as nPagine " .
           "FROM Libro " .
           "JOIN Pagina ON Libro.codISBN = Pagina.libro " .
           "JOIN RicettaPubblicata ON Pagina.libro = RicettaPubblicata.libro " .
           "JOIN Ricetta ON RicettaPubblicata.numeroRicetta = Ricetta.numero " .
           "WHERE 1=1 ";
    if ($codISBN != "") {
        $qry .= "AND Libro.codISBN = '$codISBN' ";
    }
    if ($titolo != "") {
        $qry .= "AND Libro.titolo LIKE '%$titolo%' ";
    }
    if ($anno != "") {
        $qry .= "AND Libro.anno = '$anno' ";
    }
    $qry .= "GROUP BY Libro.codISBN, Libro.titolo, Libro.anno " .
            "ORDER BY Libro.codISBN";
    return $qry;   
}

function getRegioneQry($cod, $nome): string {
    $qry = "SELECT Regione.cod AS cod, Regione.nome AS nome, COUNT(DISTINCT Ricetta.titolo) AS nRicette " .
           "FROM Regione " .
           "JOIN RicettaRegionale ON Regione.cod = RicettaRegionale.regione " .
           "JOIN Ricetta ON RicettaRegionale.ricetta = Ricetta.numero " .
           "WHERE 1=1 ";
    if ($cod != "") {
        $qry .= "AND Regione.cod = '$cod' ";
    }
    if ($nome != "") {
        $qry .= "AND Regione.nome LIKE '%$nome%' ";
    }
    
    $qry .= "GROUP BY Regione.cod, Regione.nome " .
            "ORDER BY Regione.cod";
    return $qry;   
}

function getIngredientiQry($numeroRicetta, $numero, $ingrediente, $quantita): string {
    $qry = "SELECT Ingrediente.numeroRicetta as ricetta, Ingrediente.numero as numero, Ingrediente.ingrediente as ingrediente, Ingrediente.quantita as quantita, Ricetta.titolo as nomericetta " . 
            "FROM Ingrediente " . 
            "JOIN Ricetta ON Ingrediente.numeroRicetta = Ricetta.numero " . 
            "WHERE 1=1 ";
    if ($numeroRicetta != "") {
        $qry .= "AND Ingrediente.numeroRicetta = '$numeroRicetta' ";
    }
    if ($numero != "") {
        $qry .= "AND Ingrediente.numero = '$numero' ";
    }
    if ($ingrediente != "") {
        $qry .= "AND Ingrediente.ingrediente LIKE '%$ingrediente%' ";
    }
    if ($quantita != "") {
        $qry .= "AND Ingrediente.quantita = '$quantita' ";
    }

    $qry .= "ORDER BY Ingrediente.numero";
    return $qry; 
}

function insertIngredientiQry($conn, $numeroRicetta, $numero, $ingrediente, $quantita): string {
    $controllo = "SELECT 1 FROM Ingrediente " .
                    "WHERE Ingrediente.ingrediente = '$ingrediente' AND Ingrediente.numeroRicetta = '$numeroRicetta'";
    $stmt = $conn->prepare($controllo);
    $stmt->execute([$ingrediente, $numeroRicetta]);
    $esisteNellaRicetta = $stmt->fetch();
    
    if ($esisteNellaRicetta) {
        // L'ingrediente esiste già per questa ricetta, non inserire nulla
        return "L'ingrediente esiste già per questa ricetta.";
    } else {
        // Controlla se l'ingrediente esiste già in qualche ricetta
        $controlloGenerale = "SELECT Ingrediente.numero FROM Ingrediente WHERE Ingrediente.ingrediente = '$ingrediente'";
        $stmt = $conn->prepare($controlloGenerale);
        $stmt->execute([$ingrediente]);
        $ingredienteEsistente = $stmt->fetch();
        if ($ingredienteEsistente) {
            // Usa il numero esistente per l'ingrediente trovato
            $numero = $ingredienteEsistente['numero'];
        } else {
            // L'ingrediente non esiste, trova il numero massimo e incrementa per un nuovo ingrediente
            $trovaMax = "SELECT MAX(numero) AS maxNumero FROM Ingrediente";
            $stmt = $conn->prepare($trovaMax);
            $stmt->execute();
            $maxRow = $stmt->fetch();
            $numero = $maxRow['maxNumero'] + 1;
        }

        // Inserisci il nuovo ingrediente con il numero ottenuto
        $inserimento = "INSERT INTO Ingrediente (numeroRicetta, numero, ingrediente, quantita) VALUES ('$numeroRicetta', '$numero', '$ingrediente', '$quantita')";
        $stmt = $conn->prepare($inserimento);
        $stmt->execute([$numeroRicetta, $numero, $ingrediente, $quantita]);
        return "Ingrediente inserito correttamente.";
    }
}   

function formattaQuery($inputString) {
    // Controlla se la stringa di input non è vuota
    if (empty($inputString)) {
        return "La stringa di input è vuota.";
    }

    $search = array("SELECT", "FROM", "WHERE", "GROUP BY", "ORDER BY");
    $replace = array("\n<br><b style='color:#8B4513;'>SELECT</b>", "\n<br><b style='color:#8B4513;'>FROM</b>", "\n<br><b style='color:#8B4513;'>WHERE</b>", "\n<br><b style='color:#8B4513;'>GROUP BY</b>", "\n<br><b style='color:#8B4513;'>ORDER BY</b>");

    // Sostituisci il punto "." con un carattere di nuova riga "\n"
    $outputString = str_replace($search, $replace, $inputString);

    return $outputString;
}
?>