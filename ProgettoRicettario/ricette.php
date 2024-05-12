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
  <script type="text/javascript" src="./js/ricettario.js"></script>

  <!-- link per scaricare i font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Briem+Hand:wght@100..900&family=Inter:wght@300;400;700;900&family=Jersey+10&family=Jersey+15&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">

</head>


<body onload="setupPagina('nav3')">

  <?php
    include 'header.html';
    include 'footer.html';
    include 'nav.html';
    include 'dbQuery.php';
  ?>

  <div id="container">

    <form name="stringaRicerca" method="POST">
		  <input id="nomeRicetta" name="nomeRicetta" type="text" placeholder="Inserisci il nome della riccetta"/>
      <select id="tipo" name="tipo">
        <option value="">(Tipologia Piatto)</option>
        <option value="antipasto">Antipasto</option>
        <option value="primo">Primo</option>
        <option value="secondo">Secondo</option>
        <option value="contorno">Contorno</option>
        <option value="dolce">Dolce</option>
      </select>
		  <input id="searchButton" type="submit" value="Search"/>
		</form>

    <div  id="risultati">

      <?php
	      $numero = "";
	      $titolo  = "";
        $tipo  = "";	
	      if(count($_POST)>0) {
		      $numero = $_POST["numero"];
		      $titolo = $_POST["nomeRicetta"];
          $tipo = $_POST["tipo"];
	      }	     
	      else if(count($_GET)>0) {
		      $numero = $_GET["numero"];
		      $titolo = $_GET["nomeRicetta"];
          $tipo = $_GET["tipo"];
	      }	     
	      
        if(!empty($_POST["regione"])){
          $query = getRicettaPerRegioneQry($_POST["regione"]);
        } else if(!empty($_GET["regione"])){
          $query = getRicettaPerRegioneQry($_GET["regione"]);
        } else if(!empty($_POST["codiceLibro"])){
          $query = getRicettePerLibro($_POST["codiceLibro"]);
        } else if(!empty($_GET["codiceLibro"])) {
          $query = getRicettePerLibro($_GET["codiceLibro"]);
        } else {
          $query = getRicettaQry($numero, $titolo, $tipo);
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
					<th>numero</th> 
					<!--th>id </th--> 
					<th>titolo ricetta</th> 
					<th>tipo ricetta</th>
          <th>titolo libro</th>
          <th># libri</th> 
				</tr>
      
        <?php
		      $i=0;
		      foreach($result as $riga) {
			    $i=$i+1;
			    $classRiga='class="rowOdd"';
			    if($i%2==0) {
			    	$classRiga='class="rowEven"';
			    }
			    $numero=$riga["numero"];
			    $titoloRicetta=$riga["titolo"];
			    $tipoRicetta=$riga["tipo"];
          $titoloLibro=$riga["titololibro"];
          $nLibri=$riga["nlibri"];
        ?>
      
        <tr <?php	echo $classRiga; ?> > 
			    <td > <?php echo $i; ?> </td>    						
				  <td > <?php echo $numero; ?> </td>    
				  <td > <?php echo formattaLinkRicetta1($numero, $titoloRicetta); ?> </td> 
				  <td > <?php echo $tipoRicetta; ?> </td>
          <td > <?php echo formattaLinkRicetta2($titoloLibro); ?> </td>
          <td > <?php echo $nLibri; ?> </td>
			  </tr>
      
        <?php } ?>
			</table>
    <?php }  ?>
    </div>
  </div>

</body>

</html>