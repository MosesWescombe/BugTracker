<?php
header("Access-Control-Allow-Origin: *");

$servername = "bt-database.cpferrualvuv.ap-southeast-2.rds.amazonaws.com";
$username = "admin";
$password = "Robots16";

if ($_GET['confirm'] == 'confirm') {
  // Create connection
  $conn = new mysqli($servername, $username, $password, "BugTracker");
  
  // Check connection
  if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
  }
  
  //Get all users
  $email = $_GET['email'];
  $project = $_GET['project'];
  $result = $conn->query("SELECT * FROM Tasks JOIN Links ON Tasks.task_id = Links.task_id AND Links.user_email = '$email' AND Tasks.project = '$project'");
  
  //Return data
  $resultArray = [];
  while($row = $result->fetch_assoc()) {
    array_push($resultArray, json_encode($row));           
  };
  
  echo json_encode($resultArray);
  
  //Close
  mysqli_close($conn);

}
?>