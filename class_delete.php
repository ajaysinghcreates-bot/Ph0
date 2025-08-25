<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/src/utils.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    set_flash_message('Unauthorized access', 'danger');
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $database = new Database();
    $db = $database->connect();

    $query = "DELETE FROM class WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        set_flash_message('Class deleted successfully', 'success');
    } else {
        set_flash_message('Failed to delete class. It might be in use by students or subjects.', 'danger');
    }
} else {
    set_flash_message('Invalid request', 'danger');
}
header('Location: classes.php');
exit();
?>