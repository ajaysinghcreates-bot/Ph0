<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->connect();

$query = "SELECT s.*, c.name as class_name FROM students s LEFT JOIN class c ON s.class_id = c.id ORDER BY s.id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Students</h1>
    <a href="student_add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Student</a>
</div>

<div class="card">
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $student['id']; ?></td>
                    <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($student['class_name']); ?></td>
                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                    <td><?php echo htmlspecialchars($student['phone_number']); ?></td>
                    <td>
                        <a href="student_view.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        <a href="student_edit.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="student_delete.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>