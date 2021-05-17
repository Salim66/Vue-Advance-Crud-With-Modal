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
    $gender = $_POST['gender'];
    $location = $_POST['location'];

    // //get file
    // $photo_name = $_FILES['photo']['name'];
    // $photo_tmp_name = $_FILES['photo']['tmp_name'];

    // //move file into folder
    // move_uploaded_file($photo_tmp_name, '../uploads/users/' . $photo_name);

    //upload photo function with validataion
    $photo_data = fileUpload($_FILES['photo'], '../uploads/users/', ['jpg', 'jpeg', 'png', 'gif']);
    $photo_name = $photo_data['file_name'];

    //add user query
    $conn->query("INSERT INTO users (name, email, cell, gender, location, photo) VALUES('$name', '$email', '$cell', '$gender', '$location', '$photo_name')");
}

//when action value is view then show specific user details data 
if ($action == 'view') {
    $id = $_GET['id'];

    //find specific user data query
    $data = $conn->query("SELECT * FROM users WHERE id='$id'");

    echo json_encode($data->fetch_assoc());
}

//when action value is delete then delete specific user data
if ($action == 'delete') {
    $id = $_GET['id'];

    //find specific user data query
    $data = $conn->query("SELECT * FROM users WHERE id='$id'");

    //user photo unlink when user data delete
    while ($user = $data->fetch_assoc()) {
        if (!empty($user['photo'])) {
            unlink('../uploads/users/' . $user['photo']);
        }
    }

    //delete specific user data query
    $conn->query("DELETE FROM users WHERE id='$id'");
}

//when action value is edit then show sigle user details form
if ($action == 'edit') {
    $id = $_GET['id'];

    //find specific user data query
    $data = $conn->query("SELECT * FROM users WHERE id='$id'");

    echo json_encode($data->fetch_assoc());
}

//when action value is update then specific user data updated
if ($action == 'update') {
    //get values
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $cell = $_POST['cell'];
    $gender = $_POST['gender'];
    $location = $_POST['location'];
    $old_photo = $_POST['old_photo'];

    //find specific user because when i am update photo than old photo has been delete from directory
    $data = $conn->query("SELECT * FROM users WHERE id='$id'");

    $photo_name = '';
    if (!empty($_FILES['photo'])) {
        //upload photo function with validataion
        $photo_data = fileUpload($_FILES['photo'], '../uploads/users/', ['jpg', 'jpeg', 'png', 'gif']);
        $photo_name = $photo_data['file_name'];
        //user photo unlink when user data photo update
        while ($user = $data->fetch_assoc()) {
            if (!empty($user['photo'])) {
                unlink('../uploads/users/' . $user['photo']);
            }
        }
    } else {
        $photo_name = $old_photo;
    }

    $conn->query("UPDATE users SET name ='$name', email='$email', cell='$cell', gender='$gender', location='$location', photo='$photo_name' WHERE id='$id'");
}

//when action value is search then search specific data
if ($action == 'search') {
    $search = $_GET['search'];

    //real time search user data
    $all_data = $conn->query("SELECT * FROM users WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR cell LIKE '%$search%'");

    $data = [];

    while ($user = $all_data->fetch_assoc()) {
        array_push($data, $user);
    }

    echo json_encode($data);
}

// //when action value is search_gender then search specific data
// if ($action == 'search_gender') {
//     $search = $_GET['search_gender'];

//     //real time search user data
//     $all_data = $conn->query("SELECT * FROM users WHERE gender LIKE '$search%' ORDER BY id DESC");

//     $data = [];

//     while ($user = $all_data->fetch_assoc()) {
//         array_push($data, $user);
//     }

//     echo json_encode($data);
// }

// //when action value is search_location then search specific data
// if ($action == 'search_location') {
//     $search = $_GET['search_location'];

//     //real time search user data
//     $all_data = $conn->query("SELECT * FROM users WHERE location LIKE '$search%' ORDER BY id DESC");

//     $data = [];

//     while ($user = $all_data->fetch_assoc()) {
//         array_push($data, $user);
//     }

//     echo json_encode($data);
// }

//when action value is multiple then search specific data
if ($action == 'multiple') {
    $gender = $_POST['gender'];
    $location = $_POST['location'];

    //real time search user data
    $all_data = '';
    if (empty($gender) || empty($location)) {
        $all_data = $conn->query("SELECT * FROM users WHERE gender='$gender' OR location='$location' ORDER BY id DESC");
    } else {
        $all_data = $conn->query("SELECT * FROM users WHERE gender='$gender' AND location='$location' ORDER BY id DESC");
    }

    $data = [];

    while ($user = $all_data->fetch_assoc()) {
        array_push($data, $user);
    }

    echo json_encode($data);
}
