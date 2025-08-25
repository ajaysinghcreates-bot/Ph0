<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        set_flash_message('CSRF token mismatch.', 'danger');
        header('Location: ../public/students.php');
        exit();
    }

    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];

    $database = new Database();
    $db = $database->connect();

    $query = "UPDATE students SET first_name = :first_name, last_name = :last_name, email = :email, phone_number = :phone_number, date_of_birth = :date_of_birth, gender = :gender, address = :address, class_id = :class_id WHERE id = :id";

    $stmt = $db->prepare($query);

    // Bind data
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->bindParam(':date_of_birth', $date_of_birth);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':class_id', $class_id);

    if ($stmt->execute()) {
        set_flash_message('Student updated successfully', 'success');
        header('Location: ../public/students.php');
    } else {
        set_flash_message('Failed to update student', 'danger');
        header('Location: ../public/student_edit.php?id=' . $id);
    }
} else {
    header('Location: ../public/students.php');
    exit();
}
?>