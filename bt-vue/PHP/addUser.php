<?php

if (isset($_GET['email'])) {
    //Add a new user
    $email = "'" . $_REQUEST['email'] . "'";
    $result = $conn->query("INSERT INTO Users (email) VALUES ($email);");
}

?>