<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    $total_questions = 20;
    
    // Correct answers (for demonstration)
    $correct_answers = [
        'q1' => 'B', 'q2' => 'D', 'q3' => 'C', 'q4' => 'A', 'q5' => 'B',
        'q6' => 'D', 'q7' => 'A', 'q8' => 'C', 'q9' => 'B', 'q10' => 'D',
        'q11' => 'A', 'q12' => 'C', 'q13' => 'B', 'q14' => 'D', 'q15' => 'A',
        'q16' => 'B', 'q17' => 'D', 'q18' => 'C', 'q19' => 'A', 'q20' => 'B'
    ];
    
    foreach ($correct_answers as $question => $correct) {
        if (isset($_POST[$question]) && $_POST[$question] == $correct) {
            $score++;
        }
    }
    
    // Insert result into database
    $stmt = $conn->prepare("INSERT INTO assessment_results (user_id, username, assessment_title, score) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $user_id, $username, 'CSE Assessment', $score);
    $stmt->execute();
    $stmt->close();
    
    $_SESSION['last_score'] = $score;
    header("Location: assessment_result.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSE Assessment</title>
</head>
<body>
    <h2>CSE Assessment</h2>
    <form method="POST" action="">
        <p>1. What is the time complexity of binary search?</p>
        <input type="radio" name="q1" value="A"> A) O(n)<br>
        <input type="radio" name="q1" value="B"> B) O(log n)<br>
        <input type="radio" name="q1" value="C"> C) O(n log n)<br>
        <input type="radio" name="q1" value="D"> D) O(1)<br>
        
        <p>2. What is a primary key in databases?</p>
        <input type="radio" name="q2" value="A"> A) A unique constraint<br>
        <input type="radio" name="q2" value="B"> B) A foreign key<br>
        <input type="radio" name="q2" value="C"> C) An index<br>
        <input type="radio" name="q2" value="D"> D) A unique identifier for records<br>

        <!-- Repeat similar blocks for remaining 18 questions -->
        
        <button type="submit">Submit</button>
    </form>
</body>
</html>
