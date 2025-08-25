<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->connect();

// Fetch teachers (users with role 'Staff')
$teacher_query = "SELECT id, first_name, last_name FROM users WHERE role = 'Staff' ORDER BY first_name ASC";
$teacher_stmt = $db->prepare($teacher_query);
$teacher_stmt->execute();
$teachers = $teacher_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch subjects
$subject_query = "SELECT s.id, s.name, c.name as class_name FROM subjects s JOIN class c ON s.class_id = c.id ORDER BY c.name, s.name";
$subject_stmt = $db->prepare($subject_query);
$subject_stmt->execute();
$subjects = $subject_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch current assignments
$assignment_query = "SELECT u.first_name, u.last_name, s.name as subject_name, c.name as class_name FROM teacher_subjects ts JOIN users u ON ts.teacher_id = u.id JOIN subjects s ON ts.subject_id = s.id JOIN class c ON s.class_id = c.id WHERE u.role = 'Staff'";
$assignment_stmt = $db->prepare($assignment_query);
$assignment_stmt->execute();
$assignments = $assignment_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Teacher-Subject Assignments</h1>
<hr>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h4>Assign Subjects to Teacher</h4></div>
            <div class="card-body">
                <form action="../src/assignment_handler.php" method="POST">
                    <?php csrf_field(); ?>
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Teacher</label>
                        <select class="form-select" id="teacher_id" name="teacher_id" required>
                            <option value="">Select Teacher</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?php echo $teacher['id']; ?>"><?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subject_ids" class="form-label">Subjects</label>
                        <select class="form-select" id="subject_ids" name="subject_ids[]" multiple required size="10">
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo $subject['id']; ?>"><?php echo htmlspecialchars($subject['class_name'] . ' - ' . $subject['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Hold Ctrl/Cmd to select multiple subjects.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Assignments</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h4>Current Assignments</h4></div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Class</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignments as $assignment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($assignment['first_name'] . ' ' . $assignment['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($assignment['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($assignment['class_name']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>