<?php


if (!isset($_SESSION['user_id'])) {
    include 'navbar_guest.php';
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    include 'navbar_admin.php';
} else {
    include 'navbar_user.php';
}
?>