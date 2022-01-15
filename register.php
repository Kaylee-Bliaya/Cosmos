<?php 
session_start();

include("connection.php");
include("session.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$Name = $_POST['username'];
	$Email = $_POST['email'];
	$Password = $_POST['password'];
	$ID = $_SESSION['ID'];
	
	if(!empty($Name) && !empty($Password) && !empty($Email) && !is_numeric($Name)) {
		$query = "INSERT INTO users (Name, Password, Email) VALUES ('$Name', '$Password', '$Email')";
		$query2 = "INSERT INTO habits (ID, Points) VALUES(LAST_INSERT_ID(), '10')";
		mysqli_query($con, $query);
		mysqli_query($con, $query2);
			
		header("Location: login.php");
		die;
	} else {
		echo "Please enter some valid information!";
	}
}
 ?>

 <!DOCTYPE html>
<html lang="en-US">
	<head>
		<title>Cosmos - Register</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="main.css" type="text/css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<link href="https://fonts.googleapis.com/css2?family=Sora:wght@700&display=swap" rel="stylesheet"> 
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12 title">
					<span>Cosmos</span>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-2 menu-display">
					<div class="jumbotron box-transparent box-rounded notifier btn-group-vertical" style="padding: 0px; height: auto;">
						<a class="btn btn-primary" href="index.php">Home</a>
						<a class="btn btn-primary" href="reminders.php">Reminders</a>
						<a class="btn btn-primary" href="habits.php">Habits</a>
					</div>
				</div>
				<div class="col-sm-2">
				</div>
				<div class="col-sm-4">
					<div class="jumbotron box-transparent box-rounded">
						<h2 style="padding-bottom: 20px;">Register</h2>
						<form method = "POST">
							<div class="form-group">
								<label for="username">Username</label>
								<input type="username" class="form-control" name="username">
							</div>
							<div class="form-group">
								<label for="password2">Email</label>
								<input type="email" class="form-control" name="email">
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control" name="password">
							</div>
							<button type="submit" class="btn btn-default">Submit</button>
						</form>
					</div>
				</div>
				<div class="col-sm-4">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
				</div>
				<div class="col-sm-4">
					<div class="jumbotron box-transparent box-rounded">
						<p>Already have an account?</p>
						<label>Click to sign in</label>
						<br>
						<a type="button" class="btn btn-default" href="login.php">Login</a>
					</div>
				</div>
				<div class="col-sm-4">
				</div>
			</div>
			<div class="row">
				<footer>@2021 - CPSC 362 - Group 2</footer>
			</div>
		</div>
	</body>
</html>
