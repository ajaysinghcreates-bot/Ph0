<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    set_flash_message('No student selected.', 'danger');
    header('Location: students.php');
    exit();
}

$student_id = $_GET['id'];
$database = new Database();
$db = $database->connect();

// Fetch student details
$student_query = "SELECT s.*, c.name as class_name FROM students s LEFT JOIN class c ON s.class_id = c.id WHERE s.id = :id";
$student_stmt = $db->prepare($student_query);
$student_stmt->bindParam(':id', $student_id);
$student_stmt->execute();
$student = $student_stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    set_flash_message('Student not found.', 'danger');
    header('Location: students.php');
    exit();
}

// Fetch fee history
$fee_query = "SELECT * FROM fees WHERE student_id = :student_id ORDER BY due_date DESC";
$fee_stmt = $db->prepare($fee_query);
$fee_stmt->bindParam(':student_id', $student_id);
$fee_stmt->execute();
$fees = $fee_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch exam results
$result_query = "SELECT er.marks_obtained, e.name as exam_name, e.max_marks, s.name as subject_name FROM exam_results er JOIN exams e ON er.exam_id = e.id JOIN subjects s ON e.subject_id = s.id WHERE er.student_id = :student_id ORDER BY e.date DESC";
$result_stmt = $db->prepare($result_query);
$result_stmt->bindParam(':student_id', $student_id);
$result_stmt->execute();
$results = $result_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Student Profile</h1>
    <a href="students.php" class="btn btn-secondary">Back to Student List</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h4><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h4></div>
            <div class="card-body">
                <p><strong>Class:</strong> <?php echo htmlspecialchars($student['class_name'] ?? 'N/A'); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone_number']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($student['gender']); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo $student['date_of_birth']; ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($student['address']); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h4>Exam Results</h4></div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Exam</th><th>Subject</th><th>Marks</th></tr></thead>
                    <tbody>
                        <?php foreach($results as $result): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($result['exam_name']); ?></td>
                            <td><?php echo htmlspecialchars($result['subject_name']); ?></td>
                            <td><?php echo $result['marks_obtained']; ?> / <?php echo $result['max_marks']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($results)): ?>
                            <tr><td colspan="3">No results found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h4>Fee History</h4></div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Amount</th><th>Status</th><th>Due Date</th><th>Payment Date</th></tr></thead>
                    <tbody>
                        <?php foreach($fees as $fee): ?>
                        <tr>
                            <td>$<?php echo number_format($fee['amount'], 2); ?></td>
                            <td><span class="badge bg-<?php echo $fee['status'] == 'Paid' ? 'success' : ($fee['status'] == 'Unpaid' ? 'danger' : 'warning'); ?>"><?php echo $fee['status']; ?></span></td>
                            <td><?php echo $fee['due_date']; ?></td>
                            <td><?php echo $fee['payment_date']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($fees)): ?>
                            <tr><td colspan="4">No fee history found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>