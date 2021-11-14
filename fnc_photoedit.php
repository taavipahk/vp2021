<?php
    $database = "if21_taavi_pa";
    $GLOBALS["orig_dir"] = "upload_photos_orig/";
    $GLOBALS["norm_dir"] = "upload_photos_normal/";
    $GLOBALS["thumb_dir"] = "upload_photos_thumb/";

    function getPhotoData($pid)
    {
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user"], $GLOBALS["server_pass"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT filename, created, alttext, privacy, deleted FROM vp_photos WHERE id ='".$pid."'");
        echo $conn->error;
        $stmt->bind_result($fname,$cdate,$alttxt,$priv,$ddate);
        $stmt->execute();
        if($stmt->fetch()){
            $photo_html = '<img src="' .$GLOBALS["norm_dir"] .$fname .'" alt="'.$alttxt.'" >';
        }
        $stmt->close();
		$conn->close();
        $arr=[$photo_html,$fname,$cdate,$alttxt,$priv,$ddate,$pid];
        return $arr;
    }

    function updatePhotoData($pid,$alttxt,$priv)
    {
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user"], $GLOBALS["server_pass"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("UPDATE vp_photos SET alttext=?, privacy=? WHERE id=?");
        echo $conn->error;
        $stmt->bind_param("sii", $alttxt, $priv, $pid);
        if($stmt->execute()){
            $notice="Andmed edukalt uuendatud!";
        } else {
            $notice="Miski läks tuksi.";
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }
?>