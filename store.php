<?php
global $connection;
require 'includes/database.php';


if(isset($_POST['add_posts'])){
    $fname = $_POST['f_name'];
    $image = $_POST['image'];

    if ($fname == "" || empty($fname) ) {

        header('location:post.php?message=you need to fill name!');
    }
    else{
        $query = "INSERT INTO posts(name,image_path) VALUES('$fname','$image')";
        $result = mysqli_query($connection, $query);
        if(!$result){
            die("Query Failed: ".mysqli_error($connection));
        }
        else{
            header('location:post.php?insert_msg=You data has been added successfully!');
        }

    }

}