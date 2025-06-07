<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment List</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            margin-top: 50px;
        }

        h2 {
            color: #333;
        }

        .assessment-list {
            list-style: none;
            padding: 0;
        }

        .assessment-list li {
            margin: 15px 0;
        }

        .assessment-list a {
            text-decoration: none;
            display: block;
            background: #3498db;
            color: white;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s;
        }

        .assessment-list a:hover {
            background: #2980b9;
        }

        .dashboard-btn {
            display: inline-block;
            background: #2ecc71;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s;
            margin-top: 20px;
        }

        .dashboard-btn:hover {
            background: #27ae60;
        }

        .logout-btn {
            display: inline-block;
            background: #e74c3c;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s;
            margin-top: 10px;
        }

        .logout-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
    <p>Select an assessment to test your skills:</p>

    <!-- List of Assessments -->
    <ul class="assessment-list">
        <li><a href="assessment1.php">Assessment 1</a></li>
        <li><a href="assessment2.php">Assessment 2</a></li>
        <li><a href="assessment3.php">Assessment 3</a></li>
        <li><a href="assessment4.php">Assessment 4</a></li>
        <li><a href="assessment5.php">Assessment 5</a></li>
        <li><a href="assessment6.php">Assessment 6</a></li>
    </ul>

    <!-- Button to go to Dashboard -->
    <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
    
    <!-- Logout Button -->
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

</body>
</html>

