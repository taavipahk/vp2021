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
	$film_store_notice=null;
	$err_tit=null;
	$err_gen=null;
	$err_stu=null;
	$err_dir=null;
	
	
	function test_input($data) 
		{
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
		}
	
	$saved_tit=null;
	$saved_yea="1960";
	$saved_dur="60";
	$saved_gen=null;
	$saved_stu=null;
	$saved_dir=null;
	function storeExisting($input)
	{
		if(!empty($_POST[$input]))
		{
			return test_input(filter_var($_POST[$input],FILTER_SANITIZE_STRING));
		}	
	}
	
	function errPlacer($missingInf,$errVar,$errText)
	{
		
		if(empty($_POST[$missingInf]))
		{	
			$errVar="Sisesta ".$errText."!";
			return $errVar;
		}
	}
	#kas püütakse salvestada
	if(isset($_POST["film_submit"]))
		{
			$saved_tit=storeExisting("title_input");
			$saved_gen=storeExisting("genre_input");
			$saved_yea=storeExisting("year_input");
			$saved_dur=storeExisting("duration_input");
			$saved_stu=storeExisting("studio_input");
			$saved_dir=storeExisting("director_input");
			#kontrollin, et andmeid sisestati
			if(!empty($saved_tit) and !empty($saved_gen) and !empty($saved_stu) and !empty($saved_dir) and !empty($_POST["year_input"]))
			{$film_store_notice=store_film($saved_tit, $_POST["year_input"], $_POST["duration_input"], $saved_gen, $saved_stu, $saved_dir);}
			else {
				$err_tit=errPlacer("title_input",$err_tit,"pealkiri");
				$err_gen=errPlacer("genre_input",$err_gen,"žanr");
				$err_stu=errPlacer("studio_input",$err_stu,"tootja nimi");
				$err_dir=errPlacer("director_input",$err_dir,"režissööri nimi");
				$film_store_notice="Osa andmeid on puudu!";
				}
		}
	#1) DONE
	#2) DONE
	#3) Kinda done??
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
	<h2>Eesti filmide lisamine andmebaasi</h2>
	<form method="POST">
		<label for="title_input">Filmi pealkiri</label>
		<input type="text" name="title_input" id="title_input" value="<?php echo $saved_tit;?>"><?php echo $err_tit;?>
		<br>
		<label for="year_input">Aasta</label>
		<input type="number" name="year_input" id="year_input" min="1912" value="<?php echo $saved_yea;?>">
		<br>
		<label for="duration_input">Kestvus (min)</label>
		<input type="number" name="duration_input" id="duration_input" min="1" value="<?php echo $saved_dur;?>">
		<br>
		<label for="genre_input">Žanr</label>
		<input type="text" name="genre_input" id="genre_input" value="<?php echo $saved_gen;?>"><?php echo $err_gen;?>
		<br>
		<label for="studio_input">Tootja</label>
		<input type="text" name="studio_input" id="studio_input" value="<?php echo $saved_stu;?>"><?php echo $err_stu;?>
		<br>
		<label for="director_input">Režissöör</label>
		<input type="text" name="director_input" id="director_input" value="<?php echo $saved_dir;?>"><?php echo $err_dir;?>
		<br>
		<input type="submit" name="film_submit" value="salvesta">
	</form>
	<span><?php echo $film_store_notice; echo $saved_tit;?>
	
</body>
</html>