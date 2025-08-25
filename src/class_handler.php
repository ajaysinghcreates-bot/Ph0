<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        set_flash_message('CSRF token mismatch.', 'danger');
        header('Location: ../admin/classes.php');
        exit();
    }

    $name = $_POST['name'];

    $database = new Database();
    $db = $database->connect();

    $query = "INSERT INTO class (name) VALUES (:name)";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':name', $name);

    if ($stmt->execute()) {
        set_flash_message('Class added successfully', 'success');
        header('Location: ../admin/classes.php');
    } else {
        set_flash_message('Failed to add class', 'danger');
        header('Location: ../admin/class_add.php');
    }
} else {
    header('Location: ../admin/class_add.php');
    exit();
}
?>