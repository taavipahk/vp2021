<?php
    $database = "if21_taavi_pa";
    $GLOBALS["orig_dir"] = "upload_photos_orig/";
    $GLOBALS["norm_dir"] = "upload_photos_normal/";
    $GLOBALS["thumb_dir"] = "upload_photos_thumb/";
    
    function show_latest_public_photo(){
        $photo_html = null;
        $privacy = 3;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user"], $GLOBALS["server_pass"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT filename, alttext FROM vp_photos WHERE id = (SELECT MAX(id) FROM vp_photos WHERE privacy = ? AND deleted IS NULL)");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($filename_from_db, $alttext_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            //<img src="kataloog.file" alt="tekst">
            $photo_html = '<img src="' .$GLOBALS["norm_dir"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $photo_html .= "Üleslaetud foto";
            } else {
                $photo_html .= $alttext_from_db;
            }
            $photo_html .= '">' ."\n";
        }
        if(empty($photo_html)){
            $photo_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
        }
        
        $stmt->close();
		$conn->close();
		return $photo_html;
    }
    
    function read_public_photo_thumbs($page_limit, $page){
        $gallery_html = null;
        $privacy = 2;
        $skip = ($page - 1) * $page_limit;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user"], $GLOBALS["server_pass"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT filename, alttext FROM vp_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC LIMIT ?,?");
        echo $conn->error;
        $stmt->bind_param("iii", $privacy, $skip, $page_limit);
        $stmt->bind_result($filename_from_db, $alttext_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            //<div class="thumbgallery">
            //<img src="kataloog.file" alt="tekst">
            //</div>
            $gallery_html .= '<div class="thumbgallery">' ."\n";
            $gallery_html .= '<img src="' .$GLOBALS["thumb_dir"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $gallery_html .= "Üleslaetud foto";
            } else {
                $gallery_html .= $alttext_from_db;
            }
            $gallery_html .= '" class="thumbs">' ."\n";
            $gallery_html .= "</div> \n";
        }
        if(empty($gallery_html)){
            $photo_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
        }
        
        $stmt->close();
		$conn->close();
		return $gallery_html;
    }
    
/*     function read_public_photo_thumbs(){
        $gallery_html = null;
        $privacy = 2;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user"], $GLOBALS["server_pass"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT filename, alttext FROM vp_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC LIMIT 3,3");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($filename_from_db, $alttext_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            //<img src="kataloog.file" alt="tekst">
            $gallery_html .= '<img src="' .$GLOBALS["thumb_dir"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $gallery_html .= "Üleslaetud foto";
            } else {
                $gallery_html .= $alttext_from_db;
            }
            $gallery_html .= '">' ."\n";
        }
        if(empty($gallery_html)){
            $photo_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
        }
        
        $stmt->close();
		$conn->close();
		return $gallery_html;
    } */
    
    function read_own_photo_thumbs($page_limit, $page){
        $gallery_html = null;
        $skip = ($page - 1) * $page_limit;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user"], $GLOBALS["server_pass"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id, filename, alttext FROM vp_photos WHERE userid = ? AND deleted IS NULL ORDER BY id DESC LIMIT ?,?");
        echo $conn->error;
        $stmt->bind_param("iii", $_SESSION["user_id"], $skip, $page_limit);
        $stmt->bind_result($id_from_db, $filename_from_db, $alttext_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            //<div class="thumbgallery">
            //<img src="kataloog.file" alt="tekst">
            //</div>
            $gallery_html .= '<div class="thumbgallery">' ."\n";
            $gallery_html .= '<a href="edit_gallery_photo.php?photo=' .$id_from_db .'">';
            $gallery_html .= '<img src="' .$GLOBALS["thumb_dir"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $gallery_html .= "Üleslaetud foto";
            } else {
                $gallery_html .= $alttext_from_db;
            }
            $gallery_html .= '" class="thumbs">' ."\n";
            $gallery_html .= "</a> \n";
            $gallery_html .= "</div> \n";
        }
        if(empty($gallery_html)){
            $photo_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
        }
        
        $stmt->close();
		$conn->close();
		return $gallery_html;
    }
    
    function count_public_photos($privacy){
        $photo_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user"], $GLOBALS["server_pass"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vp_photos WHERE privacy >= ? AND deleted IS NULL");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($count);
        $stmt->execute();
        if($stmt->fetch()){
            $photo_count = $count;
        }
        $stmt->close();
		$conn->close();
		return $photo_count;
    }

    function checkBelonging($uid, $pid)
    {
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user"], $GLOBALS["server_pass"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $query ="SELECT id, userid FROM vp_photos WHERE userid = '".$uid."' AND id = '".$pid."'";
        $result=$conn->query($query);
		if($result->num_rows<1)
		{
			$result->close();
			$conn->close();
			$err=False;
			return $err;
		} else {
            $result->close();
            $conn->close();
            $success=True;
            return $success;
        }
    }

#		$query="SELECT person_id,movie_id,position_id,role FROM person_in_movie WHERE person_id='".$person."' AND movie_id='".$movie."' AND position_id='".$pos."' AND role='".$role."'";
#		$result=$conn->query($query);
#		if($result->num_rows>0)
#		{
#			$result->close();
#			$conn->close();
#			$err="Selline seos on juba loodud!";
#			return $err;
#		}
#		$stmt=$