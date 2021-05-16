<?php

function fileUpload($file, $location = null, $format = ['jpg', 'jpeg', 'png', 'gif'])
{
    $photo_name = $file['name'];
    $photo_tmp_name = $file['tmp_name'];

    $file_array = explode('.', $photo_name);
    $extension = strtolower(end($file_array));

    $u_file = md5(time() . rand()) . '.' . $extension;

    //if extension is match from user extension then data upload otherwise data is not uploaded
    if (in_array($extension, $format)) {
        move_uploaded_file($photo_tmp_name, $location . $u_file);
    } else {
        $errorMas = 'File format is not allowed!';
    }

    return [
        'file_name' => $u_file,
        'errorMas' => $errorMas
    ];
}
