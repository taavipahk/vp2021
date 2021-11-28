<?php
#server_host server_user server_pass
$author_name = "Mida Iganes";
$to_head = '<script src="javascript/checkFileSize.js" defer></script>'."\n";
require_once("../../config.php");
require_once("fnc_general.php");
require_once("fnc_photo_upload.php");
require_once("classes/Photoupload.class.php");

$picnotice = null;
$alt_text = null;
$privacy = 1;
$GLOBALS["orig_dir"] = "upload_photos_orig/";
$GLOBALS["norm_dir"] = "upload_photos_normal/";
$GLOBALS["thumb_dir"] = "upload_photos_thumb/";
$file_type = null;
$file_name = null;
$temp = null;
$photo_file_prefix = "vp_";
$photo_size_limit = 1024 * 1024; #1 mb
$normal_photo_max_height=400;
$normal_photo_max_width=600;
$thumbnail_height=100;
$thumbnail_width=100;
$photo_upload_notice="";
$classErr=False;
$watermark_file="pics/vp_logo_color_w100_overlay.png";
#private omadust ei saa echoda

function storeExisting($input)
{
	if (!empty($_POST[$input])) {
		return test_input(filter_var($_POST[$input], FILTER_SANITIZE_STRING));
	}
}


if (isset($_POST["photo_submit"])) {
	$privacy = $_POST["privacy_input"];
	if (isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])) {
		if(isset($_POST["alt_input"]) and !empty($_POST["alt_input"])){
			$alt_text = test_input(filter_var($_POST["alt_input"], FILTER_SANITIZE_STRING));
			} else { $picnotice="Alternatiivtekst on puudu!"; return; }
		} else {
			$picnotice = "Pole faili";
			return;
		}

		#võtame kasutusele klassi, kuni klass failitüüpi ise kindlaks ei tee, siis anname filetype ka
                $photo_upload = new Photoupload($_FILES["photo_input"]);
                //loome uue pikslikogumi
                //$my_new_temp_image = resize_photo($my_temp_image, $normal_photo_max_width, $normal_photo_max_height);
                $photo_upload->resize_photo($normal_photo_max_width, $normal_photo_max_height);
                
                //lisan vesimärgi
				//add_watermark($my_new_temp_image, $watermark_file);
                $photo_upload->add_watermark($watermark_file);
                
				$file_name = $photo_upload->make_fname();
                //salvestan
                //$photo_upload_notice = "Vähendatud pildi " .save_image($my_new_temp_image, $file_type, $photo_normal_upload_dir .$file_name);
                $photo_upload_notice = "Vähendatud pildi " .$photo_upload->save_image($GLOBALS["norm_dir"] .$file_name);
				
				//teen pisipildi
                $photo_upload->resize_photo($thumbnail_width, $thumbnail_height);
				//$my_new_temp_image = resize_photo($my_temp_image, $thumbnail_width, $thumbnail_height, false);
                $photo_upload_notice .= " Pisipildi " .$photo_upload->save_image($GLOBALS["thumb_dir"] .$file_name);
                //imagedestroy($my_new_temp_image);
                
                //imagedestroy($my_temp_image);
                $classErr = $photo_upload->err;
                unset($photo_upload);
		if (move_uploaded_file($_FILES["photo_input"]["tmp_name"], $GLOBALS["orig_dir"] . $file_name)) {
			$picnotice .= "Originaalfoto laeti üles";
		} else {
			$picnotice .= "Originaalfoto üleslaadimine ebaõnnestus";
		}
		$picnotice.="<br>".img_to_db($_SESSION["user_id"], $file_name, $alt_text, $privacy);
	}

	require("page_header.php");

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
	<input type="submit" name="photo_submit" id="photo_submit" value="Lae pilt üles">
	<span id="notice"></span>
</form>
<span><?php echo $picnotice; echo $photo_upload_notice;
echo "<br>";
if($classErr==True)
{
	echo "Kuskil tekkis tõrge!";
} else {
	echo "Tõrkeid ei tekkinud.";
}?>
	<hr>
</span>
</body>

</html>