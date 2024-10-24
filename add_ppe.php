<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}
?>

<?php include 'db.php'; ?>

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

    <main class="add-ppe-main">
        <section class="add-ppe-section">
            <h2>Add New PPE</h2>
            <form method="post" class="add-ppe-form">
                <div class="input-group">
                    <label for="ppe_name">PPE Name:</label>
                    <input type="text" id="ppe_name" name="ppe_name" required>
                </div>
                <button type="submit" class="submit-button">Submit</button>
            </form>
             <!-- Success and Error Message Box -->
    <div id="message-box" class="message"></div>

        </section>
       
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ppe_name = $_POST['ppe_name'];

    // Check if PPE already exists in the table
    $check_sql = "SELECT * FROM ppe_items WHERE ppe_name = '$ppe_name'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // If PPE already exists, show error message
        echo "<script>
            var messageBox = document.getElementById('message-box');
            messageBox.innerHTML = 'Error: PPE already exists.';
            messageBox.classList.add('message-error');
            messageBox.style.display = 'block';
            // hideMessage();
        </script>";
    } else {
        // Get the latest PPE ID and generate the next PPE ID
        $sql = "SELECT ppe_id FROM ppe_items ORDER BY ppe_id DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $last_id = $row['ppe_id'];
            $num = (int) substr($last_id, 3); // Extract the number part from 'PPE01', 'PPE02', etc.
            $new_ppe_id = 'PPE' . str_pad($num + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $new_ppe_id = 'PPE01'; // If no PPE exists, start with PPE01
        }

        // Insert new PPE into the table
        $insert_sql = "INSERT INTO ppe_items (ppe_id, ppe_name) VALUES ('$new_ppe_id', '$ppe_name')";

        if ($conn->query($insert_sql) === TRUE) {
            // Success message
            echo "<script>
                var messageBox = document.getElementById('message-box');
                messageBox.innerHTML = 'PPE successfully added with ID: $new_ppe_id.';
                messageBox.classList.add('message-success');
                messageBox.style.display = 'block';
                hideMessage();
            </script>";
        } else {
            echo "<script>
                var messageBox = document.getElementById('message-box');
                messageBox.innerHTML = 'Error: " . $conn->error . "';
                messageBox.classList.add('message-error');
                messageBox.style.display = 'block';
                hideMessage();
            </script>";
        }
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