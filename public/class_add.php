<?php
require_once __DIR__ . '/../templates/header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<h1>Add New Class</h1>
<hr>

<div class="card">
    <div class="card-body">
        <form action="../src/class_handler.php" method="POST">
            <?php csrf_field(); ?>
            <div class="mb-3">
                <label for="name" class="form-label">Class Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Class</button>
            <a href="classes.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>