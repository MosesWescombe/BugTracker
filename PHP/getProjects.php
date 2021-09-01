<?php

if (isset($_GET['email'])) {
  //Get all users
  $email = $_GET['email'];
  $result = $conn->query("SELECT DISTINCT tasks.project FROM tasks JOIN links ON tasks.task_id = links.task_id AND links.user_email = '$email'");

  //Return data
  $resultArray = [];
  while($row = $result->fetch_assoc()) {
    array_push($resultArray, json_encode($row));           
  };
  echo json_encode($resultArray);
}
?>