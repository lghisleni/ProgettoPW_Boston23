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


<body onload="setupPagina('nav4')">

  <?php
    include 'header.html';
    include 'footer.html';
    include 'nav.html';
    include 'dbQuery.php';

  ?>

  <div id="container">

    <form name="stringaRicerca" method="POST">
		  <input id="nomeLibro" name="nomeLibro" type="text" placeholder="Inserisci il nome del libro"/>
		  <input id="searchButton" type="submit" value="Search"/>
		</form>

    <div  id="risultati">

    <?php
	      $codice = "";
	      $titolo  = "";
        $anno  = "";	
	      if(count($_POST)>0) {
		      $codice = $_POST["codice"];
		      $titolo = $_POST["nomeLibro"];
          $anno = $_POST["anno"];
	      }	     
	      else if(count($_GET)>0) {
		      $codice = $_GET["codice"];
		      $titolo = $_GET["nomeLibro"];
          $anno = $_GET["anno"];
	      }	     
	      
        $query = getLibriQry($numero, $titolo, $tipo);
        echo "<p id=query><b><u>Libro Query</u></b>: " . formattaQuery($query) . "</p>";

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
					<th>codice ISBN</th> 
					<!--th>id </th--> 
					<th>titolo libro</th> 
					<th>anno di pubblicazione</th>
          <th># ricette</th>
          <th># pagine</th> 
				</tr>
      
        <?php
		      $i=0;
		      foreach($result as $riga) {
			    $i=$i+1;
			    $classRiga='class="rowOdd"';
			    if($i%2==0) {
			    	$classRiga='class="rowEven"';
			    }
			    $codice=$riga["codISBN"];
			    $titolo=$riga["titolo"];
			    $anno=$riga["anno"];
          $numeroRicette=$riga["nRicette"];
          $numeroPagine=$riga["nPagine"];
        ?>
      
        <tr <?php	echo $classRiga; ?> > 
			    <td > <?php echo $i; ?> </td>    						
				  <td > <?php echo $codice; ?> </td>    
				  <td > <?php echo $titolo; ?> </td> 
				  <td > <?php echo $anno; ?> </td>
          <td > <?php echo $numeroRicette; ?> </td>
          <td > <?php echo $numeroPagine; ?> </td>
			  </tr>
      
        <?php } ?>
			</table>
    <?php }  ?>
    </div>
  </div>

</body>

</html>