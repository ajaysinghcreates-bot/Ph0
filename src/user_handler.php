<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/src/utils.php';
require_once __DIR__ . '/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    set_flash_message('Unauthorized access', 'danger');
    header('Location: ../public/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        set_flash_message('CSRF token mismatch.', 'danger');
        header('Location: ../public/users.php');
        exit();
    }

    // Validation
    if ($_POST['password'] !== $_POST['confirm_password']) {
        set_flash_message('Passwords do not match.', 'danger');
        header('Location: ../public/user_add.php');
        exit();
    }

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $database = new Database();
    $db = $database->connect();

    // Check for existing user
    $check_query = "SELECT id FROM users WHERE username = :username OR email = :email";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':username', $username);
    $check_stmt->bindParam(':email', $email);
    $check_stmt->execute();
    if ($check_stmt->rowCount() > 0) {
        set_flash_message('Username or email already exists.', 'danger');
        header('Location: ../public/user_add.php');
        exit();
    }

    $query = "INSERT INTO users (first_name, last_name, username, email, password, role, is_active) VALUES (:first_name, :last_name, :username, :email, :password, :role, :is_active)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':is_active', $is_active);

    if ($stmt->execute()) {
        set_flash_message('User added successfully', 'success');
        header('Location: ../public/users.php');
    } else {
        set_flash_message('Failed to add user', 'danger');
        header('Location: ../public/user_add.php');
    }
} else {
    header('Location: ../public/user_add.php');
    exit();
}
?>