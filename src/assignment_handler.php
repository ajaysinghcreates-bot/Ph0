<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    set_flash_message('Unauthorized access', 'danger');
    header('Location: ../public/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        set_flash_message('CSRF token mismatch.', 'danger');
        header('Location: ../public/teacher_assignments.php');
        exit();
    }

    $teacher_id = $_POST['teacher_id'];
    $subject_ids = $_POST['subject_ids'];

    if (empty($teacher_id) || empty($subject_ids)) {
        header('Location: ../public/teacher_assignments.php?error=Teacher and subjects are required.');
        exit();
    }

    $database = new Database();
    $db = $database->connect();

    // Start a transaction
    $db->beginTransaction();

    try {
        // 1. Delete existing assignments for this teacher
        $delete_query = "DELETE FROM teacher_subjects WHERE teacher_id = :teacher_id";
        $delete_stmt = $db->prepare($delete_query);
        $delete_stmt->bindParam(':teacher_id', $teacher_id);
        $delete_stmt->execute();

        // 2. Insert new assignments
        $insert_query = "INSERT INTO teacher_subjects (teacher_id, subject_id) VALUES (:teacher_id, :subject_id)";
        $insert_stmt = $db->prepare($insert_query);

        foreach ($subject_ids as $subject_id) {
            $insert_stmt->bindParam(':teacher_id', $teacher_id);
            $insert_stmt->bindParam(':subject_id', $subject_id);
            $insert_stmt->execute();
        }

        // Commit the transaction
        $db->commit();

        set_flash_message('Assignments saved successfully', 'success');
        header('Location: ../public/teacher_assignments.php');

    } catch (Exception $e) {
        // Roll back the transaction if something failed
        $db->rollBack();
        set_flash_message('Failed to save assignments: ' . $e->getMessage(), 'danger');
        header('Location: ../public/teacher_assignments.php');
    }

} else {
    header('Location: ../public/teacher_assignments.php');
    exit();
}
?>