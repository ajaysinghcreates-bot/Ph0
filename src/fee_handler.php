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
        header('Location: ../public/fees.php');
        exit();
    }

    $student_id = $_POST['student_id'];
    $class_id = $_POST['class_id'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $payment_date = !empty($_POST['payment_date']) ? $_POST['payment_date'] : null;

    if (empty($class_id)) {
        header('Location: ../public/fee_add.php?error=Student is not assigned to a class.');
        exit();
    }

    $database = new Database();
    $db = $database->connect();

    $query = "INSERT INTO fees (student_id, class_id, amount, status, due_date, payment_date) VALUES (:student_id, :class_id, :amount, :status, :due_date, :payment_date)";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':class_id', $class_id);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':due_date', $due_date);
    $stmt->bindParam(':payment_date', $payment_date);

    if ($stmt->execute()) {
        set_flash_message('Fee payment added successfully', 'success');
        header('Location: ../public/fees.php');
    } else {
        set_flash_message('Failed to add fee payment', 'danger');
        header('Location: ../public/fee_add.php');
    }
} else {
    header('Location: ../public/fee_add.php');
    exit();
}
?>