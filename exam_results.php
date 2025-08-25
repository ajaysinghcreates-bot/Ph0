<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['exam_id'])) {
    header('Location: exams.php?error=No exam selected');
    exit();
}

$exam_id = $_GET['exam_id'];
$database = new Database();
$db = $database->connect();

// Fetch exam details
$exam_query = "SELECT e.name, e.max_marks, c.name as class_name, s.name as subject_name, e.class_id FROM exams e JOIN class c ON e.class_id = c.id JOIN subjects s ON e.subject_id = s.id WHERE e.id = :exam_id";
$exam_stmt = $db->prepare($exam_query);
$exam_stmt->bindParam(':exam_id', $exam_id);
$exam_stmt->execute();
$exam = $exam_stmt->fetch(PDO::FETCH_ASSOC);

if (!$exam) {
    header('Location: exams.php?error=Exam not found');
    exit();
}

// Fetch students in the class
$student_query = "SELECT s.id, s.first_name, s.last_name, er.marks_obtained, er.comments FROM students s LEFT JOIN exam_results er ON s.id = er.student_id AND er.exam_id = :exam_id WHERE s.class_id = :class_id ORDER BY s.first_name ASC";
$student_stmt = $db->prepare($student_query);
$student_stmt->bindParam(':exam_id', $exam_id);
$student_stmt->bindParam(':class_id', $exam['class_id']);
$student_stmt->execute();
$students = $student_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Enter Results for <?php echo htmlspecialchars($exam['name']); ?></h1>
<p class="lead">Class: <?php echo htmlspecialchars($exam['class_name']); ?> | Subject: <?php echo htmlspecialchars($exam['subject_name']); ?> | Max Marks: <?php echo $exam['max_marks']; ?></p>
<hr>

<div class="card">
    <div class="card-body">
        <form action="../src/result_handler.php" method="POST">
            <?php csrf_field(); ?>
            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Marks Obtained</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                        <td>
                            <input type="hidden" name="results[<?php echo $student['id']; ?>][student_id]" value="<?php echo $student['id']; ?>">
                            <input type="number" class="form-control" name="results[<?php echo $student['id']; ?>][marks]" value="<?php echo htmlspecialchars($student['marks_obtained']); ?>" max="<?php echo $exam['max_marks']; ?>" min="0">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="results[<?php echo $student['id']; ?>][comments]" value="<?php echo htmlspecialchars($student['comments']); ?>">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Save Results</button>
            <a href="exams.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>