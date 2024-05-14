<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['functionName'])) {
        switch ($_POST['functionName']) {
            case 'deleteIngredientiQry':
                deleteIngredientiQry($_POST['numeroRicetta'], $_POST['numero'], $_POST['ingrediente'], $_POST['quantita']);
                break;
            case 'updateIngredientiQry':
                updateIngredientiQry($_POST['numeroRicetta'], $_POST['numero'], $_POST['ingrediente'], $_POST['quantita']);
                break;
            case 'insertIngredientiQry':
                insertIngredientiQry($_POST['numeroRicetta'], $_POST['ingrediente'], $_POST['quantita']);
                break;
        } 
    }
}

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

function insertIngredientiQry($numeroRicetta, $ingrediente, $quantita){
    include 'connDb.php';
    
    try {
        // Verifica se l'ingrediente esiste già nella stessa ricetta
        $stmt = $conn->prepare("SELECT 1 FROM Ingrediente WHERE ingrediente = ? AND numeroRicetta = ?");
        $stmt->execute([$ingrediente, $numeroRicetta]);
        $esisteNellaRicetta = $stmt->fetch();

        if ($esisteNellaRicetta) {
            return "L'ingrediente esiste già nella stessa ricetta.";
        }

        // Trova il numero massimo per l'ingrediente nella ricetta specificata
        $stmt = $conn->prepare("SELECT MAX(numero) AS maxNumero FROM Ingrediente WHERE numeroRicetta = ?");
        $stmt->execute([$numeroRicetta]);
        $maxRow = $stmt->fetch();
        $numero = $maxRow['maxNumero'] + 1;

        // Inserisci il nuovo ingrediente con il numero ottenuto
        $stmt = $conn->prepare("INSERT INTO Ingrediente (numeroRicetta, numero, ingrediente, quantita) VALUES (?, ?, ?, ?)");
        $stmt->execute([$numeroRicetta, $numero, $ingrediente, $quantita]);
        
        return "Ingrediente inserito correttamente.";
        
    } catch (PDOException $e) {
        // Gestione degli errori
        return "Errore durante l'inserimento dell'ingrediente: " . $e->getMessage();
    }
}


function deleteIngredientiQry($numeroRicetta, $numero, $ingrediente, $quantita): string {
    $qry = "DELETE FROM Ingrediente WHERE numeroRicetta = :numeroRicetta AND numero = :numero AND ingrediente = :ingrediente AND quantita = :quantita";
    
    include 'connDb.php';
    // Preparazione dello statement
    $stmt = $conn->prepare($qry);

    // Binding dei parametri
    $stmt->bindParam(':numeroRicetta', $numeroRicetta);
    $stmt->bindParam(':numero', $numero);
    $stmt->bindParam(':ingrediente', $ingrediente);
    $stmt->bindParam(':quantita', $quantita);

    // Esecuzione dello statement
    try {
        $stmt->execute();
        return "Ingrediente eliminato correttamente.";
    } catch (PDOException $e) {
        return "Errore durante l'eliminazione dell'ingrediente: " . $e->getMessage();
    }
}

function updateIngredientiQry($numeroRicetta, $numero, $ingrediente, $quantita) {
    include 'connDb.php';
    
    try {
        // Verifica se l'ingrediente esiste già nella stessa ricetta
        $stmt = $conn->prepare("SELECT 1 FROM Ingrediente WHERE ingrediente = ? AND numeroRicetta = ? AND numero != ?");
        $stmt->execute([$ingrediente, $numeroRicetta, $numero]);
        $esisteNellaRicetta = $stmt->fetch();

        if ($esisteNellaRicetta) {
            return "L'ingrediente esiste già nella stessa ricetta.";
        }

        // Trova il massimo numero per l'ingrediente nella ricetta specificata
        $stmt = $conn->prepare("SELECT MAX(numero) AS maxNumero FROM Ingrediente WHERE numeroRicetta = ?");
        $stmt->execute([$numeroRicetta]);
        $maxRow = $stmt->fetch();
        $numeroNew = $maxRow['maxNumero'];

        // Aggiornamento
        $updateQuery = "UPDATE Ingrediente SET
                        ingrediente = :newIngrediente,
                        quantita = :newQuantita
                        WHERE numero = :originalNumero AND numeroRicetta = :numeroRicetta";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':newIngrediente', $ingrediente);
        $stmt->bindParam(':newQuantita', $quantita);
        $stmt->bindParam(':originalNumero', $numero);
        $stmt->bindParam(':numeroRicetta', $numeroRicetta);

        $stmt->execute();

        return "Ingrediente aggiornato correttamente.";
        
    } catch (PDOException $e) {
        // Gestione degli errori
        return "Errore durante l'aggiornamento dell'ingrediente: " . $e->getMessage();
    }
}

