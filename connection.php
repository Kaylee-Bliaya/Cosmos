<?php 
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname= "cosmosdb";

if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname)) {
	die("Database Error: failed to connect.");
}
?>

