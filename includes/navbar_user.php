<!-- <style>
.navbar {
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #ffffff;
    padding: 12px 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
}

.logo {
    font-size: 22px;
    font-weight: bold;
    color: #4CAF50;
    text-decoration: none;
}

/* User icon dropdown */
.nav-user {
    position: relative;
    cursor: pointer;
}

.nav-user img {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 2px solid #4CAF50;
}

.user-dropdown {
    display: none;
    position: absolute;
    top: 42px;
    right: 0;
    background-color: rgba(255,255,255,0.95);
    border: 1px solid #ddd;
    border-radius: 6px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    min-width: 160px;
    z-index: 1001;
}

.user-dropdown a {
    display: block;
    padding: 12px 15px;
    color: #333;
    text-decoration: none;
}

.user-dropdown a:hover {
    background-color: #f4fdf7;
}

.nav-user:hover .user-dropdown {
    display: block;
}

body {
    margin-top: 60px;
}
</style> -->

<div class="navbar navbar-user">
    <div class="nav-left">
        <a href="dashboard.php" class="logo">🏠 Financially Fit</a>
    </div>

     <div class="nav-center">
        <a href="userHistory.php" class="nav-link">Budget History</a>

    </div>


    <div class="nav-right">
        <div class="nav-user">
            <img src="images/user-icon.png" alt="User Icon">
            <div class="user-dropdown">
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>