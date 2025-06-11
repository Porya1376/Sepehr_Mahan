<?php
global $connection;
require 'includes/database.php';

$response = ['success' => false, 'message' => 'Something went wrong'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = htmlspecialchars($_POST['title']);
    $body = htmlspecialchars($_POST['body']);
    $image_path = null;

    // Check if new image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $filename = uniqid() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "storage/" . $filename);
        $image_path = $filename;
    }

    if ($image_path) {
        $query = "UPDATE posts SET title=?, body=?, image_path=? WHERE id=?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("sssi", $title, $body, $image_path, $id);
    } else {
        $query = "UPDATE posts SET title=?, body=? WHERE id=?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ssi", $title, $body, $id);
    }

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Post updated successfully';
        $response['post'] = [
            'id' => $id,
            'title' => $title,
            'body' => $body,
            'image_path' => $image_path ?? $_POST['old_image_path'] ?? ''
        ];
    }
}
echo json_encode($response);
