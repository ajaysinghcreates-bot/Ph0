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

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $enrollment_date = date('Y-m-d'); // Set enrollment date to today

    $database = new Database();
    $db = $database->connect();

    $query = "INSERT INTO students (first_name, last_name, email, phone_number, date_of_birth, gender, address, class_id, enrollment_date) VALUES (:first_name, :last_name, :email, :phone_number, :date_of_birth, :gender, :address, :class_id, :enrollment_date)";

    $stmt = $db->prepare($query);

    // Bind data
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->bindParam(':date_of_birth', $date_of_birth);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':class_id', $class_id);
    $stmt->bindParam(':enrollment_date', $enrollment_date);

    if ($stmt->execute()) {
        set_flash_message('Student added successfully', 'success');
        header('Location: ../public/students.php');
    } else {
        set_flash_message('Failed to add student', 'danger');
        header('Location: ../public/student_add.php');
    }
} else {
    header('Location: ../public/student_add.php');
    exit();
}
?>