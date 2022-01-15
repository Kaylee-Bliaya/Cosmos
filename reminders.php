<?php 
	session_start();

	include("connection.php");
	include("session.php");

	$user_data = check_login($con);

	$ID = $_SESSION['ID'];

	if (isset($_POST['save_rem_button'])) {
		$reminderDescription = $_POST['reminder_description'];
		$Date = $_POST['date'];
		$Time = $_POST['time'];
		$Location = $_POST['loaction'];
		$Recurrence = $_POST['recurrence'];
	
		if(!empty($reminderDescription) && !empty($Date) && !empty($Time)) {
			$addReminder = "INSERT INTO reminders (UserID, Description, Date, Time, Location, Reoccuring) 
							VALUES ('$ID', '$reminderDescription', '$Date', '$Time', '$Location', '$Recurrence')";
			mysqli_query($con, $addReminder);
			
			header("Location: reminders.php");
			die;
		} 
		else {
			echo "Please enter some valid information!";
		}
	}

	if (isset($_POST['delete_rem_button'])) {
		$delete_arr = $_POST['rem_delete_id'];
		$id_to_delete = implode(',', $delete_arr);
		
		$deleteReminder = "DELETE FROM reminders WHERE ID IN($id_to_delete)";
		$deleteReminder = mysqli_query($con, $deleteReminder);

		if ($deleteReminder) {
			header("Location: reminders.php");
		}
		else {
			echo "Failed to Delete Reminder!";
			header("Location: reminders.php");
		}
	}

	if (isset($_POST['update_rem_button'])) {
		$reminderID = $_POST['reminder_id'];
		$reminderDescription_Edit = $_POST['edit_reminder_description'];
		$Date_Edit = $_POST['edit_date'];
		$Time_Edit = $_POST['edit_time'];
		$Location_Edit = $_POST['edit_loaction'];
		$Recurrence_Edit = $_POST['edit_recurrence'];
	
		if(!empty($reminderID)) {
			if (!empty($reminderDescription_Edit)) {
				$editRem = "UPDATE reminders SET Description = '$reminderDescription_Edit' WHERE ID = '$reminderID'";
				mysqli_query($con, $editRem);
			}
			if (!empty($Date_Edit)) {
				$editRem = "UPDATE reminders SET Date = '$Date_Edit' WHERE ID = '$reminderID'";
				mysqli_query($con, $editRem);
			}
			if (!empty($Time_Edit)) {
				$editRem = "UPDATE reminders SET Time = '$Time_Edit' WHERE ID = '$reminderID'";
				mysqli_query($con, $editRem);
			}
			if (!empty($Location_Edit)) {
				$editRem = "UPDATE reminders SET Location = '$Location_Edit' WHERE ID = '$reminderID'";
				mysqli_query($con, $editRem);
			}
			if (!empty($Recurrence_Edit)) {
				$editRem = "UPDATE reminders SET Reoccuring = '$Recurrence_Edit' WHERE ID = '$reminderID'";
				mysqli_query($con, $editRem);
			}
			
			header("Location: reminders.php");
			die;
		} 
		else {
			echo "Please enter some valid information!";
		}
	}

	$result = "SELECT *
			   FROM reminders
			   WHERE Date >= CURDATE() AND UserID = '$ID'
			   ORDER BY Date, Time";
	$result = mysqli_query($con, $result);
?>

