<?php include 'db.php'; ?>

<?php
// Connect to the database

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select data without the first column
$sql = "SELECT employee_id, ppe_name, issue_date, next_due_date FROM issued_items"; // Adjust table and column names as needed
$result = $conn->query(query: $sql);

// Output CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="manager_data.csv"');

// Open file output stream
$output = fopen('php://output', 'w');

// Write column headers to the CSV file
fputcsv($output, array('Employee ID', 'PPE Name', 'Issue Date', 'Next Due Date'));

// Write rows to the CSV file
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convert issue and next due dates into desired format (DD-MM-YYYY)
        $row['issue_date'] = date('d-m-Y', strtotime($row['issue_date']));
        $row['next_due_date'] = date('d-m-Y', strtotime($row['next_due_date']));

        // Write the row to the CSV file
        fputcsv($output, $row);
    }
} else {
    echo "No data available";
}

fclose($output);
$conn->close();
?>
