<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/src/utils.php';
require_once __DIR__ . '/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        set_flash_message('CSRF token mismatch.', 'danger');
        header('Location: ../public/register.php');
        exit();
    }

    // 1. Validate Passcode
    if (!isset($_POST['passcode']) || $_POST['passcode'] !== '623264') {
        set_flash_message('Invalid passcode for registration.', 'danger');
        header('Location: ../public/register.php');
        exit();
    }

    // 2. Validate Password Match
    if ($_POST['password'] !== $_POST['confirm_password']) {
        set_flash_message('Passwords do not match.', 'danger');
        // This is tricky because we need to send the passcode back to show the form
        // For simplicity, we redirect to the initial state. A better UX would use sessions.
        header('Location: ../public/register.php');
        exit();
    }

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'Admin'; // Hard-coded role
    $is_active = 1; // Admins are active by default

    $database = new Database();
    $db = $database->connect();

    // 3. Check for existing user
    $check_query = "SELECT id FROM users WHERE username = :username OR email = :email";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':username', $username);
    $check_stmt->bindParam(':email', $email);
    $check_stmt->execute();
    if ($check_stmt->rowCount() > 0) {
        set_flash_message('Username or email already exists.', 'danger');
        header('Location: ../public/register.php');
        exit();
    }

    // 4. Insert new admin user
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
        set_flash_message('Admin user created successfully. You can now log in.', 'success');
        header('Location: ../public/login.php');
    } else {
        set_flash_message('Failed to create admin user.', 'danger');
        header('Location: ../public/register.php');
    }
} else {
    header('Location: ../public/register.php');
    exit();
}
?>