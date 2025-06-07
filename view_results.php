<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user assessment results
$query = $conn->prepare("
    SELECT ar.score, a.title, ar.timestamp 
    FROM assessment_results ar
    JOIN assessments a ON ar.assessment_id = a.id
    WHERE ar.user_id = ?
    ORDER BY ar.timestamp DESC
");
$query->bind_param("i", $user_id);
$query->execute();
$results = $query->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Assessment Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        table {
            width: 60%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #1DA1F2;
            color: white;
        }
    </style>
</head>
<body>
    <h2>My Assessment Results</h2>
    
    <?php if ($results->num_rows > 0): ?>
        <table>
            <tr>
                <th>Assessment</th>
                <th>Score</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $results->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo $row['score']; ?></td>
                    <td><?php echo $row['timestamp']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No assessments taken yet.</p>
    <?php endif; ?>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
