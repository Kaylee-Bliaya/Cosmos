<?php 
session_start();

include("connection.php");
include("session.php");

$user_data = check_login($con);

$ID = $_SESSION['ID'];
if (isset($_POST['save_rem_button'])) {
		$Name = $_POST['name']; // Added
		$habitDescription = $_POST['habit_description'];
		$Date = $_POST['date'];
		// $Time = $_POST['time'];
		$Points = $_POST['points']; // Added
		// $Percentage = $_POST['percentage']; // Added

	
		if(!empty($habitDescription) && !empty($Date)) {
			$addhabit = "INSERT INTO habits (UserID, Name, Description, Date, Time, Points, Percentage) 
							VALUES ('$ID', '$Name', '$habitDescription', '$Date', 'NULL', '$Points', '0')";
			mysqli_query($con, $addhabit);
			
			header("Location: habits.php");
			die;
		} 
		else {
			echo "Please enter some valid information!";
		}
	}

	if (isset($_POST['delete_rem_button'])) {
		$delete_arr = $_POST['rem_delete_id'];
		$id_to_delete = implode(',', $delete_arr);
		
		$deletehabit = "DELETE FROM habits WHERE ID IN($id_to_delete)";
		$deletehabit = mysqli_query($con, $deletehabit);

		if ($deletehabit) {
			header("Location: habits.php");
		}
		else {
			echo "Failed to Delete habit!";
			header("Location: habits.php");
		}
	}

	if (isset($_POST['update_rem_button'])) {
		$habitID = $_POST['habit_id'];
		$Name_Edit = $_POST['edit_name'];
		$habitDescription_Edit = $_POST['edit_habit_description'];
		$Date_Edit = $_POST['edit_date'];
		$Time_Edit = $_POST['edit_time'];
		$Points_Edit = $_POST['edit_points'];
		$Percentage_Edit = $_POST['edit_percentage'];
		$Delete_Edit = $_POST['edit_delete'];
	
		if(!empty($habitID)) {
			if (!empty($Delete_Edit)) { // Added
				$editDescription = "DELETE FROM habits WHERE ID = '$habitID'";
				mysqli_query($con, $editDescription);
			}
			if (!empty($Name_Edit)) { // Added
				$editDescription = "UPDATE habits SET Name = '$Name_Edit' WHERE ID = '$habitID'";
				mysqli_query($con, $editDescription);
			}
			if (!empty($habitDescription_Edit)) {
				$editDescription = "UPDATE habits SET Description = '$habitDescription_Edit' WHERE ID = '$habitID'";
				mysqli_query($con, $editDescription);
			}
			if (!empty($Date_Edit)) {
				$editDescription = "UPDATE habits SET Date = '$Date_Edit' WHERE ID = '$habitID'";
				mysqli_query($con, $editDescription);
			}
			if (!empty($Percentage_Edit)) {
				$editDescription = "UPDATE habits SET Percentage = '$Percentage_Edit' WHERE ID = '$habitID'";
				mysqli_query($con, $editDescription);
			}
			
			header("Location: habits.php");
			die;
		} 
		else {
			echo "Please enter some valid information!";
		}
	}
$query = "SELECT ID, UserID, Name, Description, Date, Time, Points, Percentage FROM habits WHERE USERID = ".$ID; // ALL Data      
$result = mysqli_query($con, $query);                  



$missed = "SELECT * FROM habits WHERE Date < CURDATE() AND USERID = ".$ID;	// Missed/Unlogged Habits
$upcoming = "SELECT * FROM habits WHERE Date > CURDATE() AND USERID = ".$ID." ORDER BY Date";	// Upcoming Habits 
$upcoming = mysqli_query($con, $upcoming);
$missed = mysqli_query($con, $missed);
 ?>
<!--------------------------
ACCESS DATA:
$row[0] = ID
$row[1] = USERID
$row[2] = Name (Of habit)
$row[3] = Description
$row[4] = Date
$row[5] = Time
$row[6] = Points 
$row[7] = Percentage
--------------------------->

 <!DOCTYPE html>
