<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/src/utils.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    set_flash_message('Unauthorized access', 'danger');
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prevent admin from deleting their own account
    if ($_SESSION['user_id'] == $id) {
        set_flash_message('You cannot delete your own account.', 'danger');
        header('Location: users.php');
        exit();
    }

    $database = new Database();
    $db = $database->connect();

    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        set_flash_message('User deleted successfully', 'success');
    } else {
        set_flash_message('Failed to delete user.', 'danger');
    }
} else {
    set_flash_message('Invalid request', 'danger');
}
header('Location: users.php');
exit();
?>