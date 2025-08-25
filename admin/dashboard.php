<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../src/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$database = new Database();
$db = $database->connect();

// Fetch stats
$student_count = $db->query("SELECT count(*) FROM students")->fetchColumn();
$staff_count = $db->query("SELECT count(*) FROM users WHERE role = 'Staff'")->fetchColumn();
$class_count = $db->query("SELECT count(*) FROM class")->fetchColumn();
$total_income = $db->query("SELECT SUM(amount) FROM fees WHERE status = 'Paid'")->fetchColumn();
$total_expenses = $db->query("SELECT SUM(amount) FROM expenses")->fetchColumn();

// Data for gender chart
$gender_data_query = "SELECT gender, COUNT(*) as count FROM students GROUP BY gender";
$gender_stmt = $db->prepare($gender_data_query);
$gender_stmt->execute();
$gender_data = $gender_stmt->fetchAll(PDO::FETCH_ASSOC);

$gender_labels = [];
$gender_counts = [];
foreach ($gender_data as $row) {
    $gender_labels[] = $row['gender'];
    $gender_counts[] = $row['count'];
}

?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Dashboard</h1>
    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
</div>
<hr>

<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user-graduate"></i> Total Students</h5>
                <p class="card-text fs-4"><?php echo $student_count; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user-tie"></i> Total Staff</h5>
                <p class="card-text fs-4"><?php echo $staff_count; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-chalkboard"></i> Total Classes</h5>
                <p class="card-text fs-4"><?php echo $class_count; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-dollar-sign"></i> Net Balance</h5>
                <p class="card-text fs-4">$<?php echo number_format($total_income - $total_expenses, 2); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Financial Overview</div>
            <div class="card-body">
                <canvas id="financialChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Student Demographics</div>
            <div class="card-body">
                <canvas id="genderChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Financial Chart (Bar)
    const financialCtx = document.getElementById('financialChart').getContext('2d');
    new Chart(financialCtx, {
        type: 'bar',
        data: {
            labels: ['Total Income', 'Total Expenses'],
            datasets: [{
                label: 'Amount ($)',
                data: [<?php echo $total_income ?? 0; ?>, <?php echo $total_expenses ?? 0; ?>],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Gender Chart (Pie)
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($gender_labels); ?>,
            datasets: [{
                label: 'Students',
                data: <?php echo json_encode($gender_counts); ?>,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 206, 86, 0.5)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>