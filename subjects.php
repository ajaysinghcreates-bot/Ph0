<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->connect();

$query = "SELECT s.id, s.name, c.name as class_name, u.first_name, u.last_name 
          FROM subjects s 
          JOIN class c ON s.class_id = c.id 
          LEFT JOIN teacher_subjects ts ON s.id = ts.subject_id
          LEFT JOIN users u ON ts.teacher_id = u.id
          ORDER BY c.name, s.name";
$stmt = $db->prepare($query);
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Subject Management</h1>
    <a href="subject_add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Subject</a>
</div>

<div class="card">
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject Name</th>
                    <th>Class</th>
                    <th>Assigned Teacher</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $subject): ?>
                <tr>
                    <td><?php echo $subject['id']; ?></td>
                    <td><?php echo htmlspecialchars($subject['name']); ?></td>
                    <td><?php echo htmlspecialchars($subject['class_name']); ?></td>
                    <td><?php echo htmlspecialchars($subject['first_name'] . ' ' . $subject['last_name']); ?></td>
                    <td>
                        <a href="subject_edit.php?id=<?php echo $subject['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="subject_delete.php?id=<?php echo $subject['id']; ?>" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>