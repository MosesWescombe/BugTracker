<?php

if (isset($_GET['project']) && isset($_GET['email'])) {
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
}

?>