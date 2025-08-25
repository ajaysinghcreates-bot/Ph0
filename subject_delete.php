<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php?error=Unauthorized');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $database = new Database();
    $db = $database->connect();

    $query = "DELETE FROM subjects WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        set_flash_message('Subject deleted successfully', 'success');
        header('Location: subjects.php');
    } else {
        set_flash_message('Failed to delete subject', 'danger');
        header('Location: subjects.php');
    }
} else {
    header('Location: subjects.php');
    exit();
}
?>