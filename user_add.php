<?php
require_once __DIR__ . '/templates/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    set_flash_message('You do not have permission to access this page.', 'danger');
    header('Location: dashboard.php');
    exit();
}
?>

<h1>Add New User</h1>
<hr>

<div class="card">
    <div class="card-body">
        <form action="../src/user_handler.php" method="POST">
            <?php csrf_field(); ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="Admin">Admin</option>
                        <option value="Staff">Staff</option>
                        <option value="Viewer">Viewer</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3 form-check align-self-center">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                    <label class="form-check-label" for="is_active">Is Active</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add User</button>
            <a href="users.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>