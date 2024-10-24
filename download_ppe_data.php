<?php include 'db.php'; ?>
<?php
// Connect to the database

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select employee data
$sql = "SELECT * FROM ppe_items"; // Change to your actual table name
$result = $conn->query($sql);

// Output CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="ppe_data.csv"');

// Open file output stream
$output = fopen('php://output', 'w');

// Write column headers to the CSV file
fputcsv($output, array('PPE ID', 'PPE Name'));

// Write rows to the CSV file
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
} else {
    echo "No data available";
}

fclose($output);
$conn->close();
?>
