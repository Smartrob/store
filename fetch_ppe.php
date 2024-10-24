<?php
include 'db.php'; // Include your database connection

if (isset($_POST['ppe_id'])) {
    $ppe_id = $_POST['ppe_id'];

    // Fetch PPE name from the database
    $sql = "SELECT ppe_name FROM ppe_items WHERE ppe_id = '$ppe_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['ppe_name']; // Return PPE name as the response
    } else {
        echo ''; // If no PPE name found, return an empty string
    }

    $conn->close();
}
?>