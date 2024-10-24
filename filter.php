<?php include 'db.php'; ?>

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
    <title>IndianOilSkytanking</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.png">
</head>

<body>
    <header class="main-header">
        <div class="brand-title">
            <h1><a href="dashboard.php">IndianOil <span style="color: red;">Sky</span><span style="color: rgb(248, 221, 15);">tanking</span></a></h1>
        </div>
        <i class="fas fa-bars" id="menu-toggle"></i>
    </header>

    <nav class="sidebar" id="sidebar">
        <ul>
        <div class="user-info">
            <p style="color:#fff;">Welcome, <?php echo htmlspecialchars($username); ?>!</p>
        </div>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <?php if ($role === 'admin'): ?>
                <li><a href="manager.php"><i class="fas fa-user-tie"></i> Manager</a></li>
                <li><a href="add_employee.php"><i class="fas fa-user-plus"></i> Add Employee</a></li>
                <li><a href="add_ppe.php"><i class="fas fa-hard-hat"></i> Add PPE</a></li>
                <button class="download-button" onclick="window.location.href='download_manager_data.php'">
                    Export Manager Data
                </button>
                <button class="download-button" onclick="window.location.href='download_employee_data.php'">
                    Export Employee Data
                </button>
            <?php endif; ?>
            <li><a href="employee.php"><i class="fas fa-users"></i> Employee Portal</a></li>

            <form method="POST" action="logout.php" class="logout">
                <button type="submit" class="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </ul>
        
    </nav>
                <!-- filter.php -->
<?php
include('nav.php');  // Include navigation bar
?>

<div class="container mt-5">
    <h2>Filter Issued Items</h2>
    <form action="filter_results.php" method="POST" class="row g-3">
        <!-- Date Range Filter -->
        <div class="col-md-4">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" name="start_date" id="start_date">
        </div>
        <div class="col-md-4">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" name="end_date" id="end_date">
        </div>

        <!-- Employee Filter -->
        <div class="col-md-4">
            <label for="employee" class="form-label">Employee</label>
            <select class="form-select" name="employee" id="employee">
                <option value="">Select Employee</option>
                <!-- Populate dynamically from the employees table -->
                <?php
                // Connect to database and fetch employee names
                include('db.php');
                $query = "SELECT id, name FROM employees";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='".$row['id']."'>".$row['name']."</option>";
                }
                ?>
            </select>
        </div>

        <!-- Item Type Filter -->
        <div class="col-md-4">
            <label for="item_type" class="form-label">Item Type</label>
            <select class="form-select" name="item_type" id="item_type">
                <option value="">Select Item Type</option>
                <option value="PPE">PPE</option>
                <option value="Tools">Tools</option>
                <option value="Uniform">Uniform</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Apply Filter</button>
        </div>
    </form>
</div>

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
