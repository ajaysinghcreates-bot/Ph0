<?php
require_once __DIR__ . '/../templates/header.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
} else {
    header('Location: login.php');
    exit();
}
?>