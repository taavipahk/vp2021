<?php
	#server_host server_user server_pass
	$author_name="Mida Iganes";
	require_once("../../config.php");
	require_once("fnc_general.php");
	require_once("fnc_profile.php");
	require("page_header.php");
	$description=null;
	$profile_store_notice=null;
	if($_SERVER["REQUEST_METHOD"]==="POST")
	{
		if(isset($_POST["profile_submit"]))
		{
			if(!empty($_POST["description_input"]))
			{
				$description=test_input((filter_var($_POST["description_input"],FILTER_SANITIZE_STRING)));
			}
			$_SESSION["bg_color"]=$_POST["bgcolor_input"];
			$_SESSION["text_color"]=$_POST["txtcolor_input"];
			$_SESSION["description"]=$description;
			$profile_store_notice=store_new_profile($_SESSION["user_id"],$_SESSION["description"],$_SESSION["bg_color"],$_SESSION["text_color"]);
			header("refresh:0.001");
		}
	}
	if(isset($_SESSION["user_id"]))
	{
		$description=$_SESSION["description"];
	}
?>
	<h2>Sisse logitud kui <?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?></h2>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<p><a href="home.php">Tagasi kodulehele</a></p>
	<hr>
	<h2>Kasutaja andmete muutmine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="description_input">Minu lühikirjeldus</label>
		<br>
		<textarea name="description_input" id="description_input" rows="10" cols="80" placeholder="Minu lühikirjeldus..."><?php echo $_SESSION["description"];?></textarea>
		<br>
		<label for="bgcolor_input">Taustavärv</label>
		<br>
		<input type="color" name="bgcolor_input" id="bgcolor_input" value="<?php echo $_SESSION["bg_color"]?>">
		<br>
		<label for="txtcolor_input">Tekstivärv</label>
		<br>
		<input type="color" name="txtcolor_input" id="txtcolor_input" value="<?php echo $_SESSION["text_color"]?>">
		<br>
		<input type="submit" name="profile_submit" value="salvesta">
	</form>
	<span><?php echo $profile_store_notice;?>
</body>
</html>