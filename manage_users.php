<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user.";
    }
    $stmt->close();
}

// Fetch all users
$result = $conn->query("SELECT user_id, name, email, department, role FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #3498DB;
            color: white;
        }
        .delete-btn {
            background-color: red;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
        .back-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            background: #3498DB;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover {
            background: #2980B9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['department']; ?></td>
                    <td><?php echo ucfirst($row['role']); ?></td>
                    <td><a href="manage_users.php?delete_id=<?php echo $row['user_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?');">Delete</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>
