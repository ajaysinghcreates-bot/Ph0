<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->connect();

// Fetch students
$student_query = "SELECT id, first_name, last_name, class_id FROM students ORDER BY first_name ASC";
$student_stmt = $db->prepare($student_query);
$student_stmt->execute();
$students = $student_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Add Fee Payment</h1>
<hr>

<div class="card">
    <div class="card-body">
        <form action="../src/fee_handler.php" method="POST">
            <?php csrf_field(); ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="student_id" class="form-label">Student</label>
                    <select class="form-select" id="student_id" name="student_id" required>
                        <option value="">Select Student</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>" data-class-id="<?php echo $student['class_id']; ?>">
                                <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="class_id" class="form-label">Class</label>
                    <input type="hidden" id="class_id_hidden" name="class_id">
                    <input type="text" class="form-control" id="class_name_display" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Paid">Paid</option>
                        <option value="Unpaid">Unpaid</option>
                        <option value="Partially Paid">Partially Paid</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="due_date" class="form-label">Due Date</label>
                    <input type="date" class="form-control" id="due_date" name="due_date">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="payment_date" class="form-label">Payment Date</label>
                    <input type="date" class="form-control" id="payment_date" name="payment_date">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Payment</button>
            <a href="fees.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
document.getElementById('student_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const classId = selectedOption.getAttribute('data-class-id');
    document.getElementById('class_id_hidden').value = classId;
    // You would typically fetch the class name via AJAX, but for simplicity we'll leave it blank
    document.getElementById('class_name_display').value = 'Class ID: ' + (classId || 'N/A');
});
</script>

<?php require_once __DIR__ . '/templates/footer.php'; ?>