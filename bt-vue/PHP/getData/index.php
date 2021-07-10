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

    //Get a user by email
    if ($_GET['type'] == 'checkUser') {
        //Return a user ID, if they exist.
        $email = $_GET['email'];
        $result = $conn->query("SELECT user_id FROM Users WHERE email = '$email'");

        //Return data
        $return = [];
        while($row = $result->fetch_assoc()) {
            $return = json_encode($row);           
        };

        echo json_encode($return);
    } else {
        echo "Nothing";
    }

    //Close
    mysqli_close($conn);
}
?>