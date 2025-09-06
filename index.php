<?php
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) 
    die("Connection failed: " . $conn->connect_error);
error_reporting(E_ALL); 
ini_set('display_errors', 1);
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0; 
$data = [];
if ($id) { 
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    $data = $result->fetch_assoc(); 
    $stmt->close(); 
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Registration</title>
<style>
body {background:white;}
.mobile-input {display:flex;align-items:center;}
.mobile-input span {margin-right:5px;}
.mobile-input input {flex:1;max-width:150px;}
textarea {resize:vertical;}
.required-textarea {border:2px solid #ccc;}
.required-textarea:empty:not(:focus):before 
    {content:"Address *";color:#888;font-style:italic;}
.required-textarea:focus {border-color:#000;}
</style>
</head>
<body>
<h2><?php echo $id ? 'Edit Student' : 'Student Registration Form'; ?></h2>
<form method="POST" action="process.php" 
    onsubmit="return validateForm()">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<label>Student Name:</label><input type="text" 
    name="name" value="<?php echo htmlspecialchars($data['student_name'] ?? ''); ?>" 
    required><br><br>
<label>Mobile No:</label>
<div class="mobile-input"><span>+95</span><input type="text" 
    name="mobile" value="<?php echo htmlspecialchars(substr($data['mobile_no'] ?? '', 3) ?? ''); ?>" 
    required></div><br><br>
<label>Email:</label><input type="email" 
    name="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" 
    required><br><br>
<label>Gender:</label><input type="radio" 
    name="gender" value="Male" <?php echo ($data['gender'] ?? '') == 'Male' ? 'checked' : ''; ?>> 
    Male<input type="radio" name="gender" value="Female" 
    <?php echo ($data['gender'] ?? '') == 'Female' ? 'checked' : ''; ?>> Female<br><br>
<label>Department:</label><br><input type="radio" 
    name="department" value="English" 
    <?php echo ($data['department'] ?? '') == 'English' ? 'checked' : ''; ?> 
    required> English<input type="radio" name="department" 
    value="Computer" <?php echo ($data['department'] ?? '') == 'Computer' ? 'checked' : ''; ?> 
    required> Computer<input type="radio" name="department" 
    value="Business" <?php echo ($data['department'] ?? '') == 'Business' ? 'checked' : ''; ?> 
    required> Business<br><br>
<label>Address:</label><textarea name="address" 
    rows="4" cols="50" class="required-textarea" 
    required></textarea><br><br>
<input type="submit" value="<?php echo $id ? 'Update Record' : 'Register'; ?>">
</form>
<?php if ($id): ?>
<a href="view.php">Cancel and go back to the list</a>
<?php else: ?>
<a href="view.php">View All Registered Students</a>
<?php endif; ?>
<script>
function validateForm(){var address=document.querySelector('textarea[name="address"]');
if(address.value.trim()===''){address.setCustomValidity('Please fill out this field');
address.reportValidity();return false;}return true;}
</script>
</body>
</html>
<?php $conn->close(); ?>