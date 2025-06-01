<?php
session_start();
require 'includes/database_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch budget history
$stmt = $pdo->prepare("SELECT * FROM budget_history WHERE user_id = ? ORDER BY year DESC, month DESC");
$stmt->execute([$user_id]);
$history = $stmt->fetchAll();

// Group entries by month-year
$grouped_history = [];
foreach ($history as $entry) {
    $monthName = date('F', mktime(0, 0, 0, $entry['month'], 10));
    $label = $monthName . ' ' . $entry['year'];
    $grouped_history[$label][] = $entry;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Budget History</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/user_history.css">
</head>
<body>
<?php include 'includes/navbar_user.php'; ?>

<div class="history-container">
    <h2>Monthly Budget History</h2>

    <?php if (empty($grouped_history)): ?>
        <p>No history records found.</p>
    <?php else: ?>
        <?php foreach ($grouped_history as $label => $entries): ?>
            <button class="month-button" onclick="toggleContent(this)">
                <?= htmlspecialchars($label) ?>
            </button>
            <div class="history-content">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Income</th>
                            <th>Housing</th>
                            <th>Car</th>
                            <th>Groceries</th>
                            <th>Retirement</th>
                            <th>Spending</th>
                            <th>Leftover</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td>$<?= number_format($entry['income'], 2) ?></td>
                            <td>$<?= number_format($entry['housing'], 2) ?></td>
                            <td>$<?= number_format($entry['car'], 2) ?></td>
                            <td>$<?= number_format($entry['groceries'], 2) ?></td>
                            <td>$<?= number_format($entry['retirement'], 2) ?></td>
                            <td>$<?= number_format($entry['spending_money'], 2) ?></td>
                            <td style="color: <?= $entry['leftover'] < 0 ? 'red' : 'green' ?>">
                                $<?= number_format($entry['leftover'], 2) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function toggleContent(button) {
    const content = button.nextElementSibling;
    content.style.display = content.style.display === "block" ? "none" : "block";
}
</script>

</body>
</html>
