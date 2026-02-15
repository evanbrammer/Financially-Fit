<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

if (($_SESSION['role'] ?? '') !== 'admin') {
  header("Location: dashboard.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Panel</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="dashboard-container">
  <h2>Admin Panel</h2>
  <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>.</p>
  <p>This is a placeholder admin panel (we’ll expand it later).</p>
</div>

</body>
</html>
