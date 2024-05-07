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
    $controllo = "SELECT COUNT(*)" . 
                "FROM Ingrediente " . 
                "WHERE Ingrediente.ingrediente = '$ingrediente' AND Ingrediente.numeroRicetta = '$numeroRicetta'";
                "GROUP BY Ingrediente.ingrediente, Ingrediente.numeroRicetta";
    try {   
        $result = $conn->query($controllo);
    } catch(PDOException$e) {
        echo "<p> DB Error on Query: " . $e->getMessage() . "</p>";
        $error = true;
    }
    
    if ($result < 1) {
        $massimo = "SELECT MAX(Ingrediente.numero) " .
                    "FROM Ingrediente " . 
                    "WHERE Ingrediente.numero = '$numero'";
                    "GROUP BY Ingrediente.ingrediente";
                    "ORDER BY Ingrediente.ingrediente";

        try {   
            $result = $conn->query($controllo);
        } catch(PDOException$e) {
            echo "<p> DB Error on Query: " . $e->getMessage() . "</p>";
            $error = true;
        }
    }

    $insert = "INSERT INTO Ingrediente (numeroRicetta, numero, ingrediente, quantita) VALUES "


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