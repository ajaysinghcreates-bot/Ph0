<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: students.php');
    exit();
}

$id = $_GET['id'];
$database = new Database();
$db = $database->connect();

// Fetch student
$query = "SELECT * FROM students WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header('Location: students.php?error=Student not found');
    exit();
}

// Fetch classes
$query = "SELECT * FROM class ORDER BY name ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Edit Student</h1>
<hr>

<div class="card">
    <div class="card-body">
        <form action="../src/student_update_handler.php" method="POST">
            <?php csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($student['phone_number']); ?>">
                </div>
            </div>
            <div class="row">
                 <div class="col-md-6 mb-3">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo $student['date_of_birth']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" id="gender" name="gender" required>
                        <option value="Male" <?php echo ($student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($student['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($student['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="class_id" class="form-label">Class</label>
                    <select class="form-select" id="class_id" name="class_id">
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>" <?php echo ($student['class_id'] == $class['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($class['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($student['address']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Student</button>
            <a href="students.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>