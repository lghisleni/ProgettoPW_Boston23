<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="generator" content="AlterVista - Editor HTML"/>
  <title>Ricettario</title>

  <!-- foglio di stile -->
  <link rel="stylesheet" href="./css/ricettario.css">

  <!-- script -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript" src="./js/ricettario.js" defer></script>

  <!-- link per scaricare i font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;700;900&family=Jersey+10&family=Jersey+15&display=swap" rel="stylesheet">

</head>


<body>

  <?php
    include 'header.html';
    include 'footer.html';
    include 'nav.html';
    include 'dbQuery.php';
    include 'popupDelete.html';
    include 'popupUpdate.php';
    include 'popupInsert.php';
  ?>

  <div id="container">
  
    <form name="stringaRicerca" method="POST">
		  <input id="nomeIngrediente" name="nomeIngrediente" type="text" placeholder="Inserisci il nome dell'ingrediente"/>
		  <input id="searchButton" type="submit" value="Search"/>
		</form>

    <button type="submit" class="btnInsert" onclick="openPopupInsert()"> insert </button>

    <div  id="risultati">
    <?php
	      $numeroRicetta = "";
	      $numero  = "";
        $ingrediente  = "";
        $quantita  = "";	

	      if(count($_POST)>0) {
		      $numeroRicetta = $_POST["numeroRicetta"];
		      $numero = $_POST["numero"];
          $ingrediente = $_POST["nomeIngrediente"];
          $quantita = $_POST["quantita"];
	      }	     
	      else if(count($_GET)>0) {
		      $numeroRicetta = $_GET["numeroRicetta"];
		      $numero = $_GET["numero"];
          $ingrediente = $_GET["nomeIngrediente"];
          $quantita = $_GET["quantita"];
	      }	     
	      
        $query = getIngredientiQry ($numeroRicetta, $numero, $ingrediente, $quantita);
        echo "<p id=query><b><u>Ingrediente Query</u></b>: " . formattaQuery($query) . "</p>";

	      include 'connDb.php';

	      try {   
		      $result = $conn->query($query);
	      } catch(PDOException$e) {
		      echo "<p> DB Error on Query: " . $e->getMessage() . "</p>";
		      $error = true;
	      }
	      if(!$error) {      
      ?>

      <table class="table">

				<tr class="header">
          <th>#</th>
					<th>numero ricetta</th> 
					<!--th>id </th--> 
					<th>numero ingrediente</th> 
					<th>nome ingrediente</th>
          <th># quantita</th>
          <th># nome ricetta</th>
          <th>Delete</th>
          <th>Update</th>
				</tr>
      
        <?php
		      $i=0;
		      foreach($result as $riga) {
			    $i=$i+1;
			    $classRiga='class="rowOdd"';
			    if($i%2==0) {
			    	$classRiga='class="rowEven"';
			    }
			    $numeroRicetta=$riga["ricetta"];
			    $numero=$riga["numero"];
			    $ingrediente=$riga["ingrediente"];
          $quantita=$riga["quantita"];
          $nomericetta=$riga["nomericetta"];
        ?>
      
        <tr <?php	echo $classRiga; ?> > 
			    <td > <?php echo $i; ?> </td>    						
				  <td > <?php echo $numeroRicetta; ?> </td>    
				  <td > <?php echo $numero; ?> </td> 
				  <td > <?php echo $ingrediente; ?> </td>
          <td > <?php echo $quantita; ?> </td>
          <td > <?php echo $nomericetta; ?> </td>
          <td > <button type="submit" class="btnDelete" onclick="openPopupDelete(
            '<?php echo $numeroRicetta; ?>',
            '<?php echo $numero; ?>',
            '<?php echo $ingrediente; ?>',
            '<?php echo $quantita; ?>'
          )"> delete </button> </td>
          <td > <button type="submit" class="btnUpdate" onclick="openPopupUpdate(
            '<?php echo $numeroRicetta; ?>',
            '<?php echo $numero; ?>',
            '<?php echo $ingrediente; ?>',
            '<?php echo $quantita; ?>'
          )"> update </button> </td>
			  </tr>
        <?php } ?>
			</table>
    <?php }  ?>
    </div>
  </div>

</body>

</html>