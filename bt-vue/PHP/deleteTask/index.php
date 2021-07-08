<?php
header("Access-Control-Allow-Origin: *");

$servername = "bt-database.cpferrualvuv.ap-southeast-2.rds.amazonaws.com";
$username = "admin";
$password = "Robots16";

// Create connection
$conn = new mysqli($servername, $username, $password, "BugTracker");

// Check connection
if ($conn->connect_error) {
  echo "Connection failed: " . $conn->connect_error;
}

//Get all users
$id = "'" . $_GET['id'] . "'";
$result = $conn->query("DELETE FROM Tasks WHERE task_id = $id;");

echo "Ran";

//Close
mysqli_close($conn);
?>