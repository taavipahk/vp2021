<?php 
	$database="if21_taavi_pa";
	function read_all_films()
	{
		#avan andmebaasiuhenduse server,kasutaja,parool,andmebaas
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		#määrame vajaliku kodeeringu
		$conn->set_charset("utf8");
		$stmt=$conn->prepare("SELECT * FROM film");
		#kui on vigu, igaksjuhuks valjastame need
		echo $conn->error;
		#seome tulemused muutujatega
		#f-from
		$stmt->bind_result($title_f_db,$year_f_db,$duration_f_db,$genre_f_db,$studio_f_db,$director_f_db);
		$stmt->execute();
		#fetch()
		#<h3>Pealkiri<ul><li></li></h3>
		$films_html=null;
		while($stmt->fetch())
			{
			$films_html.="<h3>".$title_f_db."</h3>\n";
			$films_html.="<ul>\n";
			$films_html.="<li>Valmimisaasta:".$year_f_db."</li>\n";
			$films_html.="<li>Kestus:".$duration_f_db." min</li>\n";
			$films_html.="<li>Žanr:".$genre_f_db."</li>\n";
			$films_html.="<li>Tootja:".$studio_f_db."</li>\n";
			$films_html.="<li>Lavastaja:".$director_f_db."</li>\n";
			$films_html.="</ul>\n";
			}
			#sulgeme käsu
		$stmt->close();
		$conn->close();
		return $films_html;
	}
	function store_film($title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input)
	{
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt=$conn->prepare("INSERT INTO film (pealkiri,aasta,kestus,zanr,tootja,lavastaja) values(?,?,?,?,?,?)");
		echo $conn->error;
		#seon sql käsu päris andmetega i:integer:täisarv  d:decimal:murdarv  s:string:tekst
		$stmt->bind_param("siisss",$title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input);
		$success=null;
		if($stmt->execute())
		{
			$success="Salvestamine õnnestus!";
		}
		else{$success="Tekkis viga! ".$stmt->error;}
		return $success;
		$stmt->close();
		$conn->close();
		
	}
?>