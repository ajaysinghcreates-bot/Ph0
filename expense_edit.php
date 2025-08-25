<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: expenses.php');
    exit();
}

$id = $_GET['id'];
$database = new Database();
$db = $database->connect();

$query = "SELECT * FROM expenses WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$expense = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$expense) {
    set_flash_message('Expense not found', 'danger');
    header('Location: expenses.php');
    exit();
}
?>

<h1>Edit Expense</h1>
<hr>

<div class="card">
    <div class="card-body">
        <form action="../src/expense_update_handler.php" method="POST">
            <?php csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $expense['id']; ?>">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($expense['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($expense['description']); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?php echo $expense['amount']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?php echo $expense['date']; ?>" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Expense</button>
            <a href="expenses.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>