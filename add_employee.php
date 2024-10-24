<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}
?>
<!-- Rest of the employee.php page -->


<?php
include 'db.php';
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $employee_name = $_POST['employee_name'];
    $department = $_POST['department'];

    // Check if the employee already exists in the employees table
    $checkDuplicate = "SELECT * FROM employees WHERE employee_id = '$employee_id'";
    $duplicateResult = $conn->query($checkDuplicate);

    if ($duplicateResult->num_rows > 0) {
        // Duplicate employee found, show error message
        $errorMessage = "Error: An employee with this ID already exists.";
    } else {
        // No duplicate, proceed to insert into the employees table
        $sql = "INSERT INTO employees (employee_id, employee_name, department) 
                VALUES ('$employee_id', '$employee_name', '$department')";

        if ($conn->query($sql) === TRUE) {
            // Success message
            $successMessage = "Employee added successfully!";
        } else {
            // Error message for query failure
            $errorMessage = "Error: " . $conn->error;
        }
    }

    $conn->close();
}
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
            <h1><a href="index.php">IndianOil <span style="color: red;">Sky</span><span style="color: rgb(248, 221, 15);">tanking</span></a></h1>
        </div>
        <i class="fas fa-bars" id="menu-toggle"></i>
    </header>

    <nav class="sidebar" id="sidebar">
        <ul>
            <li><a href="manager.php"><i class="fas fa-user-tie"></i> Manager</a></li>
            <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
            <li><a href="add_employee.php"><i class="fas fa-user-plus"></i> Add Employee</a></li>
            <li><a href="add_ppe.php"><i class="fas fa-hard-hat"></i> Add PPE</a></li>
            <button class="download-button" onclick="window.location.href='download_manager_data.php'">
                Export Manager Data
            </button>
            <button class="download-button" onclick="window.location.href='download_employee_data.php'">
                Export Employee Data
            </button>
            <button class="download-button" onclick="window.location.href='download_ppe_data.php'">
                    Export PPE Data
                </button>
            <form method="POST" action="logout.php" class="logout">
                <button type="submit" class="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </ul>
    </nav>
    <main class="add-employee-main">
        <section class="add-employee-section">
            <h2>Add New Employee</h2>
            <form  method="post" class="add-employee-form">
                <div class="input-group">
                    <label for="employee_id">Employee ID:</label>
                    <input type="text" id="employee_id" name="employee_id" required>
                </div>
                <div class="input-group">
                    <label for="employee_name">Employee Name:</label>
                    <input type="text" id="employee_name" name="employee_name" required>
                </div>
                <div class="input-group">
                    <label for="department">Department:</label>
                    <input type="text" id="department" name="department" required>
                </div>
                <button type="submit" class="submit-button">Submit</button>
            </form>
             <!-- Success or Error Message -->
        <div class="success-message <?php if ($successMessage != '') echo 'show'; ?>">
            <?php echo $successMessage; ?>
        </div>
        <div class="error-message <?php if ($errorMessage != '') echo 'show'; ?>">
            <?php echo $errorMessage; ?>
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