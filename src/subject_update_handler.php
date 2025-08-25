<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        set_flash_message('CSRF token mismatch.', 'danger');
        header('Location: ../public/subjects.php');
        exit();
    }

    $id = $_POST['id'];
    $name = $_POST['name'];
    $class_id = $_POST['class_id'];

    $database = new Database();
    $db = $database->connect();

    $query = "UPDATE subjects SET name = :name, class_id = :class_id WHERE id = :id";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':class_id', $class_id);

    if ($stmt->execute()) {
        set_flash_message('Subject updated successfully', 'success');
        header('Location: ../public/subjects.php');
    } else {
        set_flash_message('Failed to update subject', 'danger');
        header('Location: ../public/subject_edit.php?id=' . $id);
    }
} else {
    header('Location: ../public/subjects.php');
    exit();
}
?>