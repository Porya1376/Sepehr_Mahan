<?php

global $connection;
require 'includes/database.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    try {
        $query = "DELETE FROM posts WHERE id = '$id'";
        $result = mysqli_query($connection, $query);
        echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}