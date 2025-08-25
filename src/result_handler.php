<?php
session_start();
require_once __DIR__ . '/../config.php';
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

    $exam_id = $_POST['exam_id'];
    $results = $_POST['results'];

    $database = new Database();
    $db = $database->connect();

    // Using INSERT ... ON DUPLICATE KEY UPDATE for an efficient upsert
    $query = "INSERT INTO exam_results (exam_id, student_id, marks_obtained, comments) VALUES (:exam_id, :student_id, :marks_obtained, :comments) ON DUPLICATE KEY UPDATE marks_obtained = :marks_obtained, comments = :comments";
    
    $stmt = $db->prepare($query);

    $db->beginTransaction();
    try {
        foreach ($results as $result) {
            if (!empty($result['marks'])) {
                $stmt->bindParam(':exam_id', $exam_id);
                $stmt->bindParam(':student_id', $result['student_id']);
                $stmt->bindParam(':marks_obtained', $result['marks']);
                $stmt->bindParam(':comments', $result['comments']);
                $stmt->execute();
            }
        }
        $db->commit();
        set_flash_message('Results saved successfully', 'success');
        header('Location: ../public/exams.php');
    } catch (Exception $e) {
        $db->rollBack();
        set_flash_message('Failed to save results', 'danger');
        header('Location: ../public/exam_results.php?exam_id=' . $exam_id);
    }

} else {
    header('Location: ../public/exams.php');
    exit();
}
?>