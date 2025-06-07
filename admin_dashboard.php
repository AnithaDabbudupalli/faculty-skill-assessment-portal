<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .nav {
            display: flex;
            justify-content: space-between;
            background: #3498DB;
            padding: 15px;
            color: white;
        }
        .nav a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .nav a:hover {
            background: #2980B9;
        }
        .section {
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px;
            margin: 10px 5px;
            text-decoration: none;
            background: #3498DB;
            color: white;
            border-radius: 5px;
            transition: 0.3s;
        }
        .button:hover {
            background: #2980B9;
        }
    </style>
</head>
<body>

    <div class="nav">
        <h3>Admin Dashboard</h3>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Welcome, Admin</h2>

        <div class="section">
            <h3>Manage Assessments</h3>
            <a href="manage_assessments.php" class="button">Go to Assessments</a>
        </div>

        <div class="section">
            <h3>Manage Training Modules</h3>
            <a href="manage_training.php" class="button">Go to Training Modules</a>
        </div>

        <div class="section">
            <h3>Manage Users</h3>
            <a href="manage_users.php" class="button">Go to Users</a>
        </div>

        <div class="section">
            <h3>View Faculty Profiles</h3>
            <a href="faculty_profiles.php" class="button">View Profiles</a>
        </div>

        <div class="section">
            <h3>View Assessment Reports</h3>
            <a href="assessment_reports.php" class="button">View Reports</a>
        </div>
    </div>

</body>
</html>
