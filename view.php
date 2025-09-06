<?php
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) 
    die("Connection failed: " . $conn->connect_error);
error_reporting(E_ALL); 
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Records</title>
<style>
body {background:white;}
table {border-collapse:collapse;width:100%;margin:10px 0;}
table th, table td {border:1px solid black;padding:5px;}
table th {background-color:#f2f2f2;}
</style>
</head>
<body>
<h2>Registered Students</h2>
<table>
<tr><th>ID</th><th>Student Name</th><th>Mobile No.</th>
<th>Email</th><th>Gender</th><th>Department</th>
<th>Address</th><th>Action</th></tr>
<?php

$stmt = $conn->prepare("SELECT * FROM students ORDER BY id ASC"); 
if (!$stmt) {
    echo "Error preparing query: " . $conn->error;
    exit;
}
$stmt->execute(); 
$result = $stmt->get_result();
if ($result->num_rows > 0) { 
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row['id']) . "</td>
            <td>" . htmlspecialchars($row['student_name']) . "</td>
            <td>" . htmlspecialchars($row['mobile_no']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            <td>" . htmlspecialchars($row['gender']) . "</td>
            <td>" . htmlspecialchars($row['department']) . "</td>
            <td>" . htmlspecialchars($row['address']) . "</td>
            <td><a href='index.php?id=" . htmlspecialchars($row['id']) 
            . "'>Edit</a></td></tr>";
    }
} else {
    echo "<tr><td colspan='8'>No records found</td></tr>";
}
$stmt->close();
?>
</table><br><a href="index.php">Register New Student</a>
</body>
</html>
<?php $conn->close(); ?>