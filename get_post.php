<?php
global $connection;
require 'includes/database.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Missing ID']);
    exit;
}

$id = $_GET['id'];
$stmt = $connection->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($post);
