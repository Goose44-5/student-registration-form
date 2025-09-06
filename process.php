<?php
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) 
    die("Connection failed: " . $conn->connect_error);
error_reporting(E_ALL); 
ini_set('display_errors', 1);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) && !empty($_POST['id']) 
        ? (int)$_POST['id'] : 0;
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $mobile_input = htmlspecialchars($_POST['mobile'], 
        ENT_QUOTES, 'UTF-8');
    $mobile = "+95" . $mobile_input;
    $email = filter_var($_POST['email'], 
        FILTER_VALIDATE_EMAIL) ? $_POST['email'] : '';
    $gender = htmlspecialchars($_POST['gender'], 
        ENT_QUOTES, 'UTF-8');
    $department = htmlspecialchars($_POST['department'] ?? '', 
        ENT_QUOTES, 'UTF-8');
    $address = htmlspecialchars($_POST['address'], 
        ENT_QUOTES, 'UTF-8');
    if (empty($email)) 
        die("Invalid email format");
    if ($id) {
        $stmt = $conn->prepare("UPDATE students SET student_name = ?, 
            mobile_no = ?, email = ?, gender = ?, department = ?, 
            address = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $name, $mobile, $email, 
            $gender, $department, $address, $id);
        $success = $stmt->execute(); 
        $stmt->close(); 
        $message = "Record updated successfully";
    } else {
        $stmt = $conn->prepare("INSERT INTO students 
            (student_name, mobile_no, email, gender, department, 
            address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $mobile, $email, 
            $gender, $department, $address);
        $success = $stmt->execute(); 
        $stmt->close(); 
        $message = "New record created successfully";
    }
    if ($success) 
        echo "<h2>$message</h2>"; 
    else 
        echo "Error: " . $conn->error;
    echo "<a href='index.php'>Back to Registration</a><br>
        <a href='view.php'>View all registrations</a>";
}
$conn->close();
?>