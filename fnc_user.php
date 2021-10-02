<?php
	$database="if21_taavi_pa";
	function store_new_user($name,$surname,$gender,$birth_date,$email,$password)
	{
		$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
		$conn->set_charset("utf8");
		$query="SELECT id FROM vp_users WHERE email='" .$email. "'";
		$result=$conn->query($query);
		if($result->num_rows>0)
		{
			$notice="Sellise emailiga kasutaja on juba olemas!";
			$result->close();
			$conn->close();
			return $notice;
		}
		$stmt=$conn->prepare("INSERT INTO vp_users (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
		echo $conn->error;
		$option=["cost"=>12];
		$pwd_hash=password_hash($password,PASSWORD_BCRYPT,$option);
		$stmt->bind_param("sssiss", $name, $surname, $birth_date, $gender, $email, $pwd_hash);
		$notice="Tekkis viga kasutajat luues:";
		if($stmt->execute()){
			$notice="Kasutaja loodud";
		}
		else{$notice="Tekkis viga kasutajat luues:".$stmt->error;}
		//
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	
	function sign_in($email,$password)
	{
		$notice=null;
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			if(strlen($password)>7)
			{
			$conn=new mysqli($GLOBALS["server_host"],$GLOBALS["server_user"],$GLOBALS["server_pass"],$GLOBALS["database"]);
			$conn->set_charset("utf8");
			$stmt=$conn->prepare("SELECT id, firstname, lastname, password FROM vp_users WHERE email=?");
			echo $conn->error;
			$stmt->bind_param("s",$email);
			$stmt->bind_result($id_from_db,$firstname_from_db,$lastname_from_db,$password_from_db);
			$stmt->execute();
			if($stmt->fetch())
			{
				if(password_verify($password,$password_from_db))
				{
					$_SESSION["user_id"]=$id_from_db;
					$_SESSION["firstname"]=$firstname_from_db;
					$_SESSION["lastname"]=$lastname_from_db;
					$stmt->close();
					$conn->close();
					header("Location: home.php");
					exit();
				}else{
					$notice="Kasutajanimi või parool on vale";
				}
			}else{
				$notice="Kasutajanimi või parool on vale";
			}
			$stmt->close();
			$conn->close();
			} else {$notice="Parool liiga lühike!";}
		} else {
		$notice="Kasutajanimi või parool on vale";
		}
		$returnArr=[$notice,$email];
		return $returnArr;
	}