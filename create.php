<?php
header('Content-Type: application/json');
require 'includes/database.php';
global $connection;

if (!isset($_COOKIE['remember_token'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$token = $_COOKIE['remember_token'];
$stmt = $connection->prepare("SELECT * FROM users WHERE remember_token = ?");
$stmt->bind_param('s', $token);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Invalid user']);
    exit;
}

$title = $_POST['title'] ?? '';
$body = $_POST['body'] ?? '';
$image = $_FILES['image'] ?? null;

if (empty($title) || empty($body) || !$image) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

$targetDir = 'storage/';
$filename = uniqid() . '_' . basename($image['name']);
$targetPath = $targetDir . $filename;

if (!move_uploaded_file($image['tmp_name'], $targetPath)) {
    echo json_encode(['success' => false, 'message' => 'Image upload failed']);
    exit;
}

$stmt = $connection->prepare("INSERT INTO posts (user_id, title, body, image_path) VALUES (?, ?, ?, ?)");
$stmt->bind_param('isss', $user['id'], $title, $body, $filename);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Post created successfully!',
        'post' => [
            'id' => $stmt->insert_id,
            'title' => $title,
            'body' => $body,
            'image_path' => $filename
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save post']);
}
