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
    <style>
        .table-container {
            max-height: 400px; /* Set this to a reasonable height */
            overflow-y: auto;
            margin-bottom: 20px; /* Prevents overlap with footer */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
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
            <div class="user-info">
                <p style="color:#fff;">Welcome, <?php echo htmlspecialchars($username); ?>!</p>
            </div>
            <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <?php if ($role === 'admin'): ?>
                <li><a href="manager.php"><i class="fas fa-user-tie"></i> Manager</a></li>
                <li><a href="add_employee.php"><i class="fas fa-user-plus"></i> Add Employee</a></li>
                <li><a href="add_ppe.php"><i class="fas fa-hard-hat"></i> Add PPE</a></li>
                <button class="download-button" onclick="window.location.href='download_manager_data.php'">Export Manager Data</button>
                <button class="download-button" onclick="window.location.href='download_employee_data.php'">Export Employee Data</button>
                <button class="download-button" onclick="window.location.href='download_ppe_data.php'">Export PPE Data</button>
            <?php endif; ?>
            <li><a href="employee.php"><i class="fas fa-users"></i> Employee Portal</a></li>

            <form method="POST" action="logout.php" class="logout">
                <button type="submit" class="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </ul>
    </nav>

    <main class="employee-main">
        <section class="employee-portal">
            <h2>Employee Portal</h2>

            <form method="GET" class="employee-search-form">
                <div class="input-group">
                    <input type="text" id="employee_id" name="employee_id" required placeholder="Enter Employee ID">
                </div>
                <span id="error-msg" class="error" style="display: none;">Please enter a valid Employee ID.</span>
                <div class="input-group">
                    <input type="text" id="item_filter" name="item_filter" placeholder="Filter by Item Name">
                </div>
                <button type="submit" class="search-button">Search</button>
            </form>
        </section>

        <?php
        if (isset($_GET['employee_id'])) {
            $employee_id = $_GET['employee_id'];
            $item_filter = isset($_GET['item_filter']) ? $_GET['item_filter'] : '';

            // Fetch employee name and issued items, sorting by issue_date DESC to get recent items first
            $sql = "SELECT employees.employee_name, issued_items.ppe_name, issued_items.issue_date, issued_items.next_due_date 
                    FROM issued_items 
                    JOIN employees ON issued_items.employee_id = employees.employee_id 
                    WHERE issued_items.employee_id = '$employee_id'";

            if (!empty($item_filter)) {
                $sql .= " AND issued_items.ppe_name LIKE '%$item_filter%'"; // Add filter condition
            }

            $sql .= " ORDER BY issued_items.issue_date DESC";  // Order by issue_date DESC for recent data first
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $first_row = $result->fetch_assoc();
                $employee_name = $first_row['employee_name'];

                echo "<h2>Items Issued to $employee_name (Employee ID: $employee_id)</h2>";

                // Scrollable container for the table
                echo "<div class='table-container'>";
                echo "<table>";
                echo "<tr><th>Item Name</th><th>Issue Date</th><th>Next Due Date</th></tr>";

                // Output the first row
                echo "<tr><td>" . $first_row['ppe_name'] . "</td>";
                echo "<td>" . $first_row['issue_date'] . "</td>";
                echo "<td>" . $first_row['next_due_date'] . "</td></tr>";

                // Output remaining rows
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['ppe_name'] . "</td>";
                    echo "<td>" . $row['issue_date'] . "</td>";
                    echo "<td>" . $row['next_due_date'] . "</td></tr>";
                }

                echo "</table>";
                echo "</div>"; // Closing table container div
            } else {
                echo "No items issued to this employee.";
            }
        }
        $conn->close();
        ?>
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
