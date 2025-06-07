<?php
session_start();
include 'db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle adding an assessment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_assessment'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO assessments (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $description);
    if ($stmt->execute()) {
        $message = "Assessment added successfully!";
    } else {
        $message = "Error adding assessment: " . $conn->error;
    }
    $stmt->close();
}

// Handle deleting an assessment
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM assessments WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Assessment deleted successfully!";
    } else {
        $message = "Error deleting assessment: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all assessments
$result = $conn->query("SELECT * FROM assessments");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assessments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h2 {
            text-align: center;
            color: #34495E;
        }
        .message {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input, textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        .button {
            display: inline-block;
            padding: 10px;
            text-decoration: none;
            background: #3498DB;
            color: white;
            border-radius: 5px;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background: #2980B9;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background: #3498DB;
            color: white;
        }
        .delete-btn {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Manage Assessments</h2>

        <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>

        <!-- Add Assessment Form -->
        <form action="manage_assessments.php" method="POST">
            <input type="text" name="title" placeholder="Assessment Title" required>
            <textarea name="description" placeholder="Assessment Description" required></textarea>
            <button type="submit" name="add_assessment" class="button">Add Assessment</button>
        </form>

        <!-- List of Assessments -->
        <h3>Existing Assessments</h3>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['title']; ?></td>
                    <td><?= $row['description']; ?></td>
                    <td>
                        <a href="manage_assessments.php?delete=<?= $row['id']; ?>" class="delete-btn">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <br>
        <a href="admindashboard.php" class="button">Back to Dashboard</a>
    </div>

</body>
</html>

<?php $conn->close(); ?>
