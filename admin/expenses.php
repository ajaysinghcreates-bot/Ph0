<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$database = new Database();
$db = $database->connect();

$query = "SELECT * FROM expenses ORDER BY date DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Expense Management</h1>
    <a href="expense_add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Expense</a>
</div>

<div class="card">
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($expenses as $expense): ?>
                <tr>
                    <td><?php echo $expense['id']; ?></td>
                    <td><?php echo htmlspecialchars($expense['title']); ?></td>
                    <td>$<?php echo number_format($expense['amount'], 2); ?></td>
                    <td><?php echo $expense['date']; ?></td>
                    <td>
                        <a href="expense_edit.php?id=<?php echo $expense['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="expense_delete.php?id=<?php echo $expense['id']; ?>" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>