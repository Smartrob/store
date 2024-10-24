<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

include 'db.php'; // Include your database connection

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $ppe_id = $_POST['ppe_id'];
    $issue_date = $_POST['issue_date'];
    $next_due_date = $_POST['next_due_date'];

    // Check if the employee exists in the employees table
    $checkEmployee = "SELECT * FROM employees WHERE employee_id = '$employee_id'";
    $result = $conn->query($checkEmployee);

    if ($result->num_rows > 0) {
        // Employee exists, now check if PPE ID exists in ppe_items table
        $checkPPE = "SELECT * FROM ppe_items WHERE ppe_id = '$ppe_id'";
        $ppeResult = $conn->query($checkPPE);

        if ($ppeResult->num_rows > 0) {
            // Fetch PPE name from the query result
            $ppeRow = $ppeResult->fetch_assoc();
            $ppe_name = $ppeRow['ppe_name'];

            // Check for duplicate entry
            $checkDuplicate = "SELECT * FROM issued_items WHERE employee_id = '$employee_id' AND ppe_id = '$ppe_id' AND issue_date = '$issue_date' AND next_due_date = '$next_due_date'";
            $duplicateResult = $conn->query($checkDuplicate);

            if ($duplicateResult->num_rows > 0) {
                // Duplicate found, show error message
                $errorMessage = "Error: This item has already been issued to the employee.";
            } else {
                // No duplicate, proceed to insert into issued_items
                $sql = "INSERT INTO issued_items (employee_id, ppe_id, ppe_name, issue_date, next_due_date) 
                        VALUES ('$employee_id', '$ppe_id', '$ppe_name', '$issue_date', '$next_due_date')";

                if ($conn->query($sql) === TRUE) {
                    // Success message
                    $successMessage = "Item issued to employee successfully!";
                    
                    // Send data to Google Sheet
                    $data = array(
                        'employee_id' => $employee_id,
                        'ppe_id' => $ppe_id,
                        'ppe_name' => $ppe_name,
                        'issue_date' => $issue_date,
                        'next_due_date' => $next_due_date
                    );

                    // Convert the data array to JSON
                    $jsonData = json_encode($data);

                    // Google Apps Script URL (use the URL from the deployment)
                    $url = 'https://script.google.com/macros/s/AKfycbyuvdjPlMVf4-Jq7ydj7xN24rZ8vuV97HGsQr8CrNDT853RJPo7yUZt1cSta6Ze0xUW/exec'; 

                    // Set up cURL to send the request to the Google Apps Script
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

                    // Execute the cURL request and get the response
                    $response = curl_exec($ch);
                    curl_close($ch);

                    // if ($response === 'Success') {
                    //     $successMessage .= " Data also saved to Google Sheet!";
                    // } else {
                    //     $errorMessage .= " Could not save data to Google Sheet.";
                    // }
                } else {
                    $errorMessage = "Error: " . $conn->error;
                }
            }
        } else {
            $errorMessage = "Error: PPE ID does not exist. Please enter a valid PPE ID.";
        }
    } else {
        $errorMessage = "Error: Employee ID does not exist. Please ensure the employee is registered first.";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Add jQuery for AJAX -->
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
            <li><a href="manager.php"><i class="fas fa-user-tie"></i> Manager</a></li>
            <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
            <li><a href="add_employee.php"><i class="fas fa-user-plus"></i> Add Employee</a></li>
            <li><a href="add_ppe.php"><i class="fas fa-hard-hat"></i> Add PPE</a></li>
            <button class="download-button" onclick="window.location.href='download_manager_data.php'">Export Manager
                Data</button>
            <button class="download-button" onclick="window.location.href='download_employee_data.php'">Export Employee
                Data</button>
            <button class="download-button" onclick="window.location.href='download_ppe_data.php'">
                Export PPE Data
            </button>
            <form method="POST" action="logout.php" class="logout">
                <button type="submit" class="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </ul>
    </nav>

    <main class="manager-main">
        <section class="form-container">
            <h2>Issue PPE</h2>
            <form method="post" class="manager-form">
                <div class="input-group">
                    <label for="employee_id">Employee ID:</label>
                    <input type="text" id="employee_id" name="employee_id" required onkeyup="fetchEmployeeName()">
                    <!-- <input type="text" id="employee_name" placeholder="Employee Name" readonly> -->
                </div>
                <div class="input-group">
                    <label for="employee_id">Employee Name:</label>
                    <input type="text" id="employee_name" placeholder="Employee Name" readonly>
                </div>

                <div class="input-group">
                    <label for="ppe_id">PPE ID:</label>
                    <input type="text" id="ppe_id" name="ppe_id" required onkeyup="fetchPPEName()">
                    <!-- <input type="text" id="ppe_name" placeholder="PPE Name" readonly> -->
                </div>
                <div class="input-group">
                    <label for="ppe_name">PPE Name:</label>
                    <!-- <input type="text" id="ppe_id" name="ppe_id" required onkeyup="fetchPPEName()"> -->
                    <input type="text" id="ppe_name" placeholder="PPE Name" readonly>
                </div>

                <div class="input-group">
                    <label for="issue_date">Issue Date:</label>
                    <input type="date" id="issue_date" name="issue_date" required>
                </div>

                <div class="input-group">
                    <label for="next_due_date">Next Due Date:</label>
                    <input type="date" id="next_due_date" name="next_due_date" required>
                </div>

                <button type="submit" class="submit-button">Submit</button>
            </form>

            <!-- Success or Error Message -->
            <div class="success-message <?php if ($successMessage != '')
                echo 'show'; ?>">
                <?php echo $successMessage; ?>
            </div>
            <div class="error-message <?php if ($errorMessage != '')
                echo 'show'; ?>">
                <?php echo $errorMessage; ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>Powered by <a href="https://technicalraven.netlify.app" target="_blank">Technical Raven</a> © 2024</p>
    </footer>

    <script>
        // Function to fetch Employee Name based on Employee ID
        function fetchEmployeeName() {
            var employee_id = $('#employee_id').val();
            if (employee_id !== '') {
                $.ajax({
                    url: 'fetch_employee.php', // This script will return employee name based on the entered ID
                    method: 'POST',
                    data: { employee_id: employee_id },
                    success: function (data) {
                        $('#employee_name').val(data); // Show employee name in the input field
                    }
                });
            } else {
                $('#employee_name').val(''); // Clear employee name if no ID entered
            }
        }

        // Function to fetch PPE Name based on PPE ID
        // Function to fetch PPE Name based on PPE ID
        function fetchPPEName() {
            var ppe_id = $('#ppe_id').val();
            if (ppe_id !== '') {
                $.ajax({
                    url: 'fetch_ppe.php', // This script will return PPE name based on the entered ID
                    method: 'POST',
                    data: { ppe_id: ppe_id },
                    success: function (data) {
                        $('#ppe_name').val(data); // Show PPE name in the input field
                    }
                });
            }
        }


        // This function will set the Issue Date to today's date in YYYY-MM-DD format
        window.onload = function () {
            var date = new Date();
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var year = date.getFullYear();

            // Set the formatted date to the issue_date input field
            var today = year + "-" + month + "-" + day;
            document.getElementById("issue_date").value = today;
        };

        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');

        menuToggle.onclick = function () {
            sidebar.classList.toggle('active');
        }
    </script>
</body>

</html>