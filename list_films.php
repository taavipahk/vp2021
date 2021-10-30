<?php
	#server_host server_user server_pass
	$author_name="Mida Iganes";
	require_once("../../config.php");
	require_once("fnc_movie.php");
	$filmArr=[];
	$filmArr=read_all_films();
	require("page_header.php");

function min_to_hrsmin($integer)
{
	$calculation=$integer/60;
	if($calculation<1)
	{
		$str=$integer.'min';
	}else{
		$min=$integer%60;
		$hrs=($integer-$min)/60;
		if($min==0)
		{
			$str=$hrs.' h';
		}else{
			$str=$hrs.' h '.$min.' min';
		}
	}
	return $str;
}

?>
	<h1><?php echo $author_name; ?></h1>
	<h2>Sisse logitud kui <?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?></h2>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<p><a href="home.php">Tagasi kodulehele</a></p>
	<hr>
	<h2>Eesti filmid</h2>
	<p>
	<?php
	for($n=0;$n<=count($filmArr[0])-1;$n++)
	{
		echo "<b>Pealkiri:</b>   ".$filmArr[0][$n]."<br>";
		echo "<b>Valmimisaasta:</b>   ".$filmArr[1][$n]."<br>";
		echo "<b>Pikkus:</b>   ".min_to_hrsmin($filmArr[2][$n])."<br>";
		echo "<b>Kirjeldus:</b>   ".$filmArr[3][$n]."<br>";
		echo "<b>Žanr:</b>   ".$filmArr[4][$n]."<br>";
		echo "<b>Lavastaja:</b>   ".$filmArr[5][$n]." ".$filmArr[6][$n]."<br><br>";
	}
	?>
	<br>Mõned filmid, millel pole kirjeldust, žanri vms olemas, puuduvad siit.</br>
	</p>
</body>
</html>