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
    include 'nav.html'
  ?>

  <div id="container">

    <form name="stringaRicerca" method="POST">
		  <input id="nomeLibro" name="nomeLibro" type="text" placeholder="Inserisci il nome del libro"/>
		  <input id="searchButton" type="submit" value="Search"/>
		</form>

  </div>

</body>

</html>