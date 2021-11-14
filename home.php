<?php
	require("page_header.php");
    $GLOBALS["orig_dir"] = "upload_photos_orig/";
    $GLOBALS["norm_dir"] = "upload_photos_normal/";
    $GLOBALS["thumb_dir"] = "upload_photos_thumb/";
?>
	<h1><?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?></h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<ul>
	<li><a href="?logout=1">Logi välja</a></li>
	<p>Ligipääsetav ainult sisselogitud kasutajale:</p>
	<li><a href="list_films.php">Filmide tabel</a></li>
	<li><a href="list_people.php">Inimeste tabel</a></li>
	<li><a href="user_profile.php">Profiil</a></li>
	<li><a href="movie_relations.php">Filmi info sidumine</a></li>
	<li><a href="add_info.php">Uue info lisamine! (EPIC)</a></li>
	<li><a href="gallery_photo_upload.php">Fotode üleslaadimine</a></li>
    <li><a href="gallery_public.php">Sisseloginud kasutajate jaoks avalike fotode galerii</a></li>
    <li><a href="gallery_own.php">Minu oma galerii fotod</a></li>
	</ul>
</body>
</html>