<?php

if (isset($_GET['id'])) {
    //Delete a user
    $id = "'" . $_GET['id'] . "'";
    $result = $conn->query("DELETE FROM tasks WHERE task_id = $id;");
    $result = $conn->query("DELETE FROM links WHERE task_id = $id;");
}

?>