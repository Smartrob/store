<?php
include 'db.php';

if (isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];
    $sql = "SELECT employee_name FROM employees WHERE employee_id = '$employee_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['employee_name'];
    } else {
        echo ''; // Return empty if no employee found
    }
}
?>
