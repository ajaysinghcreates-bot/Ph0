<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->connect();

// Fetch classes
$query = "SELECT * FROM class ORDER BY name ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Add New Subject</h1>
<hr>

<div class="card">
    <div class="card-body">
        <form action="../src/subject_handler.php" method="POST">
            <?php csrf_field(); ?>
            <div class="mb-3">
                <label for="name" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="class_id" class="form-label">Class</label>
                <select class="form-select" id="class_id" name="class_id" required>
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Subject</button>
            <a href="subjects.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>