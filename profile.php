<?php
session_start();
require 'includes/database_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if user_info row exists
$stmt = $pdo->prepare("SELECT * FROM user_info WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

if (!$profile) {
    // Insert a blank profile if one doesn't exist yet
    $insert = $pdo->prepare("INSERT INTO user_info (user_id) VALUES (?)");
    $insert->execute([$user_id]);

    // Re-fetch the profile
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch();
}

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE user_info SET 
        first_name = ?, 
        last_name = ?, 
        age = ?, 
        gender = ?, 
        height_in = ?, 
        weight_lbs = ?
        WHERE user_id = ?");
    
    $stmt->execute([
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['age'],
        $_POST['gender'],
        $_POST['height_in'],
        $_POST['weight_lbs'],
        $user_id
    ]);

    $success = true;

    // fetch updated profile
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Profile</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/navbar.css"> 
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="form-container">
        <h2>Your Profile</h2>

        <?php if ($success): ?>
            <p class="success">Profile updated successfully!</p>
        <?php endif; ?>

        <form method="POST">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($profile['first_name'] ?? '') ?>">

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($profile['last_name'] ?? '') ?>">

            <label>Age:</label>
            <input type="number" name="age" value="<?= htmlspecialchars($profile['age'] ?? '') ?>">

            <label>Gender:</label>
            <select name="gender">
                <option value="Male" <?= ($profile['gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= ($profile['gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= ($profile['gender'] === 'Other') ? 'selected' : '' ?>>Other</option>
            </select>

            <label>Height (in):</label>
            <input type="number" name="height_in" step="0.1" value="<?= htmlspecialchars($profile['height_in'] ?? '') ?>">

            <label>Weight (lbs):</label>
            <input type="number" name="weight_lbs" step="0.1" value="<?= htmlspecialchars($profile['weight_lbs'] ?? '') ?>">

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>