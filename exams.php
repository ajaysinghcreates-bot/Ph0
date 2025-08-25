<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->connect();

$query = "SELECT e.id, e.name, e.date, e.max_marks, c.name as class_name, s.name as subject_name 
          FROM exams e
          JOIN class c ON e.class_id = c.id
          JOIN subjects s ON e.subject_id = s.id
          ORDER BY e.date DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Examinations</h1>
    <a href="exam_add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Exam</a>
</div>

<div class="card">
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Exam Name</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Max Marks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exams as $exam): ?>
                <tr>
                    <td><?php echo htmlspecialchars($exam['name']); ?></td>
                    <td><?php echo htmlspecialchars($exam['class_name']); ?></td>
                    <td><?php echo htmlspecialchars($exam['subject_name']); ?></td>
                    <td><?php echo $exam['date']; ?></td>
                    <td><?php echo $exam['max_marks']; ?></td>
                    <td>
                        <a href="exam_results.php?exam_id=<?php echo $exam['id']; ?>" class="btn btn-sm btn-success"><i class="fas fa-list-ol"></i> Results</a>
                        <a href="exam_edit.php?id=<?php echo $exam['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="exam_delete.php?id=<?php echo $exam['id']; ?>" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>