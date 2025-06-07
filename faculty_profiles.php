<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all faculty profiles
$sql = "SELECT users.user_id, users.name, users.email, users.department, 
               assessments.title AS assessment_title, results.score
        FROM users 
        LEFT JOIN results ON users.user_id = results.user_id
        LEFT JOIN assessments ON results.assessment_id = assessments.id
        WHERE users.role = 'faculty' 
        ORDER BY users.user_id";
$result = $conn->query($sql);

$faculty_profiles = [];
while ($row = $result->fetch_assoc()) {
    $faculty_profiles[$row['user_id']]['name'] = $row['name'];
    $faculty_profiles[$row['user_id']]['email'] = $row['email'];
    $faculty_profiles[$row['user_id']]['department'] = $row['department'];
    $faculty_profiles[$row['user_id']]['assessments'][] = [
        'title' => $row['assessment_title'],
        'score' => $row['score']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Profiles</title>
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
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #3498DB;
            color: white;
        }
        .back-button {
            display: inline-block;
            padding: 10px;
            margin-top: 20px;
            text-decoration: none;
            background: #3498DB;
            color: white;
            border-radius: 5px;
            transition: 0.3s;
        }
        .back-button:hover {
            background: #2980B9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Faculty Profiles</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Assessments & Scores</th>
            </tr>
            <?php foreach ($faculty_profiles as $profile) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($profile['name']); ?></td>
                    <td><?php echo htmlspecialchars($profile['email']); ?></td>
                    <td><?php echo htmlspecialchars($profile['department']); ?></td>
                    <td>
                        <?php 
                        if (!empty($profile['assessments'])) {
                            foreach ($profile['assessments'] as $assessment) {
                                if ($assessment['title']) {
                                    echo htmlspecialchars($assessment['title']) . ': ' . htmlspecialchars($assessment['score']) . '<br>';
                                }
                            }
                        } else {
                            echo "No assessments taken";
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <a href="admindashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>
