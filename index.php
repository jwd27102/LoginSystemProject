<?php
// Database connection
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
    $classId = intval($_POST['classId']);

    $lastName = $conn->real_escape_string($lastName);

    // Get or insert student
    $studentQuery = "SELECT student_id FROM Students WHERE last_name = '$lastName'";
    $studentResult = $conn->query($studentQuery);

    if ($studentResult->num_rows > 0) {
        $row = $studentResult->fetch_assoc();
        $studentId = $row['student_id'];
    } else {
        $conn->query("INSERT INTO Students (last_name) VALUES ('$lastName')");
        $studentId = $conn->insert_id;
    }

    // Check if student is already logged in
    $checkLogin = "SELECT * FROM Logins WHERE student_id = $studentId AND logout_time IS NULL";
    $loginResult = $conn->query($checkLogin);

    if ($loginResult->num_rows > 0) {
        $loginMessage = "You are already logged in!";
    } else {
        // Log in student
        $insertLogin = "INSERT INTO Logins (student_id, class_id) VALUES ($studentId, $classId)";
        if ($conn->query($insertLogin)) {
            $loginMessage = $lastName." logged in successful!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Fetch classes
$classes = $conn->query("SELECT class_id, class_name FROM Classes ORDER BY class_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="main.css">
    <title>Student Login</title>
</head>
<body>
    <div class="login-container">
        <h2>Virginia Western Cyber Security Student Login</h2>
         <!-- will display login message-->
        <p><?php if(!empty($loginMessage)) echo $loginMessage; ?></p>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="text" name="lastName" placeholder="Enter Last Name" required>
            <select name="classId" required>
                <option value="">Select Class</option>
                <?php while ($row = $classes->fetch_assoc()): ?>
                    <option value="<?= $row['class_id'] ?>"><?= htmlspecialchars($row['class_name']) ?> (ID: <?= $row['class_id'] ?>)</option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Login</button>
        </form>
        <a href="logout.php" class="logout-link">Need to log out?</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>