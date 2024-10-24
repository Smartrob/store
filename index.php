<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

// Retrieve user details from the session
$username = $_SESSION['username'];
$role = $_SESSION['role']; // 'admin' or 'employee'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - IndianOilSkytanking</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.png">
</head>

<body>
    <header class="main-header">
        <div class="brand-title">
            <h1><a href="index.php">IndianOil <span style="color: red;">Sky</span><span
                        style="color: rgb(248, 221, 15);">tanking</span></a></h1>
        </div>
        <i class="fas fa-bars" id="menu-toggle"></i>
    </header>

    <nav class="sidebar" id="sidebar">
        <ul>
        <div class="user-info">
            <p style="color:#fff;">Welcome, <?php echo htmlspecialchars($username); ?>!</p>
        </div>
            <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <?php if ($role === 'admin'): ?>
                <li><a href="manager.php"><i class="fas fa-user-tie"></i> Manager</a></li>
                <li><a href="add_employee.php"><i class="fas fa-user-plus"></i> Add Employee</a></li>
                <li><a href="add_ppe.php"><i class="fas fa-hard-hat"></i> Add PPE</a></li>
            <?php endif; ?>
            <li><a href="employee.php"><i class="fas fa-users"></i> Employee Portal</a></li>

            <?php if ($role === 'admin'): ?>
                <button class="download-button" onclick="window.location.href='download_manager_data.php'">
                    Export Manager Data
                </button>
                <button class="download-button" onclick="window.location.href='download_employee_data.php'">
                    Export Employee Data
                </button>
                <button class="download-button" onclick="window.location.href='download_ppe_data.php'">
                    Export PPE Data
                </button>
            <?php endif; ?>

            <form method="POST" action="logout.php" class="logout">
                <button type="submit" class="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </ul>
        
    </nav>

    <main class="dashboard-main">
        <section class="dashboard-container">
            <h2>Dashboard</h2>
            <div class="card-container">
                <?php if ($role === 'admin'): ?>
                    <div class="card">
                        <a href="manager.php">
                            <i class="fas fa-user-cog"></i>
                            <h3>Manager Panel</h3>
                        </a>
                    </div>
                    <div class="card">
                        <a href="add_employee.php">
                            <i class="fas fa-user-plus"></i>
                            <h3>Add Employee</h3>
                        </a>
                    </div>
                    <div class="card">
                        <a href="add_ppe.php">
                            <i class="fas fa-hard-hat"></i>
                            <h3>Add PPE</h3>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="card">
                    <a href="employee.php">
                        <i class="fas fa-users"></i>
                        <h3>Employee Portal</h3>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>Powered by <a href="https://technicalraven.netlify.app" target="_blank">Technical Raven</a> © 2024</p>
    </footer>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');

        menuToggle.onclick = function () {
            sidebar.classList.toggle('active');
        }
    </script>
</body>

</html>
