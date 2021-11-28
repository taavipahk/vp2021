<?php
    require_once("../../config.php");
	require_once("fnc_gallery.php");
    
    $page = 1;
    $page_limit = 8;
    $photo_count = count_public_photos(2);
    //hoolitseme, et saaks liikuda vaid legaalsetele lehekülgedele, mis on olemas
    if(!isset($_GET["page"]) or $_GET["page"] < 1){
        $page = 1;
    } elseif(round($_GET["page"] - 1) * $page_limit >= $photo_count){
        $page = ceil($photo_count / $page_limit);
    } else {
        $page = $_GET["page"];
    }

    $to_head = '<link rel="stylesheet" type="text/css" href="style/gallery.css">' ."\n";
    $to_head .= '<link rel="stylesheet" type="text/css" href="style/modal.css">' ."\n";
    $to_head .= '<script src="javascript/modal.js" defer></script>' ."\n";
    
    require("page_header.php");
?>
    <!--modaalakna osa galerii jaoks-->
    <div id="modalarea" class="modalarea">
        <!--sulgemisnupp-->
        <span id="modalclose" class="modalclose">&times;</span>
        <div class="modalhorizontal">
            <div class="modalvertical">
                <p id="modalcaption"></p>
                <img id="modalimg" src="pics/empty.png" alt="Galerii pilt">
                <br>
                <div id="rating" class="modalRating">
                    <input id="rate1" name="rating" type="radio" value="1"><label for="rate1">1</label>
                    <input id="rate2" name="rating" type="radio" value="2"><label for="rate2">2</label>
                    <input id="rate3" name="rating" type="radio" value="3"><label for="rate3">3</label>
                    <input id="rate4" name="rating" type="radio" value="4"><label for="rate4">4</label>
                    <input id="rate5" name="rating" type="radio" value="5"><label for="rate5">5</label>
                    <button id="storeRating" type="button">Salvesta hinnang</button>
                    <br>
                    <p id="avgRating"></p>
                </div>
            </div>
        </div> 
    </div>

    <p><a href="home.php">Avaleht</a></p>

	<hr>
    <h2>Fotogalerii</h2>
    <p>
        <?php
            if($page > 1){
                echo '<span><a href="?page=' .($page - 1) .'">Eelmine leht</a></span> |' ."\n";
            } else {
                echo "<span>Eelmine leht</span> | \n";
            }
            if($page * $page_limit < $photo_count){
                echo '<span><a href="?page=' .($page + 1) .'">Järgmine leht</a></span>' ."\n";
            } else {
                echo "<span>Järgmine leht</span> \n";
            }
            
        ?>
    </p>
    <div id="gallery" class="gallery">
    <?php echo read_public_photo_thumbs($page_limit, $page); ?>
    </div>
</body>
</html>