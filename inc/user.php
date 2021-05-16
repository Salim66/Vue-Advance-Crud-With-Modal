<?php


// Server Connection
$conn = new mysqli('localhost', 'root', '', 'vue-advance-crud');

//get action and assign to another variable
$action = "read";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

//when action value is red then all users data get into server
if ($action == 'read') {
    //get all data query
    $data_all = $conn->query("SELECT * FROM users ORDER BY id DESC ");

    $data = [];

    //all data fetch
    while ($user = $data_all->fetch_assoc()) {
        array_push($data, $user);
    }
    echo json_encode($data);
}
