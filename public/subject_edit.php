<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: subjects.php');
    exit();
}

$id = $_GET['id'];
$database = new Database();
$db = $database->connect();

// Fetch subject
$query = "SELECT * FROM subjects WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$subject = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$subject) {
    header('Location: subjects.php?error=Subject not found');
    exit();
}

// Fetch classes
$class_query = "SELECT * FROM class ORDER BY name ASC";
$class_stmt = $db->prepare($class_query);
$class_stmt->execute();
$classes = $class_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Edit Subject</h1>
<hr>

<div class="card">
    <div class="card-body">
        <form action="../src/subject_update_handler.php" method="POST">
            <?php csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $subject['id']; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($subject['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="class_id" class="form-label">Class</label>
                <select class="form-select" id="class_id" name="class_id" required>
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>" <?php echo ($subject['class_id'] == $class['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($class['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Subject</button>
            <a href="subjects.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>