<!DOCTYPE html>
<html>
<head>
    <!-- Foglio di stile -->
    <link rel="stylesheet" href="index.css">

    <!-- Link per scaricare i font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;700;900&family=Jersey+10&family=Jersey+15&display=swap" rel="stylesheet">
</head>

<body>

    <?php
        include 'header.html';
        include 'footer.html';
    ?>
    
    <?php
    include 'connDb.php';

    // Elenco delle tabelle da selezionare
    $selected_tables = ["Libro", "Pagina", "Regione", "Ricetta", "Ingrediente", "RicettaPubblicata", "RicettaRegionale"];

    // Array per memorizzare i dati del database
    $db_data = array();

    foreach ($selected_tables as $table) {
        $sql = "SELECT * FROM $table";
        try {
            $result = $conn->query($sql);
            $table_data = $result->fetchAll(PDO::FETCH_ASSOC);
            $db_data[$table] = $table_data;
        } catch (PDOException $e) {
            echo "<p>DB Error on Query: " . $e->getMessage() . "</p>";
            $db_data[$table] = array();
        }
    }

    // Chiudere la connessione al database
    $conn = null;

    // Converti i dati in JSON
    $json_data = json_encode($db_data);
    ?>

    <script>
        // Passare i dati JSON dal PHP al JavaScript
        const dbData = <?php echo $json_data; ?>;

        // Funzione per mostrare i dati JSON
        function displayData() {
            const container = document.getElementById('data-container');

            for (const table in dbData) {
                const tableDiv = document.createElement('div');
                tableDiv.classList.add('table-data');

                const tableTitle = document.createElement('h3');
                tableTitle.classList.add('table-title');
                tableTitle.textContent = table;

                const tableContent = document.createElement('pre');
                tableContent.textContent = JSON.stringify(dbData[table], null, 2);

                tableDiv.appendChild(tableTitle);
                tableDiv.appendChild(tableContent);
                container.appendChild(tableDiv);
            }
        }

        // Chiamare la funzione per mostrare i dati quando la pagina è caricata
        window.onload = displayData;
    </script>

    <!-- Div per mostrare i dati -->
    <h1> Web Service Remoto per l'esposizione dei dati all'interno del DB - Boston23 </h1> 
    <div class="container" id="data-container"> </div>
</body>

</html>
