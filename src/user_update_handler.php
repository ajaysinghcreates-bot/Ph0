<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/utils.php';
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

    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = isset($_POST['role']) ? $_POST['role'] : null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $database = new Database();
    $db = $database->connect();

    // Prevent admin from changing their own role or status
    if ($_SESSION['user_id'] == $id) {
        $user_query = $db->prepare("SELECT role, is_active FROM users WHERE id = :id");
        $user_query->bindParam(':id', $id);
        $user_query->execute();
        $current_user = $user_query->fetch(PDO::FETCH_ASSOC);
        $role = $current_user['role'];
        $is_active = $current_user['is_active'];
    }

    $query = "UPDATE users SET first_name = :first_name, last_name = :last_name, username = :username, email = :email, role = :role, is_active = :is_active";
    if (!empty($password)) {
        $query .= ", password = :password";
    }
    $query .= " WHERE id = :id";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':is_active', $is_active);
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashed_password);
    }

    if ($stmt->execute()) {
        set_flash_message('User updated successfully', 'success');
        header('Location: ../public/users.php');
    } else {
        set_flash_message('Failed to update user. The username or email might already be taken.', 'danger');
        header('Location: ../public/user_edit.php?id=' . $id);
    }
} else {
    header('Location: ../public/users.php');
    exit();
}
?>