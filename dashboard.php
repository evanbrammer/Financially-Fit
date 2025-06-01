<?php
session_start();
require 'includes/database_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] === 'admin') {
    header("Location: admin_panel.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$budget = [
    'income' => '',
    'housing' => '',
    'car' => '',
    'groceries' => '',
    'retirement' => '',
    'spending_money' => ''
];



// Load existing budget if available
$stmt = $pdo->prepare("SELECT * FROM budget WHERE user_id = ?");
$stmt->execute([$user_id]);
$existing = $stmt->fetch();

if ($existing) {
    $budget = array_merge($budget, $existing);
}

$total_budgeted = (
    floatval($budget['housing']) +
    floatval($budget['car']) +
    floatval($budget['groceries']) +
    floatval($budget['retirement']) +
    floatval($budget['spending_money'])
                    );

$leftover = floatval($budget['income']) - $total_budgeted;
$is_over_budget = $leftover < 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_budget'])) { //was income  changing to update_budget 
    $budget = [
        'income' => (float)$_POST['income'],
        'housing' => (float)$_POST['housing'],
        'car' => (float)$_POST['car'],
        'groceries' => (float)$_POST['groceries'],
        'retirement' => (float)$_POST['retirement'],
        'spending_money' => (float)$_POST['spending_money']
    ];

    

    // Save to main budget table
    if ($existing) {
        $stmt = $pdo->prepare("UPDATE budget SET income = ?, housing = ?, car = ?, groceries = ?, retirement = ?, spending_money = ? WHERE user_id = ?");
        $stmt->execute([
            $budget['income'],
            $budget['housing'],
            $budget['car'],
            $budget['groceries'],
            $budget['retirement'],
            $budget['spending_money'],
            $user_id
        ]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO budget (user_id, income, housing, car, groceries, retirement, spending_money) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user_id,
            $budget['income'],
            $budget['housing'],
            $budget['car'],
            $budget['groceries'],
            $budget['retirement'],
            $budget['spending_money']
        ]);
    }

    // Now insert into history
    $currentYear = date('Y');
    $currentMonth = date('n');

    $check = $pdo->prepare("SELECT id FROM budget_history WHERE user_id = ? AND year = ? AND month = ?");
    $check->execute([$user_id, $currentYear, $currentMonth]);

    if ($check->rowCount() === 0) {
        $insertHistory = $pdo->prepare("
            INSERT INTO budget_history 
            (user_id, year, month, income, housing, car, groceries, retirement, spending_money, leftover) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $insertHistory->execute([
            $user_id,
            $currentYear,
            $currentMonth,
            $budget['income'],
            $budget['housing'],
            $budget['car'],
            $budget['groceries'],
            $budget['retirement'],
            $budget['spending_money'],
            $leftover
        ]);
    }
}



// calculation for compound interest
$growthData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate_interest'])) {
    $monthly_contribution = max(0, $leftover);
    $annual_rate = floatval($_POST['rate']) / 100;
    $years = intval($_POST['years']);
    $monthly_rate = $annual_rate / 12;

    $total_months = $years * 12;

    // calculation for the monthly amount and interest 
    for ($i = 0; $i <= $total_months; $i++) {
        $fv = $monthly_contribution * ((pow(1 + $monthly_rate, $i) - 1) / $monthly_rate);
        $growthData[] = round($fv, 2);
    }
    $finalTotal = round($fv, 2); // store the final total so we can show it below our line graph
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/user_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="dashboard-container">
    <h2>Welcome to your dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>Your role: <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong></p>

    <div class="budget-section">
    <form class="budget-form" method="POST">
    <h3>Enter Your Monthly Budget Plan</h3>
    <label>Income: <input type="number" step="0.01" name="income" value="<?= htmlspecialchars($budget['income']) ?>" required></label>
    <label>Housing: <input type="number" step="0.01" name="housing" value="<?= htmlspecialchars($budget['housing']) ?>"></label>
    <label>Car: <input type="number" step="0.01" name="car" value="<?= htmlspecialchars($budget['car']) ?>"></label>
    <label>Groceries: <input type="number" step="0.01" name="groceries" value="<?= htmlspecialchars($budget['groceries']) ?>"></label>
    <label>Retirement Savings: <input type="number" step="0.01" name="retirement" value="<?= htmlspecialchars($budget['retirement']) ?>"></label>
    <label>Spending Money: <input type="number" step="0.01" name="spending_money" value="<?= htmlspecialchars($budget['spending_money']) ?>"></label>
    <button type="submit" name="update_budget">Update Chart</button>
</form>

        <div class="budget-chart">
            <canvas id="budgetChart"></canvas>

            <?php if ($is_over_budget): ?>
                <p style="color: red; font-weight: bold; margin-top: 15px;">
                    Warning: You are over your income budget!
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="compound-section">
    <form method="POST" class="compound-form">
        <h3>Compound Interest Calculator</h3>

        <!-- using monthly leftover "sabings -->
        <p><strong>Using your current monthly savings: $<?= number_format(max(0, $leftover), 2) ?></strong></p>

        <label>Annual Interest Rate (%):
            <input type="number" step="0.01" name="rate" required>
        </label>
        <br>
        <label>Years:
            <input type="number" name="years" required>
        </label>
        <br>
        <button type="submit" name="calculate_interest">Calculate</button>
    </form>

    <div class="compound-chart">
        <canvas id="interestChart"></canvas>
        
        <?php if (!empty($growthData)): ?>
        
            <p style="margin-top: 15px; font-weight: bold;">
        
            Projected Total: $<?= number_format($finalTotal, 2) ?>
        
            </p>
<?php endif; ?>
    </div>
</div>

</div>

<script>
const ctx = document.getElementById('budgetChart').getContext('2d');
const budgetChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [
            'Housing', 
            'Car', 
            'Groceries', 
            'Retirement', 
            'Spending Money', 
            <?= $is_over_budget ? "'Over Budget'" : "'Savings'" ?>
        ],
        datasets: [{
            label: 'Monthly Budget',
            data: [
                <?= $budget['housing'] ?: 0 ?>,
                <?= $budget['car'] ?: 0 ?>,
                <?= $budget['groceries'] ?: 0 ?>,
                <?= $budget['retirement'] ?: 0 ?>,
                <?= $budget['spending_money'] ?: 0 ?>,
                <?= abs($leftover) ?> // positive value for savings/over budget
            ],
            backgroundColor: [
                '#4CAF50', // Housing
                '#FFC107', // Car
                '#03A9F4', // Groceries
                '#8BC34A', // Retirement
                '#FF9800', // Spending Money
                <?= $is_over_budget ? "'#E53935'" : "'#9E9E9E'" ?> // Red if over, gray if saving
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'right' }
        }
    }
});
</script>

<?php if (!empty($growthData)): ?>
<script>
const interestCtx = document.getElementById('interestChart').getContext('2d');
new Chart(interestCtx, {
    type: 'line',
    data: {
        labels: [...Array(<?= count($growthData) ?>).keys()].map(i => `${Math.floor(i/12)}y ${i%12}m`),
        datasets: [{
            label: 'Projected Growth',
            data: <?= json_encode($growthData) ?>,
            borderColor: '#4CAF50',
            backgroundColor: 'rgba(76, 175, 80, 0.1)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: { title: { display: true, text: 'Years' }},
            y: { title: { display: true, text: 'Amount ($)' }}
        }
    }
});
</script>
<?php endif; ?>


</body>
</html>
