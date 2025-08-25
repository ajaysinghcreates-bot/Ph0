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
        header('Location: ../admin/subjects.php');
        exit();
    }

    $name = $_POST['name'];
    $class_id = $_POST['class_id'];

    $database = new Database();
    $db = $database->connect();

    $query = "INSERT INTO subjects (name, class_id) VALUES (:name, :class_id)";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':class_id', $class_id);

    if ($stmt->execute()) {
        set_flash_message('Subject added successfully', 'success');
        header('Location: ../admin/subjects.php');
    } else {
        set_flash_message('Failed to add subject', 'danger');
        header('Location: ../admin/subject_add.php');
    }
} else {
    header('Location: ../admin/subject_add.php');
    exit();
}
?>