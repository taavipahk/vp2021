<?php
#server_host server_user server_pass
$author_name = "Mida Iganes";
require_once("../../config.php");
require_once("fnc_general.php");
require_once("fnc_photo_upload.php");
require("page_header.php");
$picnotice = null;
$alttxt = null;
$privacy = 1;
$photo_orig_upload_dir = "upload_photos_orig/";
$photo_norm_upload_dir = "upload_photos_normal/";
$photo_thumb_upload_dir = "upload_photos_thumb/";
$file_type = null;
$file_name = null;
$temp = null;
$photo_file_prefix = "vp_";
$photo_size_limit = 1024 * 1024; #1 mb

function storeExisting($input)
{
	if (!empty($_POST[$input])) {
		return test_input(filter_var($_POST[$input], FILTER_SANITIZE_STRING));
	}
}


if (isset($_POST["photo_submit"])) {
	$privacy = $_POST["privacy_input"];
	if (isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])) {
		$temp=img_filetype($_FILES["photo_input"]["tmp_name"], $_FILES["photo_input"]["size"], $photo_size_limit);
		if($temp!=="png" and $temp!=="jpg" and $temp!=="gif")
		{
			$picnotice=$temp;
		} else {
			$file_type=$temp;
		}
		$temp=null;
		if(isset($_POST["alt_input"]) and !empty($_POST["alt_input"])){
			$alt_text = test_input(filter_var($_POST["alt_input"], FILTER_SANITIZE_STRING));
			if(empty($alt_text)){
				$photo_error .= "Alternatiivtekst on lisamata!";
			}
		}

		#filename
		$time_stamp = microtime(1) * 10000;
		$file_name = $photo_file_prefix . $time_stamp . '.' . $file_type;

		$new_thumbimg=resize_image($_FILES["photo_input"]["tmp_name"], $file_type, "thumb");
		$picnotice = save_image($new_thumbimg, $file_type, $photo_thumb_upload_dir.$file_name, "Pöidla");
		imagedestroy($new_thumbimg);
		$new_normimg=resize_image($_FILES["photo_input"]["tmp_name"], $file_type, "norm");
		$picnotice.= save_image($new_normimg, $file_type, $photo_norm_upload_dir.$file_name, "Vähendatud ");
		imagedestroy($new_normimg);

		if (move_uploaded_file($_FILES["photo_input"]["tmp_name"], $photo_orig_upload_dir . $file_name)) {
			$picnotice .= "Originaalfoto laeti üles";
		} else {
			$picnotice .= "Originaalfoto üleslaadimine ebaõnnestus";
		}
		$picnotice.="<br>".img_to_db($_SESSION["user_id"], $file_name, $alt_text, $privacy);
	} else {
		$picnotice = "Pole faili";
	}
}
?>
<h2>Sisse logitud kui <?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"]; ?></h2>
<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu.</p>
<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
<hr>
<p><a href="home.php">Tagasi kodulehele</a></p>
<hr>
<h3>Foto üles laadimine</h3>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
	<label for="photo_input">Vali fail!</label>
	<br>
	<input type="file" name="photo_input" id="photo_input">
	<br>
	<label for="alt_input">Alternatiivtekst (Tekst, mida näidatakse, kui pilt ära ei lae)</label><br>
	<input type="text" name="alt_input" id="alt_input" placeholder="Sisesta" rows="10">
	<br>
	<input type="radio" name="privacy_input" id="privacy_input_1" value="1" <?php if ($privacy == 1) {
																				echo " checked";
																			} ?>>
	<label for="privacy_input_1">Privaatne (ainult mina näen)</label>
	<br>
	<input type="radio" name="privacy_input" id="privacy_input_2" value="2" <?php if ($privacy == 2) {
																				echo " checked";
																			} ?>>
	<label for="privacy_input_2">Sisseloginud kasutajad näevad</label>
	<br>
	<input type="radio" name="privacy_input" id="privacy_input_3" value="3" <?php if ($privacy == 3) {
																				echo " checked";
																			} ?>>
	<label for="privacy_input_3">Avalik</label>
	<br>
	<input type="submit" name="photo_submit" value="Lae pilt üles">
</form>
<span><?php echo $picnotice; ?>
	<hr>
</span>
</body>

</html>