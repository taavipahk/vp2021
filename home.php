<?php
	//alustame sessiooni
	session_start();
	
	if(!isset($_SESSION["user_id"]))
	{
		header("Location: page.php");
	}
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?>, Veebiproge 21</title>
</head>
<body>
	<h1><?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?></h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<ul>
	<li><a href="?logout=1">Logi välja</a></li>
	<p>Ligipääsetav ainult sisselogitud kasutajale:</p>
	<li><a href="add_films.php">Filmide lisamine</a></li>
	<li><a href="list_films.php">Filmide tabel</a></li>
	</ul>
</body>
</html>