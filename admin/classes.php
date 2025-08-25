<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$database = new Database();
$db = $database->connect();

$query = "SELECT c.*, u.username as teacher_name FROM class c LEFT JOIN users u ON c.teacher_id = u.id ORDER BY c.id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Classes</h1>
    <a href="class_add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Class</a>
</div>

<div class="card">
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Teacher</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                <tr>
                    <td><?php echo $class['id']; ?></td>
                    <td><?php echo htmlspecialchars($class['name']); ?></td>
                    <td><?php echo htmlspecialchars($class['teacher_name']); ?></td>
                    <td>
                        <a href="class_edit.php?id=<?php echo $class['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="class_delete.php?id=<?php echo $class['id']; ?>" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>