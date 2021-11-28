<?php

require_once("../../config.php");
$database="if21_taavi_pa";
if(isset($_GET["photo"]) and !empty($_GET["photo"])){
    $id = filter_var($_GET["photo"], FILTER_VALIDATE_INT);
}
if(isset($_GET["rating"]) and !empty($_GET["rating"])){
    $rating = filter_var($_GET["rating"], FILTER_VALIDATE_INT);
}

$response = "Hinne teadmata!";
echo $rating;
if(!empty($id)){
    $conn = new mysqli($_GLOBALS["server_host"], $_GLOBALS["server_user"], $_GLOBALS["server_pass"],  $database);
    $conn->set_charset("utf8");
    echo $conn->error;
    $stmt = $conn->prepare("INSERT INTO vp_photoratings (photoid, userid, rating) VALUES(?, ?, ?)");
    $stmt->bind_param("iii", $id, $_SESSION["user_id"], $rating);
    $stmt->execute();
    $stmt->close();
    echo "NOH";
    //loeme keskmise hinde
    $stmt = $conn->prepare("SELECT AVG(rating) as avgValue FROM vp_photoratings WHERE photoid = ?");
    echo $stmt->error;
    $stmt->bind_param("i", $id);
    $stmt->bind_result($score);
    $stmt->execute();
    if($stmt->fetch()){
        $response = "Keskmine hinne: " .round($score, 2);
    }
    $stmt->close();
    $conn->close();
}
echo $response;
#ei toota veel