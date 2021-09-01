<?php

if (isset($_GET['email'])) {
    //Return a user ID, if they exist.
    $email = $_GET['email'];
    $result = $conn->query("SELECT user_id FROM users WHERE email = '$email'");

    //Return data
    $return = [];
    while($row = $result->fetch_assoc()) {
        $return = json_encode($row);           
    };

    echo json_encode($return);
}

?>