<?php
session_start();
require_once __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php"><?php echo SITE_NAME; ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/utils.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/theme.css">
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <h3><?php echo SITE_NAME; ?></h3>
        </div>

        <ul class="list-unstyled components">
            <li>
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li>
                <a href="students.php"><i class="fas fa-user-graduate"></i> Students</a>
            </li>
            <li>
                <a href="#academicsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-university"></i> Academics</a>
                <ul class="collapse list-unstyled" id="academicsSubmenu">
                    <li><a href="classes.php">Classes</a></li>
                    <li><a href="subjects.php">Subjects</a></li>
                    <li><a href="teacher_assignments.php">Assignments</a></li>
                    <li><a href="exams.php">Examinations</a></li>
                </ul>
            </li>
            <li>
                <a href="#financeSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-money-bill-wave"></i> Finance</a>
                <ul class="collapse list-unstyled" id="financeSubmenu">
                    <li><a href="fees.php">Fee Payments</a></li>
                    <li><a href="expenses.php">Expenses</a></li>
                </ul>
            </li>
            <li>
                <a href="#adminSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-cogs"></i> Administration</a>
                <ul class="collapse list-unstyled" id="adminSubmenu">
                    <li><a href="users.php">User Management</a></li>
                </ul>
            </li>
            <li>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-info">
                    <i class="fas fa-align-left"></i>
                </button>
                <span class="navbar-text">
                    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                </span>
            </div>
        </nav>

        <?php display_flash_message(); ?>
        </div>
    </div>
</nav>

<div class="container mt-4">