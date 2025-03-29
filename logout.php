<?php
// logout.php

$host = 'localhost';
$db   = 'StudentLoginSystem';
$user = 'loginuser'; // or your DB user
$pass = 'password';     // or your DB password

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //check if form was submitted

$lastName = trim($_POST['lastName']);
$lastName = $conn->real_escape_string($lastName);

// Get student ID
$result = $conn->query("SELECT student_id FROM Students WHERE last_name = '$lastName'");
if ($result->num_rows == 0) {
    echo "Student not found.";
    exit;
}

$row = $result->fetch_assoc();
$studentId = $row['student_id'];

// Find active session (no logout time)
$check = $conn->query("SELECT login_id FROM Logins WHERE student_id = $studentId AND logout_time IS NULL ORDER BY login_time DESC LIMIT 1");

if ($check->num_rows === 0) {
    $logoutMessage = "You're not currently logged in.";
} else {
    $login = $check->fetch_assoc();
    $loginId = $login['login_id'];

    $update = $conn->query("UPDATE Logins SET logout_time = NOW() WHERE login_id = $loginId");

    if ($update) {
        $logoutMessage =  $lastName." logged out successful.";
    } else {
        $logoutMessage = "Error logging out: " . $conn->error;
    }
}

}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Logout</title>
    <link rel="stylesheet" href="main.css">
</head>

<body id="logout_body">
    <div class="logout-container">
        <h2>Student Logout</h2>
         <!-- will display login message-->
         <p><?php if(!empty($logoutMessage)) echo $logoutMessage; ?></p>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="text" name="lastName" placeholder="Enter Last Name" required>
            <button type="submit">Logout</button>
        </form>
        <a href="index.php"class="logout-link">Back to Login</a>
    </div>
</body>
</html>