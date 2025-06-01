<!-- 

<style>
/* Navbar Base */
.navbar {
    width: 100%;
    max-width: 100%;
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
/* Logo */
.navbar .logo {
    font-size: 22px;
    font-weight: bold;
    color: #4CAF50;
    text-decoration: none;
}

/* Dropdown Wrapper */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-toggle {
    color: #333;
    font-weight: bold;
    cursor: pointer;
    padding: 8px 12px;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 35px;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    border-radius: 6px;
    min-width: 150px;
    z-index: 1001;
}

.dropdown-menu a {
    display: block;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
}

.dropdown-menu a:hover {
    background-color: #f4fdf7;
}

.dropdown:hover .dropdown-menu {
    display: block;
}

/* Navbar Layout */
.nav-left, .nav-center, .nav-right {
    display: flex;
    align-items: center;
}

.nav-center {
    gap: 20px;
}

.nav-right .dropdown-toggle {
    color: #4CAF50;
}

.nav-right {
    margin-right: 20px;
}

body {
    margin: 0;
    padding: 0;
    margin-top: 60px; /* Push content below navbar */
    font-family: 'Segoe UI', sans-serif;
} 
</style> -->




<link rel="stylesheet" href="css/navbar.css">

<div class="navbar navbar-guest">
    <!-- Left Logo -->
    <div class="nav-left">
        <a href="index.php" class="logo">🏠 Financially Fit</a>
    </div>

    <!-- Center Dropdown: Info -->
    <div class="nav-center">
        <div class="dropdown">
            <div class="dropdown-toggle">Info ▼</div>
            <div class="dropdown-menu">
                <a href="#">About Us</a>
                <a href="mailto:your-email@example.com">Contact</a>
            </div>
        </div>
    </div>

    <!-- Right Login/Register -->
    <div class="nav-right">
        <div class="dropdown">
            <div class="dropdown-toggle">Sign Up / Login ▼</div>
            <div class="dropdown-menu">
                <a href="registration.php">Sign Up</a>
                <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</div>
