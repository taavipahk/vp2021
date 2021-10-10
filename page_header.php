<?php
	//alustame sessiooni
	session_start();
	
	if(!isset($_SESSION["user_id"]))
	{
		header("Location: page.php");
	}
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	}
	$css_color=null;
	$css_color.="<style>\n";
	$css_color.="body{";
	$css_color.="\nbackground-color:".$_SESSION["bg_color"].";\ncolor:".$_SESSION["text_color"].";\n}</style>";
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?>, Veebiproge 21</title>
	<?php echo $css_color;?>
</head>
<body>
<img src="pics/vp_banner.png" alt="veebiprogrammeerimise lehe bänner">