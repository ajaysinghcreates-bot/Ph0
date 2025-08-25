<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: exams.php');
    exit();
}

$id = $_GET['id'];
$database = new Database();
$db = $database->connect();

// Fetch exam
$query = "SELECT * FROM exams WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$exam = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exam) {
    set_flash_message('Exam not found', 'danger');
    header('Location: exams.php');
    exit();
}

// Fetch classes and subjects for dropdowns
$class_query = "SELECT * FROM class ORDER BY name ASC";
$class_stmt = $db->prepare($class_query);
$class_stmt->execute();
$classes = $class_stmt->fetchAll(PDO::FETCH_ASSOC);

$subject_query = "SELECT id, name, class_id FROM subjects ORDER BY name ASC";
$subject_stmt = $db->prepare($subject_query);
$subject_stmt->execute();
$subjects = $subject_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Edit Exam</h1>
<hr>

<div class="card">
    <div class="card-body">
        <form action="../src/exam_update_handler.php" method="POST">
            <?php csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $exam['id']; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Exam Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($exam['name']); ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="class_id" class="form-label">Class</label>
                    <select class="form-select" id="class_id" name="class_id" required>
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>" <?php echo ($exam['class_id'] == $class['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($class['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select class="form-select" id="subject_id" name="subject_id" required>
                        <option value="">Select Subject</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo $subject['id']; ?>" data-class-id="<?php echo $subject['class_id']; ?>" <?php echo ($exam['subject_id'] == $subject['id'] ? 'selected' : ''); ?>>
                                <?php echo htmlspecialchars($subject['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?php echo $exam['date']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="max_marks" class="form-label">Maximum Marks</label>
                    <input type="number" class="form-control" id="max_marks" name="max_marks" value="<?php echo $exam['max_marks']; ?>" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Exam</button>
            <a href="exams.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
// Script to filter subjects based on selected class
document.getElementById('class_id').addEventListener('change', function() {
    const classId = this.value;
    const subjectSelect = document.getElementById('subject_id');
    subjectSelect.value = '';

    for (let option of subjectSelect.options) {
        if (option.value === '') continue;
        if (option.dataset.classId === classId) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    }
});
// Trigger change on load to set initial state
document.getElementById('class_id').dispatchEvent(new Event('change'));
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>