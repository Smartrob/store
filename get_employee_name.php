<?php
// Start the session
session_start();

// Include database connection
include 'db.php';

if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    // Query to fetch the employee name based on employee ID
    $query = "SELECT employee_name FROM employees WHERE employee_id = '$employee_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Fetch employee name
        $row = $result->fetch_assoc();
        $employee_name = $row['employee_name'];
        echo json_encode(['employee_name' => $employee_name]);
    } else {
        // If no employee is found
        echo json_encode(['employee_name' => '']);
    }
}

$conn->close();
?>
