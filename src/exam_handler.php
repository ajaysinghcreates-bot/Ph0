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
        header('Location: ../public/exams.php');
        exit();
    }

    $name = $_POST['name'];
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $date = $_POST['date'];
    $max_marks = $_POST['max_marks'];

    $database = new Database();
    $db = $database->connect();

    $query = "INSERT INTO exams (name, class_id, subject_id, date, max_marks) VALUES (:name, :class_id, :subject_id, :date, :max_marks)";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':class_id', $class_id);
    $stmt->bindParam(':subject_id', $subject_id);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':max_marks', $max_marks);

    if ($stmt->execute()) {
        set_flash_message('Exam added successfully', 'success');
        header('Location: ../public/exams.php');
    } else {
        set_flash_message('Failed to add exam', 'danger');
        header('Location: ../public/exam_add.php');
    }
} else {
    header('Location: ../public/exam_add.php');
    exit();
}
?>