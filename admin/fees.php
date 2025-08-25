<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$database = new Database();
$db = $database->connect();

$query = "SELECT f.*, s.first_name, s.last_name, c.name as class_name 
          FROM fees f 
          JOIN students s ON f.student_id = s.id 
          JOIN class c ON f.class_id = c.id 
          ORDER BY f.payment_date DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$fees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Fee Management</h1>
    <a href="fee_add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Fee Payment</a>
</div>

<div class="card">
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Payment Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fees as $fee): ?>
                <tr>
                    <td><?php echo $fee['id']; ?></td>
                    <td><?php echo htmlspecialchars($fee['first_name'] . ' ' . $fee['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($fee['class_name']); ?></td>
                    <td>$<?php echo number_format($fee['amount'], 2); ?></td>
                    <td><span class="badge bg-<?php echo $fee['status'] == 'Paid' ? 'success' : ($fee['status'] == 'Unpaid' ? 'danger' : 'warning'); ?>"><?php echo $fee['status']; ?></span></td>
                    <td><?php echo $fee['due_date']; ?></td>
                    <td><?php echo $fee['payment_date']; ?></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>