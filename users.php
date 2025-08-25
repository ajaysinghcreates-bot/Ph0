<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    set_flash_message('You do not have permission to access this page.', 'danger');
    header('Location: dashboard.php');
    exit();
}

$database = new Database();
$db = $database->connect();

$query = "SELECT id, username, first_name, last_name, email, role, is_active FROM users ORDER BY username ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>User Management</h1>
    <a href="user_add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add User</a>
</div>

<div class="card">
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <?php if ($user['is_active']): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="user_edit.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <?php if ($_SESSION['user_id'] !== $user['id']): // Prevent admin from deleting themselves ?>
                            <a href="user_delete.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>