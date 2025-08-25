<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/utils.php';
require_once __DIR__ . '/../src/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        set_flash_message('CSRF token mismatch.', 'danger');
        header('Location: ../login.php');
        exit();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $database = new Database();
    $db = $database->connect();

    $query = "SELECT id, username, password, role FROM users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            header('Location: dashboard.php');
            exit();
        } else {
            set_flash_message('Invalid Credentials', 'danger');
            header('Location: ../login.php');
            exit();
        }
    } else {
        set_flash_message('Invalid Credentials', 'danger');
        header('Location: ../login.php');
        exit();
    }
} else {
    header('Location: ../login.php');
    exit();
}
?>