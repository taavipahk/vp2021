<?php
#image___ funktsioonid teevad antud pildist selles tüübis pildi
#$img on pilt, $filetype on laiend, $target on kuhu fail salvestada

function img_to_db($uid,$fname,$alttxt,$priv)
{
	$database="if21_taavi_pa";
	$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$database);
	$conn->set_charset("utf8");
	$stmt=$conn->prepare("INSERT INTO vp_photos (userid, filename, alttext, privacy) VALUES(?,?,?,?)");
		echo $conn->error;
		$stmt->bind_param("issi", $uid,$fname,$alttxt,$priv);
		if($stmt->execute()){
			$notice="Pilt üles laetud!";
		}
		else{$notice="Tekkis viga üleslaadimisel: ".$stmt->error;}
		$stmt->close();
		$conn->close();
		return $notice;
}
?>