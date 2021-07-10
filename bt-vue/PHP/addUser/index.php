<?php
header("Access-Control-Allow-Origin: *");

if ($_GET['confirm'] == 'confirm' && $_GET['email'] != "") {
    $servername = "bt-database.cpferrualvuv.ap-southeast-2.rds.amazonaws.com";
    $username = "admin";
    $password = "Robots16";

    // Create connection
    $conn = new mysqli($servername, $username, $password, "BugTracker");

    // Check connection
    if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
    }

    //Add a new user
    $email = "'" . $_REQUEST['email'] . "'";
    $result = $conn->query("INSERT INTO Users (email) VALUES ($email);");

    //Close
    mysqli_close($conn);
}
?>