function formattaLinkIngrediente($nomeRicetta): String{
	return "<a href='ricette.php?nomeRicetta=" . $nomeRicetta . "'>" . $nomeRicetta . "</a>";
}

function formattaLinkRegioni($n, $codice) {
    return "<a href='ricette.php?regione=" . urlencode($codice) . "'>" . $n . "</a>";
}

function getRicettaPerRegioneQry($codiceRegione): string {
    $numeroLibri = "(SELECT Ricetta.numero AS numero1, COUNT(DISTINCT Libro.codISBN) AS nlibri1 " .
                    "FROM Ricetta " .
                    "JOIN RicettaPubblicata ON Ricetta.numero = RicettaPubblicata.numeroRicetta " . 
                    "JOIN Pagina ON RicettaPubblicata.libro = Pagina.libro " . 
                    "JOIN Libro ON Pagina.libro = Libro.codISBN " .
                    "GROUP BY Ricetta.numero) AS Ricetta1";

    $qry = "SELECT DISTINCT Ricetta.numero AS numero, Ricetta.titolo AS titolo, Ricetta.tipo AS tipo, Libro.titolo AS titololibro, Ricetta1.nlibri1 AS nlibri " .
           "FROM Ricetta " .
           "JOIN RicettaPubblicata ON Ricetta.numero = RicettaPubblicata.numeroRicetta " . 
           "JOIN Pagina ON RicettaPubblicata.libro = Pagina.libro " . 
           "JOIN Libro ON Pagina.libro = Libro.codISBN " .
           "JOIN " . $numeroLibri . " ON Ricetta.numero = Ricetta1.numero1 " .
           "JOIN RicettaRegionale ON Ricetta.numero = RicettaRegionale.ricetta " .
           "WHERE 1=1 ";

    if ($codiceRegione != "") {
        $qry .= "AND RicettaRegionale.regione = '$codiceRegione' ";
    }

    $qry .= "GROUP BY Ricetta.numero, Ricetta.titolo, Ricetta.tipo, Libro.titolo " .
            "ORDER BY Ricetta.numero";

    return $qry;
}


function formattaLinkRicetta1($numero, $nomeRicetta): String{
	return "<a href='ingredienti.php?nRicetta=" . $numero . "'>" . $nomeRicetta . "</a>";
}

function getIngredientiPerRicetta($numeroRicetta): string {
    $qry = "SELECT Ingrediente.numeroRicetta as ricetta, Ingrediente.numero as numero, Ingrediente.ingrediente as ingrediente, Ingrediente.quantita as quantita, Ricetta.titolo as nomericetta " . 
            "FROM Ingrediente " . 
            "JOIN Ricetta ON Ingrediente.numeroRicetta = Ricetta.numero " . 
            "WHERE Ingrediente.numeroRicetta = '$numeroRicetta' " .
            "ORDER BY Ingrediente.numero";
    return $qry; 
}

function formattaLinkRicetta2($titoloLibro){
    return "<a href='libri.php?nomeLibro=" . $titoloLibro . "'>" . $titoloLibro . "</a>";
}

function formattaLinkLibri($numeroRicette, $codice){
    return "<a href='ricette.php?codiceLibro=" . $codice . "'>" . $numeroRicette . "</a>";
}

function getRicettePerLibro($codiceLibro): string {
    $numeroLibri = "(SELECT Ricetta.numero AS numero1, COUNT(DISTINCT Libro.codISBN) AS nlibri1 " .
                    "FROM Ricetta " .
                    "JOIN RicettaPubblicata ON Ricetta.numero = RicettaPubblicata.numeroRicetta " . 
                    "JOIN Pagina ON RicettaPubblicata.libro = Pagina.libro " . 
                    "JOIN Libro ON Pagina.libro = Libro.codISBN " .
                    "GROUP BY Ricetta.numero) AS Ricetta1";

    $qry = "SELECT DISTINCT Ricetta.numero AS numero, Ricetta.titolo AS titolo, Ricetta.tipo AS tipo, Libro.titolo AS titololibro, Ricetta1.nlibri1 AS nlibri " .
           "FROM Ricetta " .
           "JOIN RicettaPubblicata ON Ricetta.numero = RicettaPubblicata.numeroRicetta " . 
           "JOIN Pagina ON RicettaPubblicata.libro = Pagina.libro " . 
           "JOIN Libro ON Pagina.libro = Libro.codISBN " .
           "JOIN " . $numeroLibri . " ON Ricetta.numero = Ricetta1.numero1 " .
           "WHERE 1=1 ";

    if ($codiceLibro != "") {
        $qry .= "AND Libro.codISBN = '$codiceLibro' ";
    }

    $qry .= "GROUP BY Ricetta.numero, Ricetta.titolo, Ricetta.tipo, Libro.titolo " .
            "ORDER BY Ricetta.numero";

    return $qry;
}

?>