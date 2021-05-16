<?php
require_once '../app/db.php';
require_once '../app/function.php';



//get user data
$data = json_decode(file_get_contents('php://input'));

//get action and assign to another variable
$action = "read";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

//when action value is read then all users data get into server
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

//when action value is create then create new user
if ($action == 'create') {
    //get values
    $name = $_POST['name'];
    $email = $_POST['email'];
    $cell = $_POST['cell'];

    // //get file
    // $photo_name = $_FILES['photo']['name'];
    // $photo_tmp_name = $_FILES['photo']['tmp_name'];

    // //move file into folder
    // move_uploaded_file($photo_tmp_name, '../uploads/users/' . $photo_name);

    //upload photo function with validataion
    $photo_data = fileUpload($_FILES['photo'], '../uploads/users/', ['jpg', 'jpeg', 'png', 'gif']);
    $photo_name = $photo_data['file_name'];

    //add user query
    $conn->query("INSERT INTO users (name, email, cell, photo) VALUES('$name', '$email', '$cell', '$photo_name')");
}

//when action value is view then show specific user details data 
if ($action == 'view') {
    $id = $_GET['id'];

    //view specific user data
    $data = $conn->query("SELECT * FROM users WHERE id='$id'");

    echo json_encode($data->fetch_assoc());
}

//when action value is delete then delete specific user data
if ($action == 'delete') {
    $id = $_GET['id'];

    //delete specific user data query
    $conn->query("DELETE FROM users WHERE id='$id'");
}
