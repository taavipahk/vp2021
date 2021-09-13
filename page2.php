<?php
	$author_name="Taavi Pahk";
	$todays_evaluation=null;
	$inserted_adj=null;
	$adj_error=null;
	//meatball < > == <= >= !=;
	//submit nupu vajutamise kontroll
	if(isset($_POST["todays_adj_input"]))
	{if(!empty($_POST["adjective_input"]))
	{$todays_evaluation="<p>Tänane päev on <strong>".$_POST["adjective_input"]."</strong>.</p><hr>";
	$inserted_adj=$_POST["adjective_input"];
	}
	else {$adj_error="palun kirjuta tänase päeva kohta omadussõna";}
	}
	$photo_dir="photos/";
	$allowed_photo_types=["image/jpeg","image/png"];
	$photo_files=[];
	$all_files=array_slice(scandir($photo_dir),2);
	$file="juust";
	$file_info="juust";
	foreach($all_files as $file)
	{$file_info=getimagesize($photo_dir.$file);
	if(isset($file_info["mime"]))
	{if(in_array($file_info["mime"],$allowed_photo_types))
		{array_push($photo_files,$file);
	}}}
	$limit=count($photo_files);
	$pic_num=mt_rand(0,$limit-1);
	$pic_file=$photo_files[$pic_num];
	$pic_html='<img src="'.$photo_dir.$pic_file.'" alt="Tallinna Ülikool">';
	//; on eraldi kaskude jaoks {}-s ja {} tootab ;-na i guess
	//var_dump($all_files); to display all da crap in dat folder
	//<p>valida on järgmised fotod: <strong>f1.jpg</strong> jne.
	$list_html="<ul>";
	for($i=0; $i<$limit; $i++)
	{$list_html.="<li>".$photo_files[$i]."</li>";}
	$list_html.="</ul>";
	$photo_select_html='<select name="photo_select">'."\n";
	for($i=0; $i<$limit; $i++)
	{$photo_select_html.='<option value="'.$i.'">'.$photo_files[$i]."</option> \n";}
	$photo_select_html.="</select>\n";
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name; ?>, Veebiproge 21</title>
</head>
<body>
	<h1><?php echo $author_name; ?></h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<form method="POST">
		<input type="text" name="adjective_input" placeholder="omadussõna tänase kohta" value="<?php echo $inserted_adj;?>">
		<input type="submit" name="todays_adj_input" value="Salvesta">
		<span><?php echo $adj_error ?>
	</form>
	<hr>
	<?php echo $todays_evaluation;?>
	<form method="POST"><?php echo $photo_select_html; ?>
	<input type="submit" name="pic_choice" value="Vali pilt">
	</form>
	<?php echo $pic_html;
	// hw: pic_html peab olema kasutaja valik, kui ta valib midagi. muidu random
	echo $list_html;?>
</body>
</html>