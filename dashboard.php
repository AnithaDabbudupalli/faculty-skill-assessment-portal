<?php
session_start();
require 'db_connect.php'; // Ensure database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'faculty'; // Default role

// Fetch user details
$query = "SELECT name, email, department, profile_photo FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Set default profile photo if empty
$profile_photo = !empty($user['profile_photo']) ? $user['profile_photo'] : "default-profile.png";

// Fetch assessment scores
$scores = [];
$score_query = "SELECT assessment_name, score FROM results WHERE user_id = ?";
$score_stmt = $conn->prepare($score_query);
if ($score_stmt) {
    $score_stmt->bind_param("i", $user_id);
    $score_stmt->execute();
    $score_stmt->bind_result($assessment_name, $score);
    while ($score_stmt->fetch()) {
        // Fetch total questions for each assessment dynamically
        $total_questions_query = "SELECT COUNT(*) FROM questions WHERE assessment_name = ?";
        $total_stmt = $conn->prepare($total_questions_query);
        if ($total_stmt) {
            $total_stmt->bind_param("s", $assessment_name);
            $total_stmt->execute();
            $total_stmt->bind_result($total_questions);
            $total_stmt->fetch();
            $total_stmt->close();
        } else {
            $total_questions = "Unknown"; // If no question count is found
        }

        $scores[] = [
            "name" => $assessment_name,
            "score" => $score,
            "total_questions" => $total_questions
        ];
    }
    $score_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 30px;
        }
        .dashboard-container {
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .details {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 10px;
        }
        .btn {
            background-color: #3498DB;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            display: inline-block;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #2980B9;
        }
        .assessment-list {
            text-align: left;
            margin-top: 10px;
        }
        .assessment-list li {
            list-style: none;
            background: #ecf0f1;
            padding: 8px;
            margin: 5px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2>User Dashboard</h2>
    <img src="uploads/<?php echo htmlspecialchars($profile_photo); ?>" class="profile-photo" alt="Profile Photo">
    <p class="details"><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
    <p class="details"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p class="details"><strong>Department:</strong> <?php echo htmlspecialchars($user['department']); ?></p>

    <a href="edit_profile.php" class="btn">Edit Profile</a>
    <a href="change_password.php" class="btn">Change Password</a>
    <a href="logout.php" class="btn">Logout</a>

    <h3>Assessment Scores</h3>
    <ul class="assessment-list">
        <?php if (!empty($scores)): ?>
            <?php foreach ($scores as $score): ?>
                <li><strong><?php echo htmlspecialchars($score['name']); ?>:</strong> 
                    <?php echo htmlspecialchars($score['score']) . "/20"; ?>

                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No assessments taken yet.</li>
        <?php endif; ?>
    </ul>
</div>

</body>
</html>

