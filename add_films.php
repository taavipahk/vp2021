<?php
	#server_host server_user server_pass
	$author_name="Taavi Pahk";
	require_once("../../config.php");
	require_once("fnc_film.php");
	$film_store_notice=null;
	#kas püütakse salvestada
	if(isset($_POST["film_submit"]))
		{
			#kontrollin, et andmeid sisestati
			if(!empty($_POST["title_input"]) and !empty($_POST["genre_input"]) and !empty($_POST["studio_input"]) and !empty($_POST["director_input"]) and !empty($_POST["year_input"]))
			{$film_store_notice=store_film($_POST["title_input"], $_POST["year_input"], $_POST["duration_input"], $_POST["genre_input"], $_POST["studio_input"], $_POST["director_input"]);}
			else {$film_store_notice="Osa andmeid on puudu!";}
		}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name; ?>, Veebiproge 21</title>
</head>
<body>
	<h1><?php echo $author_name; ?></h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<h2>Eesti filmide lisamine andmebaasi</h2>
	<form method="POST">
		<label for="title_input">Filmi pealkiri</label>
		<input type="text" name="title_input" id="title_input">
		<br>
		<label for="year_input">Aasta</label>
		<input type="number" name="year_input" id="year_input" min="1912" value="1960">
		<br>
		<label for="duration_input">Kestvus</label>
		<input type="number" name="duration_input" id="duration_input" min="1" value="60">
		<br>
		<label for="genre_input">Žanr</label>
		<input type="text" name="genre_input" id="genre_input">
		<br>
		<label for="studio_input">Tootja</label>
		<input type="text" name="studio_input" id="studio_input">
		<br>
		<label for="director_input">Režissöör</label>
		<input type="text" name="director_input" id="director_input">
		<br>
		<input type="submit" name="film_submit" value="salvesta">
	</form>
	<span><?php echo $film_store_notice; ?>
	
</body>
</html>