<?php 
function check_login($con) {
	if(isset($_SESSION['ID'])) {
		$ID = $_SESSION['ID'];
		
		$query = "SELECT * FROM users WHERE ID = '$ID' limit 1";
		$result = mysqli_query($con, $query);
		
		if($result && mysqli_num_rows($result) > 0 ) {
			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	} else {
		header("Location: login.php");
		die;
	}
}

?>
