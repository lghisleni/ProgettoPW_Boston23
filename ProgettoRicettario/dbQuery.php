<?php
function getRicettaQry($numero, $titolo, $tipo): string {
    $qry = "SELECT Ricetta.numero AS numero, Ricetta.titolo AS titolo, Ricetta.tipo AS tipo, Libro.titolo as titololibro, COUNT(DISTINCT Libro.codISBN) AS nlibri " .
           "FROM Ricetta " .
           "JOIN RicettaPubblicata ON Ricetta.numero = RicettaPubblicata.numeroRicetta " . 
           "JOIN Pagina ON RicettaPubblicata.libro = Pagina.libro " . 
           "JOIN Libro ON Pagina.libro = Libro.codISBN " .
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
    $qry .= "GROUP BY Ricetta.numero,  Ricetta.titolo, Ricetta.tipo, Libro.titolo" .
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
?>