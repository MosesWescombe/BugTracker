<?php

if (isset($_GET['id'])) {
    //Delete a user
    $id = "'" . $_GET['id'] . "'";
    $result = $conn->query("DELETE FROM Tasks WHERE task_id = $id;");
    $result = $conn->query("DELETE FROM Links WHERE task_id = $id;");
}

?>