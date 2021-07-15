<?php

if (isset($_GET['email']) && isset($_GET['id'])) {
    $email = $_GET['email'];
    $id = $_GET['id'];
    $result = $conn->query("INSERT INTO Links (user_email, task_id) VALUES ('$email', '$id');");
}

?>