<?php

if (isset($_GET['email'])) {
  //Get all users
  $email = $_GET['email'];
  $result = $conn->query("SELECT DISTINCT Tasks.project FROM Tasks JOIN Links ON Tasks.task_id = Links.task_id AND Links.user_email = '$email'");

  //Return data
  $resultArray = [];
  while($row = $result->fetch_assoc()) {
    array_push($resultArray, json_encode($row));           
  };
  echo json_encode($resultArray);
}
?>