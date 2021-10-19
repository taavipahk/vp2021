<?php
	#server_host server_user server_pass
	$author_name="Mida Iganes";
	require_once("../../config.php");
	require_once("fnc_general.php");
	require_once("fnc_movie.php");
	require("page_header.php");
	$notice=null;
	$notice2=null;
	$role=null;
	$selected_person="";
	$selected_movie="";
	$selected_occup="";
	$selected_person_for_photo=null;
	$selected_movie2="";
	$selected_genre="";
	
	function storeExisting($input)
	{
		if(!empty($_POST[$input]))
		{
			return test_input(filter_var($_POST[$input],FILTER_SANITIZE_STRING));
		}	
	}
	
	if(isset($_POST["person_in_movie_submit"]))
		{
			$role=test_input((filter_var($_POST["role_input"],FILTER_SANITIZE_STRING)));
			$selected_person=storeExisting("person_input");
			$selected_movie=storeExisting("movie_input");
			$selected_occup=storeExisting("position_input");
			if(empty($selected_person) or empty($selected_movie) or empty($selected_occup))
			{
				$notice="Infot on puudu!";
			}else{
				$notice=save_relation($selected_person,$selected_movie,$selected_occup,$role);
			}
		}
	$photo_dir="movie_photos/";
	$file_type=null;
	$file_name=null;
	$picnotice=null;

	if(isset($_POST["person_photo_submit"]))
		{
		$image_check=getimagesize($_FILES["photo_input"]["tmp_name"]);
		if($image_check!==false)
		{
			if($image_check["mime"]=="image/jpeg")
			{
				$file_type="jpg";
			}
			if($image_check["mime"]=="image/png")
			{
				$file_type="png";
			}
			if($image_check["mime"]=="image/gif")
			{
				$file_type="gif";
			}
			#ajatempel ja failinime tegu
			$time_stamp=microtime(1)*10000;
			$file_name=$_POST["person_getting_photo_input"]."_".$time_stamp.".".$file_type;
			move_uploaded_file($_FILES["photo_input"]["tmp_name"],$photo_dir.$file_name);
			$picnotice=save_photo($file_name,$_POST["person_getting_photo_input"]);
		}else{$picnotice="Seda faililaiendit ei toetata";}
		}

	if(isset($_POST["movie_genre_submit"]))
		{
			$selected_movie2=storeExisting("movie_input2");
			$selected_genre=storeExisting("genre_input");
			if(empty($selected_movie2) or empty($selected_genre))
			{
				$notice2="Infot on puudu!";
			}else{
				$notice2=save_movgen($selected_movie2,$selected_genre);
			}
		}
?>
	<h2>Sisse logitud kui <?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?></h2>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<p><a href="home.php">Tagasi kodulehele</a></p>
	<hr>
	<h2>Filmi info seostamine</h2>
	<h3>Film, inimene ja tema roll</h3>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="person_input">Isik:</label>
		<select name="person_input">
		<option value="" selected disabled>Vali isik</option>
		<?php echo read_all_person($selected_person);?>
		</select>
		<br>
		<label for="movie_input">Film:</label>
		<select name="movie_input">
		<option value="" selected disabled>Vali film</option>
		<?php echo read_all_movie($selected_movie);?>
		</select>
		<br>
		<label for="position_input">Amet:</label>
		<select name="position_input">
		<option value="" selected disabled>Vali amet</option>
		<?php echo read_all_pos($selected_occup);?>
		</select>
		<br>
		<label for="role_input">Roll:</label>
		<input type="text" name="role_input" id="role_input" placeholder="Tegelase nimi" value="<?php echo $role; ?>">
		<br>
		<input type="submit" name="person_in_movie_submit" value="Sisesta">
		</form>
		<?php echo $notice;?>
	<h3>Näitleja foto lisamine</h3>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<select name="person_getting_photo_input">
		<option value="" selected disabled>Vali isik</option>
		<?php echo read_all_person($selected_person);?>
		</select>
		<br>
		<br>
		<label for="photo_input">Vali fail!</label>
		<br>
		<input type="file" name="photo_input" id="photo_input">
		<br>
		<br>
		<input type="submit" name="person_photo_submit" value="Lae pilt üles">
		</form>
		<span><?php echo $picnotice;?>
	<h3>Film ja žanr</h3>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="movie_input2">Film</label>
		<select name="movie_input2">
			<option value="" selected disabled>Vali film</option>
			<?php echo read_all_movie($selected_movie2);?>
		</select><br>
		<label for="genre_input">Žanr</label>
		<select name="genre_input">
			<option value="" selected disabled>Vali žanr</option>
			<?php echo read_all_genre($selected_genre);?>
		</select><br>
		<input type="submit" name="movie_genre_submit" value="Seo žanr filmiga">
		</form>
		<?php echo $notice2;?>
</body>
</html>