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

    $email = $_GET['email'];
    $id = $_GET['id'];
    $result = $conn->query("INSERT INTO Links (user_email, task_id) VALUES ('$email', '$id');");

    //Close
    mysqli_close($conn);
}
?>