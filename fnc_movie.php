<?php 
	$database="if21_taavi_pa";
	function read_all_person($selectedp)
	{
		$html=null;
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt=$conn->prepare("SELECT id, first_name, last_name, birth_date FROM person");
		$stmt->bind_result($id,$fname,$lname,$bdate);
		$stmt->execute();
		while($stmt->fetch())
		{
			$html.='<option value="'.$id.'"';
			if($selectedp==$id)
			{
				$html.=' selected';
			}
			$html.='>'.$fname.' '.$lname.' ('.$bdate.')</option>\n';
		}
		$stmt->close();
		$conn->close();
		return $html;
	}
	
	function read_all_movie($selectedm)
	{
		$html=null;
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt=$conn->prepare("SELECT id, title, production_year FROM movie");
		$stmt->bind_result($id,$title,$prodyear);
		$stmt->execute();
		while($stmt->fetch())
		{
			$html.='<option value="'.$id.'"';
			if($selectedm==$id)
			{
				$html.=' selected';
			}
			$html.='>'.$title.' ('.$prodyear.')</option>\n';
		}
		$stmt->close();
		$conn->close();
		return $html;
	}
	
	function read_all_pos($selectedo)
	{
		$html=null;
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt=$conn->prepare("SELECT id, position_name FROM position");
		$stmt->bind_result($id,$posname);
		$stmt->execute();
		while($stmt->fetch())
		{
			$html.='<option value="'.$id.'"';
			if($selectedo==$id)
			{
				$html.=' selected';
			}
			$html.='>'.$posname.'</option>\n';
		}
		$stmt->close();
		$conn->close();
		return $html;
	}

	function save_relation($person,$movie,$pos,$role)
	{
		if($pos==1 and (empty($role)))
		{
			$err="Näitleja puhul peab roll olema kirjas!";
			return $err;
		}
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$query="SELECT person_id,movie_id,position_id,role FROM person_in_movie WHERE person_id='".$person."' AND movie_id='".$movie."' AND position_id='".$pos."' AND role='".$role."'";
		$result=$conn->query($query);
		if($result->num_rows>0)
		{
			$result->close();
			$conn->close();
			$err="Selline seos on juba loodud!";
			return $err;
		}
		$stmt=$conn->prepare("INSERT INTO person_in_movie (person_id, movie_id, position_id, role) VALUES(?,?,?,?)");
		echo $conn->error;
		$stmt->bind_param("iiis", $person, $movie, $pos, $role);
		$notice="Tekkis viga seost luues:";
		if($stmt->execute()){
			$notice="Seos loodud";
		}
		else{$notice="Tekkis viga seost luues:".$stmt->error;}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function save_photo($photofile,$person)
	{
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt=$conn->prepare("INSERT INTO picture (picture_file_name,person_id) VALUES(?,?)");
		echo $conn->error;
		$stmt->bind_param("si",$photofile,$person);
		$notice="Tekkis viga fotot andmebaasi salvestades:";
		if($stmt->execute()){
			$notice="Foto salvestatud";
		}
		else{$notice="Tekkis viga fotot salvestades:".$stmt->error;}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function read_all_genre($selectedg)
	{
		$html=null;
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt=$conn->prepare("SELECT id, genre_name FROM genre");
		$stmt->bind_result($id,$genName);
		$stmt->execute();
		while($stmt->fetch())
		{
			$html.='<option value="'.$id.'"';
			if($selectedg==$id)
			{
				$html.=' selected';
			}
			$html.='>'.$genName.'</option>\n';
		}
		$stmt->close();
		$conn->close();
		return $html;
	}

	function save_movgen($movie,$genre)
	{
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$query="SELECT movie_id,genre_id FROM movie_genre WHERE movie_id='".$movie."' AND genre_id='".$genre."'";
		$result=$conn->query($query);
		if($result->num_rows>0)
		{
			$result->close();
			$conn->close();
			$err="Selle filmiga on see žanr juba seostatud!";
			return $err;
		}
		$stmt=$conn->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES(?,?)");
		echo $conn->error;
		$stmt->bind_param("ii", $movie, $genre);
		$notice="Tekkis viga seost luues:";
		if($stmt->execute()){
			$notice="Filmi ja žanri seos loodud";
		}
		else{$notice.=$stmt->error;}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function new_genre($name,$desc)
	{
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$query="SELECT genre_name,description FROM genre WHERE genre_name='".$name."' AND description='".$desc."'";
		$result=$conn->query($query);
		if($result->num_rows>0)
		{
			$result->close();
			$conn->close();
			$err="Selline žanr on olemas";
			return $err;
		}
		$stmt=$conn->prepare("INSERT INTO genre (genre_name, description) VALUES(?,?)");
		echo $conn->error;
		$stmt->bind_param("ss", $name, $desc);
		$notice="Tekkis viga žanri sisestades:";
		if($stmt->execute()){
			$notice="Uus žanr tehtud";
		}
		else{$notice.=$stmt->error;}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function new_movie($title,$yr,$dur,$desc)
	{
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$query="SELECT title,production_year,duration,description FROM movie WHERE title='".$title."' AND production_year='".$yr."' AND duration='".$dur."' AND description='".$desc."'";
		$result=$conn->query($query);
		if($result->num_rows>0)
		{
			$result->close();
			$conn->close();
			$err="Selline film on olemas";
			return $err;
		}
		$stmt=$conn->prepare("INSERT INTO movie (title,production_year,duration,description) VALUES(?,?,?,?)");
		echo $conn->error;
		$stmt->bind_param("siis", $title,$yr,$dur,$desc);
		$notice="Tekkis viga uut filmi sisestades:";
		if($stmt->execute()){
			$notice="Kirje filmi kohta tehtud";
		}
		else{$notice.=$stmt->error;}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function new_person($fname,$lname,$bday)
	{
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$query="SELECT first_name,last_name,birth_date FROM person WHERE first_name='".$fname."' AND last_name='".$lname."' AND birth_date='".$bday."'";
		$result=$conn->query($query);
		if($result->num_rows>0)
		{
			$result->close();
			$conn->close();
			$err="Selline inimene on olemas";
			return $err;
		}
		$stmt=$conn->prepare("INSERT INTO person (first_name,last_name,birth_date) VALUES(?,?,?)");
		echo $conn->error;
		$stmt->bind_param("sss", $fname,$lname,$bday);
		$notice="Tekkis viga uut inimest sisestades:";
		if($stmt->execute()){
			$notice="Kirje inimese kohta tehtud";
		}
		else{$notice.=$stmt->error;}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function new_company($cname,$addy)
	{
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$query="SELECT company_name,company_address FROM production_company WHERE company_name='".$cname."' AND company_address='".$addy."'";
		$result=$conn->query($query);
		if($result->num_rows>0)
		{
			$result->close();
			$conn->close();
			$err="Selline firma on juba olemas";
			return $err;
		}
		$stmt=$conn->prepare("INSERT INTO production_company (company_name,company_address) VALUES(?,?)");
		echo $conn->error;
		$stmt->bind_param("ss", $cname,$addy);
		$notice="Tekkis viga uut firmat sisestades:";
		if($stmt->execute()){
			$notice="Kirje firma kohta tehtud";
		}
		else{$notice.=$stmt->error;}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function get_people()
	{
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt=$conn->prepare("SELECT p.first_name,p.last_name,p.birth_date FROM person p");
		echo $conn->error;
		$stmt->bind_result($fname,$lname,$bday);
		$stmt->execute();
		$fnames=[];
		$lnames=[];
		$bdays=[];
		while($stmt->fetch())
		{
			array_push($fnames, $fname);
			array_push($lnames, $lname);
			array_push($bdays, $bday);
		}
		$stmt->close();
		$conn->close();
		$arr=[$fnames,$lnames,$bdays];
		return $arr;
	} 

	function read_all_films()
	{
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt=$conn->prepare("SELECT m.title,m.production_year,m.duration,m.description,g.genre_name,p.first_name,p.last_name FROM movie m JOIN movie_genre AS mg ON m.id=mg.movie_id JOIN genre AS g ON mg.genre_id=g.id JOIN person_in_movie AS pim ON m.id=pim.movie_id JOIN person AS p ON pim.person_id=p.id WHERE pim.position_id='2'");
		echo $conn->error;
		$stmt->bind_result($title,$yr,$dur,$desc,$gen,$fname,$lname);
		$stmt->execute();
		$titles=[];
		$prodyrs=[];
		$durs=[];
		$descs=[];
		$gens=[];
		$fnames=[];
		$lnames=[];
		while($stmt->fetch())
			{
				array_push($titles, $title);
				array_push($prodyrs, $yr);
				array_push($durs, $dur);
				array_push($descs, $desc);
				array_push($gens, $gen);
				array_push($fnames, $fname);
				array_push($lnames, $lname);
			}
		$stmt->close();
		$conn->close();
		$arr=[$titles, $prodyrs, $durs, $descs, $gens, $fnames, $lnames];
		return $arr;
	}

	#OLD SHIT
#	function store_film($title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input)
#	{
#		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
#		$conn->set_charset("utf8");
#		$stmt=$conn->prepare("INSERT INTO film (pealkiri,aasta,kestus,zanr,tootja,lavastaja) values(?,?,?,?,?,?)");
#		echo $conn->error;
#		#seon sql käsu päris andmetega i:integer:täisarv  d:decimal:murdarv  s:string:tekst
#		$stmt->bind_param("siisss",$title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input);
#		$success=null;
#		if($stmt->execute())
#		{
#			$success="Salvestamine õnnestus!";
#		}
#		else{$success="Tekkis viga! ".$stmt->error;}
#		return $success;
#		$stmt->close();
#		$conn->close();
#		
#	}
#?>