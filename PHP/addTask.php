<?php

if (isset($_GET['project']) && isset($_GET['description']) && isset($_GET['level'])) {
    //Add Task
    $project = "'" . $_GET['project'] . "'";
    $description = "'" . $_GET['description'] . "'";
    $level = "'" . $_GET['level'] . "'";
    $result = $conn->query("INSERT INTO tasks(level, project, description) VALUES ($level, $project, $description);");
    
    $result = $conn->query("SELECT * FROM tasks WHERE  project = $project AND level = $level AND description = $description");
    
    //Return data
    $return = [];
    while($row = $result->fetch_assoc()) {
        $return = json_encode($row);           
    };
    
    echo $return;
}

?>