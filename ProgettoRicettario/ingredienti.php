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
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Briem+Hand:wght@100..900&family=Inter:wght@300;400;700;900&family=Jersey+10&family=Jersey+15&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
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

    <div id="formButton">
      <div id="containerForm">
      <form name="stringaRicerca" id="searchForm" method="POST" onsubmit="handleRicerca(event)">
        <input id="numeroRicetta" name="numeroRicetta" type="text" placeholder="Numero ricetta"/>
        <input id="numero" name="numero" type="text" placeholder="Numero dell'ingrediente"/>
		    <input id="nomeIngrediente" name="nomeIngrediente" type="text" placeholder="Nome dell'ingrediente"/>
		    <input id="searchButton" type="submit" value="Search"/>
		  </form>
      </div>

      <button type="submit" class="btnInsert" onclick="openPopupInsert()"> Insert </button>
    </div>

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
	      
        if(!empty($_POST["nRicetta"])){
          $query = getIngredientiPerRicetta($_POST["nRicetta"]);
        } else if(!empty($_GET["nRicetta"])){
          $query = getIngredientiPerRicetta($_GET["nRicetta"]);
        } else {
          $query = getIngredientiQry ($numeroRicetta, $numero, $ingrediente, $quantita);
        }

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
					<th>
            Numero ricetta
            <button onclick="sortTable(1, 'int')" class="sort-button">Sort</button>
        </th>
        <th>
            Numero ingrediente
            <button onclick="sortTable(2, 'int')" class="sort-button">Sort</button>
        </th>
        <th>
            Nome ingrediente
            <button onclick="sortTable(3, 'string')" class="sort-button">Sort</button>
        </th>
        <th>
            Quantità
            <button onclick="sortTable(4, 'int')" class="sort-button">Sort</button>
        </th>
        <th>
            Nome ricetta
            <button onclick="sortTable(5, 'string')" class="sort-button">Sort</button>
        </th>
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
          <td > <?php echo formattaLinkIngrediente($nomericetta); ?> </td>
          <td > <button type="submit" class="btnDelete" onclick="openPopupDelete(
            '<?php echo $numeroRicetta; ?>',
            '<?php echo $numero; ?>',
            '<?php echo $ingrediente; ?>',
            '<?php echo $quantita; ?>'
          )"> Delete </button> </td>
          <td > <button type="submit" class="btnUpdate" onclick="openPopupUpdate(
            '<?php echo $numeroRicetta; ?>',
            '<?php echo $numero; ?>',
            '<?php echo $ingrediente; ?>',
            '<?php echo $quantita; ?>'
          )"> Update </button> </td>
			  </tr>
        <?php } ?>
			</table>
    <?php }  ?>
    </div>
  </div>

</body>

</html>