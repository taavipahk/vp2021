<?php
	$author_name="Mida Iganes";
	require_once("../../config.php");
	require_once("fnc_movie.php");
	require("page_header.php");
    $peopleArr=[];
    $peopleArr=get_people();
    #yyyy-mm-dd
    function str_to_date($string)
    {
        $months=['jaanuaril','veebruaril','märtsil','aprillil',
        'mail','juunil','juulil','augustil','septembril',
        'oktoobril','novembril','detsembril'];
        $year=substr($string,0,4);
        $month=substr($string,5,2);
        $day=substr($string,8,2);
        $str=intval($day).'. '.$months[intval($month)-1].' '.$year.' a';
        return $str;
    }
?>
	<h1><?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?></h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<p><a href="home.php">Tagasi kodulehele</a></p>
	<hr>
<h2>Näitlejad, režissöörid, muud isikud</h2>
<?php 
for($n=0;$n<=count($peopleArr[0])-1;$n++)
{
    echo "<h4>".$peopleArr[0][$n]." ".$peopleArr[1][$n]."</h4>";
    echo "<p>Sündinud ".str_to_date($peopleArr[2][$n])."</p><br>";
}
?>
</body>
</html>