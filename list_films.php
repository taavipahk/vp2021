<?php
	#server_host server_user server_pass
	session_start();
	if(!isset($_SESSION["user_id"]))
	{
		header("Location: home.php");
	}
	$author_name="Mida Iganes";
	require_once("../../config.php");
	require_once("fnc_film.php");
	$films_html=null;
	$films_html=read_all_films();
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name; ?>, Veebiproge 21</title>
</head>
<body>
	<h1><?php echo $author_name; ?></h1>
	<h2>Sisse logitud kui <?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?></h2>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<h2>Eesti filmid</h2>
	<?php echo $films_html ?>
</body>
</html>