<html lang="en-US">
	<head>
		<title>Cosmos - Home</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="main.css" type="text/css">
		<link rel="stylesheet" href="calendar.css" type="text/css">
		<link rel="stylesheet" href="habits.css" type="text/css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<link href="https://fonts.googleapis.com/css2?family=Sora:wght@700&display=swap" rel="stylesheet"> 
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-6 title">
					<span>Cosmos</span>
				</div>
				<div class="col-sm-6 user-display">
					<br>
					<h2>Hello, <?php echo $user_data['Name']; ?></h2>
					<a type="button" class="btn btn-default" href="logout.php">Logout</a>
				</div>
			</div>
			<div class="row main-wrapper" style="height: 75vh;">
				<div class="col-sm-2 menu-display">
					<div class="jumbotron box-transparent box-rounded notifier btn-group-vertical" style="padding: 0px; height: auto;">
						<a class="btn btn-primary" href="index.php">Home</a>
						<a class="btn btn-primary" href="reminders.php">Reminders</a>
						<a class="btn btn-primary" href="habits.php">Habits</a>
						
					</div>
					<div>
						<div class="box-rounded box-transparent box-rounded habit-heading">
							<h2>Upcoming Habits</h2>
						</div>
						<div class="box-rounded jumbotron box-transparent box-rounded">
						<?php 
						if (mysqli_num_rows($upcoming) > 0) {
							while ($row = mysqli_fetch_array($upcoming)) {
								echo $row[2]." on ".$row[4];
								echo "<br>";
							}
						} elseif (mysqli_num_rows($upcoming) == 0) {
							echo "No Upcoming Habits to track";
						}
						
						if (mysqli_num_rows($missed) > 0) {
							echo "<br>";
							echo "<br>";
							echo "Past Habits:  ";
							echo "<br>";
							while ($row = mysqli_fetch_array($missed)) {
								echo $row[2]." on ".$row[4];
								echo "<br>";
							}
						} elseif (mysqli_num_rows($missed) == 0) {
							echo "<br><br>No past habits to display";
							echo "<br>";
						}
						?>
						</div>
					</div>
				</div>
				<div class="col-sm-7 top-padder">
					<div class="box-rounded box-transparent box-rounded habit-heading">
						<h2>Habits</h2>
					</div>
					<div style="padding: 0px; height: auto;">
						<?php
						if (mysqli_num_rows($result) > 0) {
							while ($row = $result->fetch_assoc()) {
								$progressbar = "progress-bar-success";
								$alertbar = "alert-success";
								if ($row['Percentage'] <= 30) {
									$progressbar = "progress-bar-danger";
									$alertbar = "alert-danger";
								} elseif ($row['Percentage'] <= 60) {
									$progressbar = "progress-bar-warning";
									$alertbar = "alert-warning";
								}
								echo ' 
								<div>
									<div class="alert '.$alertbar.'"><b>(ID: '.$row['ID'].')  '.$row['Name'].'</b><b> [Points:  '.$row['Points'].']</b></div>
									<div class="progress">
										<div class="progress-bar '.$progressbar.' progress-bar-striped active" role="progressbar" aria-valuenow="70"  aria-valuemin="0" aria-valuemax="100" style="width:'.$row['Percentage'].'%">'.$row['Percentage'].'%</div>
									</div>
								</div> ';
							}
						}
						
						?>
					</div>
				</div>
				<div class="col-sm-3 top-padder">
					<div class="jumbotron box-transparent box-rounded">
						<h2>Add a Habit</h2>
						<br />
						<form method = "POST">
									<div class="form-group">
										<label for="name">Name</label>
										<input type="name" class="form-control" name="name">
									</div>
									<br />
									<div class="form-group">
										<label for="habit_description">Description</label>
										<input type="habit_description" class="form-control" name="habit_description">
									</div>
									<br />
									<div class="form-group">
										<label for="date">Date</label>
										<input type="date" class="form-control" name="date">
									</div>
									<br />
									<!-- <div class="form-group">
										<label for="time">Time</label>
										<input type="time" class="form-control" name="time">
									</div> -->
									<br />
									<div class="form-group">
										<label for="points">Points</label>
										<input type="points" class="form-control" name="points">
									</div>
									<br />
									<!-- <div class="form-group">
										<label for="percentage">Percentage</label>
										<input type="percentage" class="form-control" name="percentage">
									</div> -->
									<button type="save" class="btn btn-default" name = "save_rem_button">Save</button>
						</form>
					</div>
				</div>
			</div>
			<div class="jumbotron box-transparent box-rounded">
				<h2>Edit a Habit</h2>
				<br />
				<form method = "POST">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for="habit_id">Habit ID</label>
								<input type="habit_id" class="form-control" name="habit_id"></input>
							</div>
							<div class="form-group">
								<label for="edit_habit_description">Description</label>
								<textarea rows = "5" type="edit_habit_description" class="form-control" name="edit_habit_description"></textarea>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="edit_name">Name</label>
								<textarea rows = "1" type="edit_name" class="form-control" name="edit_name"></textarea>
							</div>
							<div class="form-group">
								<label for="edit_date">Date</label>
								<input type="date" class="form-control" name="edit_date"></input>
							</div>
							<!-- <div class="form-group">
								<label for="edit_time">Time</label>
								<input type="time" class="form-control" name="edit_time"></input>
							</div> -->
							<div class="form-group">
								<label for="edit_percentage">Percentage</label>
								<input type="edit_percentage" class="form-control" name="edit_percentage"></input>
							</div>
							<!-- <div class="form-group">
								<label for="edit_points">Points</label>
								<input type="edit_points" class="form-control" name="edit_points"></input>
							</div> -->
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="edit_delete">Delete</label>
								<input type="checkbox" class="form-control" name="edit_delete"></input>
							</div>
							<br>
							<br>
							<button type="update" class="btn btn-default" name = "update_rem_button">Update</button>
						</div>
					</div>
				</form>
				<br />
			</div>
			<div class="row">
				<div class="col-sm-11">
					<footer>@2021 - CPSC 362 - Group 2</footer>
				</div>
			</div>
		</div>
	</body>
</html>