<?php
	$weekday_names_et=["esmaspäev","teisipäev","kolmapäev","neljapäev","reede","laupäev","pühapäev"];
	$day_phases_et=["on uneaeg","toimuvad tunnid","tunde ei toimu","on puhkeaeg"];
	$author_name="Taavi Pahk";
	$time_now=date("d.m.Y H:i:s");
	$hour_now=date("H");
	$day_phase_n="100";
	//meatball < > == <= >= !=;
	$weekday_now=date("N");
	$day_category="ebamäärane";
	if($weekday_now<=5)
	{$day_category="koolipäev";}
	else {$day_category="puhkepäev";}
	if($hour_now>=22 && $hour_now<7)
	{$day_phase_n=0;}
	elseif($hour_now>=7 && $hour_now<18)
	{if($day_category=="koolipäev")
		{$day_phase_n=1;}
	else {$day_phase_n=2;}}
	else {$day_phase_n=3;}
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
	<img src="kool.jpg" alt="TLÜ õppehoone" width="600">
	<h2>Kursusel õpime</h2>
	<ul>
		<li>HTML keelt</li>
		<li>PHP programmeerimiskeelt</li>
		<li>SQL päringukeelt</li>
		<li>jne!</li>
	</ul>
	<p>See tekst kirjutati 19:58 30.augustil 2021.</p>
	<p>Leht avati: <span><?php echo $weekday_names_et[$weekday_now-1].", ".$time_now.", on ".$day_category." ja ".$day_phases_et[$day_phase_n]; ?></span>.</p>
	<?php echo $pic_html; ?>
</body>
</html>