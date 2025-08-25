<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: classes.php');
    exit();
}

$id = $_GET['id'];
$database = new Database();
$db = $database->connect();

$query = "SELECT * FROM class WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$class = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$class) {
    set_flash_message('Class not found', 'danger');
    header('Location: classes.php');
    exit();
}
?>

<h1>Edit Class</h1>
<hr>

<div class="card">
    <div class="card-body">
        <form action="../src/class_update_handler.php" method="POST">
            <?php csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $class['id']; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Class Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($class['name']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Class</button>
            <a href="classes.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>