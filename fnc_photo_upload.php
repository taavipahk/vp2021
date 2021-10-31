<?php
#image___ funktsioonid teevad antud pildist selles tüübis pildi
#$img on pilt, $filetype on laiend, $target on kuhu fail salvestada
function save_image($img,$filetype,$target,$format)
{
	$notice=null;
	if($filetype=="jpg")
	{
		if(imagejpeg($img, $target, 90))
		{
			$notice=$format."pildi salvestamine õnnestus!<br>";
		}else{
			$notice="Tekkis tõrge!<br>";
		}
	}
	if($filetype=="png")
	{
		if(imagepng($img, $target, 6))
		{
			$notice=$format."pildi salvestamine õnnestus!<br>";
		}else{
			$notice="Tekkis tõrge!<br>";
		}
	}
	if($filetype=="gif")
	{
		if(imagegif($img,$target))
		{
			$notice=$format."pildi salvestamine õnnestus!<br>";
		}else{
			$notice="Tekkis tõrge!<br>";
		}
	}
	return $notice;
}

function resize_image($img,$filetype,$format)
{
	if($format=="thumb"){
		$edited_max_w=100;
		$edited_max_h=100;
	}
	if($format=="norm"){
		$edited_max_w=600;
		$edited_max_h=400;
		$watermark_file="pics/vp_logo_color_w100_overlay.png";
	}
	$tempimg=null;
	if ($filetype == "jpg") {
		$tempimg = imagecreatefromjpeg($img);
	} elseif ($filetype == "png") {
		$tempimg = imagecreatefrompng($img);
	} elseif ($filetype == "gif") {
		$tempimg = imagecreatefromgif($img);
	}
	$image_w = imagesx($tempimg);
	$image_h = imagesy($tempimg);
	if ($image_w / $edited_max_w > $image_h / $edited_max_h) {
		$photo_ratio = $image_w / $edited_max_w;
	} else {
		$photo_ratio = $image_h / $edited_max_h;
	}
	if($format=="norm")
	{
		$new_w = round($image_w / $photo_ratio);
		$new_h = round($image_h / $photo_ratio);
		$src_x = 0;
		$src_y = 0;
	} elseif($format=="thumb") {
		$new_w=100;
		$new_h=100;
		if($image_w>$image_h)
		{
			$src_x=($image_w/2)-($image_h/2);
			$src_y=0;
			$image_w=$image_h;
		}
		elseif($image_h>$image_w)
		{
			$src_x=0;
			$src_y=($image_h/2)-($image_w/2);
			$image_h=$image_w;
		}
		elseif($image_h==$image_w) {
			$src_x=0 ;
			$src_y=0 ;
		}
	}
	$new_tempimg = imagecreatetruecolor($new_w, $new_h);
	imagecopyresampled($new_tempimg, $tempimg, 0, 0, $src_x, $src_y, $new_w, $new_h, $image_w, $image_h);
	if($format=="norm")
	{
		$watermark = imagecreatefrompng($watermark_file);
		$watermark_w = imagesx($watermark);
		$watermark_h = imagesy($watermark);
		$watermark_x = $new_w - $watermark_w - 10;
		$watermark_y = $new_h - $watermark_h - 10;
		imagecopy($new_tempimg, $watermark, $watermark_x, $watermark_y, 0, 0, $watermark_w, $watermark_h);
		imagedestroy($watermark);
	}
	imagedestroy($tempimg);
	return $new_tempimg;
}

function img_filetype($img_file, $img_size, $size_lim)
{
	$data_arr = getimagesize($img_file);
	# array(6)
	# [0]=> int(244) [1]=> int(232) [2]=> int(3)
	# [3]=> string(24) "width="244" height="232""
	# ["bits"]=> int(8) ["mime"]=> string(9) "image/png"
	if ($data_arr !== false) {
		if ($data_arr["mime"] == "image/jpeg") {
			$ftype = "jpg";
		} elseif ($data_arr["mime"] == "image/png") {
			$ftype = "png";
		} elseif ($data_arr["mime"] == "image/gif") {
			$ftype = "gif";
		} else {
			$picnotice = "Valitud pilt ei ole sobivas laiendis";
			return $picnotice;
		}
		if ($img_size > $size_lim) {
			$picnotice = "Valitud fail liiga suur (üle 1mb)";
			return $picnotice;
		}
	} else {
		$picnotice = "Pole pilt!";
		return $picnotice;
	}
	return $ftype;
}

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