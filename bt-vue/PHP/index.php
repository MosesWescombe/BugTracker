<?php
header("Access-Control-Allow-Origin: *");

//Get Tasks
function getTasks($conn) {
    include 'getTasks.php';
}

//Get Projects
function getProjects($conn) {
    include 'getProjects.php';
}

//Get Data
function getUserID($conn) {
    include 'getUserID.php';
}

//Delete Task
function deleteTask($conn) {
    include 'deleteTask.php';
}

//Add User
function addUser($conn) {
    include 'addUser.php';
}

//Add Task
function addTask($conn) {
    include 'addTask.php';
}

//Add Link
function addLink($conn) {
    include 'addLink.php';
}


//Main
if ($_GET['confirm'] == 'confirm') {
    //Set Connection variables
    $servername = "bt-database.cpferrualvuv.ap-southeast-2.rds.amazonaws.com";
    $username = "admin";
    $password = "Robots16";
    $database = 'BugTracker';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        exit ("Connection failed: " . $conn->connect_error);
    }

    //Run commands
    switch ($_GET['type']) {
        case 'getTasks':
            getTasks($conn);
            break;
        case 'getProjects':
            getProjects($conn);
            break;
        case 'getUserID':
            getUserID($conn);
            break;
        case 'deleteTask':
            deleteTask($conn);
            break;
        case 'addUser':
            if ($_GET['email'] != "" && $_GET['email'] != 'undefined') {
                addUser($conn);
            }
            break;
        case 'addTask':
            addTask($conn);
            break;
        case 'addLink':
            addLink($conn);
            break;
    }

    //Close
    mysqli_close($conn);

} else {
    echo 'Must confirm';
}
?>