<?php

if (isset($_GET['project']) && isset($_GET['email'])) {
  //Get all users
  $email = $_GET['email'];
  $project = $_GET['project'];
  $result = $conn->query("SELECT * FROM tasks JOIN links ON tasks.task_id = links.task_id AND links.user_email = '$email' AND tasks.project = '$project'");

  //Return data
  $resultArray = [];
  while($row = $result->fetch_assoc()) {
    array_push($resultArray, json_encode($row));           
  };

  echo json_encode($resultArray);
}

?>