<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/src/utils.php';
require_once __DIR__ . '/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        set_flash_message('CSRF token mismatch.', 'danger');
        header('Location: ../public/classes.php');
        exit();
    }

    $id = $_POST['id'];
    $name = $_POST['name'];

    $database = new Database();
    $db = $database->connect();

    $query = "UPDATE class SET name = :name WHERE id = :id";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);

    if ($stmt->execute()) {
        set_flash_message('Class updated successfully', 'success');
        header('Location: ../public/classes.php');
    } else {
        set_flash_message('Failed to update class', 'danger');
        header('Location: ../public/class_edit.php?id=' . $id);
    }
} else {
    header('Location: ../public/classes.php');
    exit();
}
?>