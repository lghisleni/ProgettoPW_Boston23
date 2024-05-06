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
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;700;900&family=Jersey+10&family=Jersey+15&display=swap" rel="stylesheet">

</head>


<body onload="setupPagina('nav2')">

  <?php
    include 'header.html';
    include 'footer.html';
    include 'nav.html';
    include 'dbQuery.php';
  ?>

  <div id="container">

    <form name="stringaRicerca" method="POST">
		<input id="nome" name="nome" type="text" placeholder="Inserisci il nome della regione"/>
		<input id="searchButton" type="submit" value="Search"/>
	</form>

    <div  id="risultati">

      <?php
	      $cod = "";
	      $nome  = "";	
	      if(count($_POST)>0) {
		      $cod = $_POST["cod"];
		      $nome = $_POST["nome"];
	      }	     
	      else if(count($_GET)>0) {
		      $cod = $_GET["cod"];
		      $nome = $_GET["nome"];
	      }	     
	      
        $query = getRegioneQry($cod, $nome);
        echo "<p id=query><b><u>Regione Query</u></b>: " . formattaQuery($query) . "</p>";

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
					<th>Codice</th> 
					<!--th>id </th--> 
					<th>Nome</th> 
					<th>NumeroRicette</th> 
				</tr>
      
        <?php
		      $i=0;
		      foreach($result as $riga) {
			    $i=$i+1;
			    $classRiga='class="rowOdd"';
			    if($i%2==0) {
			    	$classRiga='class="rowEven"';
			    }
			    $cod=$riga["cod"];
			    $nome=$riga["nome"];
			    $n=$riga["nRicette"];
        ?>
      
        <tr <?php	echo $classRiga; ?> > 
			    <td > <?php echo $i; ?> </td>    						
				  <td > <?php echo $cod; ?> </td>    
				  <td > <?php echo $nome; ?> </td> 
				  <td > <?php echo $n; ?> </td> 
			  </tr>
      
        <?php } ?>
			</table>
    <?php }  ?>
    
    </div>
  </div>

</body>

</html>