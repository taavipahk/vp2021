<?php
	$database="if21_taavi_pa";
	
			function update_profile($user_id,$description,$bgcolor,$color)
	{
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt=$conn->prepare("UPDATE vp_userprofiles SET description=?, bgcolor=?, txtcolor=? WHERE userid=?");
		echo $conn->error;
		$stmt->bind_param("sssi", $description, $bgcolor, $color, $user_id);
		$notice="Tekkis viga profiili uuendades:";
		if($stmt->execute())
		{
			$notice="Profiil uuendatud";
		}
		else{$notice="Tekkis viga profiili uuendades:".$stmt->error;}
		//
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
		function store_new_profile($user_id,$description,$bgcolor,$color)
	{
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$query="SELECT userid FROM vp_userprofiles WHERE userid='" .$user_id. "'";
		$result=$conn->query($query);
		if($result->num_rows>0)
		{
			$result->close();
			$conn->close();
			$notice=update_profile($user_id,$description,$bgcolor,$color);
			return $notice;
		}
		$stmt=$conn->prepare("INSERT INTO vp_userprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
		echo $conn->error;
		$stmt->bind_param("isss", $user_id, $description, $bgcolor, $color);
		$notice="Tekkis viga profiili luues:";
		if($stmt->execute()){
			$notice="Profiil loodud";
		}
		else{$notice="Tekkis viga profiili luues:".$stmt->error;}
		//
		$stmt->close();
		$conn->close();
		return $notice;
	}
#UPDATE table_name
#SET column1 = value1, column2 = value2, ...
#WHERE condition;

?>