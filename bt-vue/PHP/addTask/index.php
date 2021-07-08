<?php
header("Access-Control-Allow-Origin: *");

if ($_GET['confirm'] == 'confirm') {
  $servername = "bt-database.cpferrualvuv.ap-southeast-2.rds.amazonaws.com";
  $username = "admin";
  $password = "Robots16";
  
  // Create connection
  $conn = new mysqli($servername, $username, $password, "BugTracker");
  
  // Check connection
  if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
  }
  
  //Add Task
  $project = "'" . $_GET['project'] . "'";
  $description = "'" . $_GET['description'] . "'";
  $level = "'" . $_GET['level'] . "'";
  $result = $conn->query("INSERT INTO Tasks(level, project, description) VALUES ($level, $project, $description);");
  
  echo "Ran";
  
  //Close
  mysqli_close($conn);
}
?>