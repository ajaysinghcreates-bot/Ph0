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
        header('Location: ../public/expenses.php');
        exit();
    }

    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    $database = new Database();
    $db = $database->connect();

    $query = "UPDATE expenses SET title = :title, description = :description, amount = :amount, date = :date WHERE id = :id";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':date', $date);

    if ($stmt->execute()) {
        set_flash_message('Expense updated successfully', 'success');
        header('Location: ../public/expenses.php');
    } else {
        set_flash_message('Failed to update expense', 'danger');
        header('Location: ../public/expense_edit.php?id=' . $id);
    }
} else {
    header('Location: ../public/expenses.php');
    exit();
}
?>