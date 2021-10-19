<?php
	$author_name="Mida Iganes";
	require_once("../../config.php");
	require_once("fnc_general.php");
	require_once("fnc_movie.php");
	require("page_header.php");

	$clicked="";
	$formHtml="";
	if(isset($_POST["option_submit"]))
	{
		$clicked=$_POST["option_input"];
		$formHtml='<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">';
		$formHtml.=getInfo($clicked);
		$formHtml.='<input type="submit" name="new_'.$clicked.'_submit" value="Sisesta"></form>';
	}

function sanitize($string)
{
	$sanitized=test_input(filter_var($string,FILTER_SANITIZE_STRING));
	return $sanitized;
}

#genre - genre_name, description
#movie - title, production_year, duration, description
#person - first_name, last_name, birth_date
#production_company - company_name, company_address

function getInfo($val)
{
	$html='';
    if($val=="")
	{
		$notice="Lahtrit ei saa tühjaks jätta";
		return $notice;
	}
	elseif($val=="genre")
	{
		$html='
		<h3>Žanr</h3>
		<input type="text" name="genre_input" id="genre_input" placeholder="Žanri nimi" value=""><br>
		<input type="text" name="gdesc_input" id="gdesc_input" placeholder="Žanri kirjeldus" value=""><br>
		';
	}
	elseif($val=="movie")
	{
		$html='
		<h3>Film</h3>
		<input type="text" name="title_input" id="title_input" placeholder="Filmi pealkiri" value=""><br>
		<label for="year_input">Ilmumisaasta</label><br>
		<input type="number" name="year_input" id="year_input" min="1912" value=""><br>
		<label for="dur_input">Filmi pikkus</label><br>
		<input type="number" name="dur_input" id="dur_input" min="0" max="300" value=""><br>
		<input type="text" name="mdesc_input" id="mdesc_input" placeholder="Filmi kirjeldus" value=""><br>
		';
	}
	elseif($val=="person")
	{
		$html='
		<h3>Inimene</h3>
		<input type="text" name="fname_input" id="fname_input" placeholder="Eesnimi" value=""><br>
		<input type="text" name="lname_input" id="lname_input" placeholder="Perenimi" value=""><br>
		<label for="bday_input">Sünnipäev</label><br>
		<input type="date" name="bday_input" id="bday_input" value=""><br>
		';
	}
	else
	{
		$html='
		<h3>Tootjafirma</h3>
		<input type="text" name="cname_input" id="cname_input" placeholder="Firma nimi" value=""><br>
		<input type="text" name="address_input" id="address_input" placeholder="Firma aadress" value=""><br>
		';
	}
	return $html;
}

$notice="";
	if(isset($_POST["new_genre_submit"]))
	{
		if(!empty($_POST["genre_input"]) and !empty($_POST["gdesc_input"]))
		{
			$notice=new_genre(sanitize($_POST["genre_input"]),sanitize($_POST["gdesc_input"]));
		}else{
			$notice="Infot puudu";
		}
	}
	elseif(isset($_POST["new_movie_submit"]))
	{
		if(!empty($_POST["title_input"]) and !empty($_POST["year_input"]) and !empty($_POST["dur_input"]) and !empty($_POST["mdesc_input"]))
		{
			$notice=new_movie(sanitize($_POST["title_input"]),$_POST["year_input"],$_POST["dur_input"],sanitize($_POST["mdesc_input"]));
		}else{
			$notice="Infot puudu";
		}
	}
	elseif(isset($_POST["new_person_submit"]))
	{
		if(!empty($_POST["fname_input"]) and !empty($_POST["lname_input"]) and !empty($_POST["bday_input"]))
		{
			$notice=new_person(sanitize($_POST["fname_input"]),sanitize($_POST["lname_input"]),$_POST["bday_input"]);
		}else{
			$notice="Infot puudu";
		}
	}
	elseif(isset($_POST["new_prod_company_submit"]))
	{
		if(!empty($_POST["cname_input"]) and !empty($_POST["address_input"]))
		{
			$notice=new_company(sanitize($_POST["cname_input"]),sanitize($_POST["address_input"]));
		}else{
			$notice="Infot puudu";
		}
	}

?>
<hr>
<p><a href="home.php">Tagasi kodulehele</a></p>
<hr>
<h2>Vali uus info, mida tahad sisestada</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<select name="option_input">
		<option value="" selected disabled>Vali siit midagi..</option>
		<option value="genre">Žanr</option>
		<option value="movie">Film</option>
		<option value="person">Inimene</option>
		<option value="prod_company">Tootjafirma</option>
	</select>
	<input type="submit" name="option_submit" value="Vali">
</form>
<?php echo $formHtml; echo $notice; ?>
</body>
</html>