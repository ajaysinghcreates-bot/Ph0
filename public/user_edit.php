<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../src/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    set_flash_message('You do not have permission to access this page.', 'danger');
    header('Location: dashboard.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit();
}

$id = $_GET['id'];
$database = new Database();
$db = $database->connect();

$query = "SELECT * FROM users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    set_flash_message('User not found', 'danger');
    header('Location: users.php');
    exit();
}
?>

<h1>Edit User</h1>
<hr>

<div class="card">
    <div class="card-body">
        <form action="../src/user_update_handler.php" method="POST">
            <?php csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">New Password (optional)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                 <div class="col-md-6 mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required <?php echo ($_SESSION['user_id'] == $user['id']) ? 'disabled' : ''; ?>>
                        <option value="Admin" <?php echo ($user['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="Staff" <?php echo ($user['role'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
                        <option value="Viewer" <?php echo ($user['role'] == 'Viewer') ? 'selected' : ''; ?>>Viewer</option>
                    </select>
                </div>
            </div>
             <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?php echo ($user['is_active']) ? 'checked' : ''; ?> <?php echo ($_SESSION['user_id'] == $user['id']) ? 'disabled' : ''; ?>>
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="users.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>