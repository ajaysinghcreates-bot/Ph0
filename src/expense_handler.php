<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        set_flash_message('CSRF token mismatch.', 'danger');
        header('Location: ../admin/expenses.php');
        exit();
    }

    $title = $_POST['title'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    $database = new Database();
    $db = $database->connect();

    $query = "INSERT INTO expenses (title, description, amount, date) VALUES (:title, :description, :amount, :date)";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':date', $date);

    if ($stmt->execute()) {
        set_flash_message('Expense added successfully', 'success');
        header('Location: ../admin/expenses.php');
    } else {
        set_flash_message('Failed to add expense', 'danger');
        header('Location: ../admin/expense_add.php');
    }
} else {
    header('Location: ../admin/expense_add.php');
    exit();
}
?>