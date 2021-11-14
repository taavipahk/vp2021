<?php
    require_once("../../config.php");
	require_once("fnc_gallery.php");
    require_once("fnc_photoedit.php");
    require_once("fnc_general.php");
    require("page_header.php");
    $err="";
    $photoData=["Miski ebaõnnestus..",""];
    $notice="<br>";
    $altTxt="";

    if(isset($_GET["photo"]) and !empty($_GET["photo"])){
        $msg=checkBelonging($_SESSION["user_id"], $_GET["photo"]);
    } else {
        header("Location: home.php");
    }

    if($msg==False)
    {
        $err="Pilti ei saa muuta!";
        header("Location: home.php:2");
    } else {
        $err="Pildi kuuluvus tuvastatud.";
        $photoData=getPhotoData($_GET["photo"]);
        $photo_html=$photoData[0];
        $filename=$photoData[1];
        $creationDate=$photoData[2];
        $altTxt=$photoData[3];
        $privacy=$photoData[4];
        $deletionDate=$photoData[5];
		$photoid=$photoData[6];
        if($deletionDate==null)
        {
            $deletionDate="pilti pole kustutatud.";
        }
        #$arr=[$photo_html,$fname,$cdate,$alttxt,$priv,$ddate];
    }

    if(isset($_POST["data_submit"]))
    {
        if(!empty($_POST["alttxt_input"]))
            {
                $altTxt=$_POST["alttxt_input"];
                $privacy=$_POST["privacy_input"];
                $notice.=updatePhotoData($photoid,$_POST["alttxt_input"],$_POST["privacy_input"]);
            }
        else {
            if(empty($_POST["alttxt_input"]))
            {
                $notice.="Alternatiivtekst on puudu!";
            } else {
                $notice.="123";
            }
        }
		
    }
    echo $photoid;
	var_dump($_POST);
?>

	<p><a href="home.php">Avaleht</a></p>
	<hr>
    <h2>Foto andmete muutmine</h2>
    <?php echo $err; echo $notice; ?>
    <p>
        <?php
        echo $photo_html." <br>";
        echo "Failinimi: ".$filename." <br>";
        echo "Üles laetud: ".$creationDate." <br>";
        echo "Kustutatud: ".$deletionDate." <br>";
        ?>
	</p>
    <form method="post">
		<label for="alttxt_input">Alternatiivtekst (Tekst, mida näidatakse, kui pilt ära ei lae)</label><br>
		<input type="text" name="alttxt_input" id="alttxt_input" placeholder="Sisesta" rows="10" value="<?php echo $altTxt; ?>">
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_1" value="1" 
		<?php if ($privacy == 1) {
			echo " checked";
		} ?>>
		<label for="privacy_input_1">Privaatne (ainult mina näen)</label>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_2" value="2" 
		<?php if ($privacy == 2) {
			echo " checked";
		} ?>>
		<label for="privacy_input_2">Sisseloginud kasutajad näevad</label>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_3" value="3"
		<?php if ($privacy == 3) {
			echo " checked";
		} ?>>
		<label for="privacy_input_3">Avalik</label>
		<br>
		<input type="submit" name="data_submit" value="Muuda andmeid">
    </form>
</body>
</html>