<!DOCTYPE html>
<html lang="en-US">
	<head>
		<title>Cosmos - Reminders</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="main.css" type="text/css">
		<link rel="stylesheet" href="reminder.css?v=1.6" type="text/css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<link href="https://fonts.googleapis.com/css2?family=Sora:wght@700&display=swap" rel="stylesheet"> 
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
			<div class="row main-wrapper">
				<div class="col-sm-2 menu-display">
					<div class="jumbotron box-transparent box-rounded notifier" style="padding: 0px;">
						<div class="btn-group-vertical" style="width: 100%;">
							<a class="btn btn-primary" href="index.php">Home</a>
							<a class="btn btn-primary" href="reminders.php">Reminders</a>
							<a class="btn btn-primary" href="habits.php">Habits</a>
						</div>
					</div>
				</div>
				<div class="col-sm-7 current-reminders top-padder">
					<div class="jumbotron box-transparent box-rounded">
						<h2>Upcoming Reminders</h2>
						<div class = "upcomingRemBox">
							<form method = "POST">
								<table>
									<?php 
										date_default_timezone_set('US/Pacific');
										$currDate = date("m/d/Y");

										if (mysqli_num_rows($result) > 0) {
											echo "<thead>";
												echo "<tr style = 'text-align: center;'>";
													echo "<th>Reminder ID</th>";
													echo "<th>Description</th>";
													echo "<th>Date</th>";
													echo "<th>Time</th>";
													echo "<th>Location</th>";
													echo "<th>Recurrence</th>";
													echo "<td>" . "<button type='delete' class='btn btn-default; update_buttons' name='delete_rem_button'>Delete</button>" . "</td>";
												echo "</tr>";
											echo "</thead>";

											while ($row = mysqli_fetch_array($result)) {
												$remID = $row[0];
												$remUserID = $row[1];
												$remDescription = $row[2];
												$remDate = date("m/d/Y", strtotime($row[3]));
												$remTime = date("h:i a", strtotime($row[4]));
												$remLocation = $row[5];
												$remRecurring = $row[6];

												echo "<tbody>";
													echo "<tr>";
														echo "<td>" . $remID . "</td>";
														echo "<td style='text-align: left;'>" . $remDescription . "</td>";
														echo "<td>" . $remDate . "</td>";
														echo "<td>" . $remTime . "</td>";
														echo "<td>" . $remLocation . "</td>";
														echo "<td>" . $remRecurring . "</td>";
														echo "<td class = 'select_rem' style = 'text-align: center;'>" . "<input type = 'checkbox' name = 'rem_delete_id[]' value = $remID>" . "</td>";
													echo "</tr>";
												echo "</tbody>";
											}
										}
										else {
											echo "<br />";
											echo "No upcoming reminders to track";
										}
									?>
								</table>
							</form>
						</div>
					</div>
				</div>
				<div class="col-sm-3 create-reminder top-padder">
					<div class="jumbotron box-transparent box-rounded">
						<h2>Create a Reminder</h2>
						<br />
						<form method = "POST">
								<div class="form-group">
									<label for="reminder_description">Description</label>
									<textarea rows = "5" type="reminder_description" class="form-control" name="reminder_description"></textarea>
								</div>
								<br />
								<div class="form-group">
									<label for="date">Date</label>
									<input type="date" class="form-control" name="date"></input>
								</div>
								<br />
								<div class="form-group">
									<label for="time">Time</label>
									<input type="time" class="form-control" name="time"></input>
								</div>
								<br />
								<div class="form-group">
									<label for="loaction">Location</label>
									<input type="loaction" class="form-control" name="loaction"></input>
								</div>
								<br />
								<div class="form-group">
									<label for="recurrence">Recurrence</label>
									<input type="recurrence" class="form-control" name="recurrence"></input>
								</div>
								<br />
								<button type="save" class="btn btn-default" name = "save_rem_button">Save</button>
						</form>
						<br />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"></div>
				<div class="col-sm-2"></div>
				<div class="col-sm-10">
					<div class="jumbotron box-transparent box-rounded">
						<h2>Edit a Reminder</h2>
						<br />
						<form method = "POST">
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="reminder_id">Reminder ID</label>
											<input type="reminder_id" class="form-control" name="reminder_id"></input>
										</div>
										<div class="form-group">
											<label for="edit_reminder_description">Description</label>
											<textarea rows = "5" type="edit_reminder_description" class="form-control" name="edit_reminder_description"></textarea>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="edit_date">Date</label>
											<input type="date" class="form-control" name="edit_date"></input>
										</div>
										<div class="form-group">
											<label for="edit_time">Time</label>
											<input type="time" class="form-control" name="edit_time"></input>
										</div>
										<div class="form-group">
											<label for="edit_loaction">Location</label>
											<input type="edit_loaction" class="form-control" name="edit_loaction"></input>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="edit_recurrence">Recurrence</label>
											<input type="edit_recurrence" class="form-control" name="edit_recurrence"></input>
										</div>
									</div>
								</div>
								<button type="update" class="btn btn-default" name = "update_rem_button">Update</button>
						</form>
						<br />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"></div>
				<div class="col-sm-2"></div>
				<div class="col-sm-7">
					<footer>@2021 - CPSC 362 - Group 2</footer>
				</div>
				<div class="col-sm-3"></div>
			</div>
		</div>
	</body>
</html>
