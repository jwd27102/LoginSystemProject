<?php
// admin.php

$host = 'localhost';
$db   = 'StudentLoginSystem';
$user = 'loginuser';
$pass = 'password';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle add class
if (isset($_POST['add_class'])) {
    $classId = intval($_POST['class_id']);
    $className = trim($_POST['class_name']);
    $className = $conn->real_escape_string($className);

    $insert = "INSERT INTO Classes (class_id, class_name) VALUES ($classId, '$className')";
    if ($conn->query($insert)) {
        echo "Class added successfully.";
    } else {
        echo "Error adding class: " . $conn->error;
    }
}

// Handle delete class
if (isset($_POST['delete_class'])) {
    $classIdToDelete = intval($_POST['class_id_to_delete']);
    $delete = "DELETE FROM Classes WHERE class_id = $classIdToDelete";
    if ($conn->query($delete)) {
        echo "Class deleted.";
    } else {
        echo "Error deleting class: " . $conn->error;
    }
}

// Get current classes
$classes = $conn->query("SELECT class_id, class_name FROM Classes ORDER BY class_name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Classes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f9f9f9;
        }

        h2 {
            color: #333;
        }

        form {
            margin-bottom: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            width: 300px;
        }

        input, select, button {
            display: block;
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body id="admin_body">

    <h2>Add a New Class</h2>
    <form method="POST">
        <label for="class_id">Class ID (number):</label>
        <input type="number" name="class_id" id="class_id" required>

        <label for="class_name">Class Name:</label>
        <input type="text" name="class_name" id="class_name" required>

        <button type="submit" name="add_class">Add Class</button>
    </form>

    <h2>Delete an Existing Class</h2>
    <form method="POST">
        <label for="class_id_to_delete">Select Class:</label>
        <select name="class_id_to_delete" required>
            <option value="">-- Select a Class --</option>
            <?php while ($row = $classes->fetch_assoc()): ?>
                <option value="<?= $row['class_id'] ?>">
                    <?= htmlspecialchars($row['class_name']) ?> (ID: <?= $row['class_id'] ?>)
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="delete_class">Delete Class</button>
    </form>

</body>
</html>

<?php $conn->close(); ?>