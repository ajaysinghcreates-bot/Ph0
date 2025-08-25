<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: ../login.php?error=Unauthorized');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $database = new Database();
    $db = $database->connect();

    $query = "DELETE FROM students WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        set_flash_message('Student deleted successfully', 'success');
        header('Location: students.php');
    } else {
        set_flash_message('Failed to delete student', 'danger');
        header('Location: students.php');
    }
} else {
    header('Location: students.php');
    exit();
}
?>