<?php
session_start();
include 'db_connect.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

echo "<h2>Assessment 2 - Aptitude and Reasoning</h2>";

// Check if user has already taken the assessment
$check_query = "SELECT COUNT(*) FROM results WHERE user_id = ? AND assessment_name = 'Assessment 2'";
$check_stmt = $conn->prepare($check_query);
if (!$check_stmt) {
    die("Query Error: " . $conn->error);
}
$check_stmt->bind_param("i", $user_id);
$check_stmt->execute();
$check_stmt->bind_result($attempt_count);
$check_stmt->fetch();
$check_stmt->close();

if ($attempt_count > 0) {
    echo "<p style='color: red;'>You have already attempted this assessment. Only one attempt is allowed.</p>";
    echo "<a href='dashboard.php'>Go to Dashboard</a>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    $total_questions = 20;

    // Correct answers
    $correct_answers = [
        1 => "C", 2 => "B", 3 => "A", 4 => "D", 5 => "B", 
        6 => "A", 7 => "D", 8 => "C", 9 => "B", 10 => "A", 
        11 => "D", 12 => "C", 13 => "A", 14 => "B", 15 => "D", 
        16 => "C", 17 => "A", 18 => "B", 19 => "C", 20 => "D"
    ];

    foreach ($correct_answers as $q_id => $answer) {
        if (isset($_POST["q$q_id"]) && $_POST["q$q_id"] === $answer) {
            $score++;
        }
    }

    // Store results in database
    $stmt = $conn->prepare("INSERT INTO results (user_id, assessment_name, score) VALUES (?, 'Assessment 2', ?)");
    $stmt->bind_param("ii", $user_id, $score);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "<script>alert('Test submitted! Your score: $score / 20'); window.location.href='dashboard.php';</script>";
    exit();
}
?>

<form method="POST" action="">
    <?php
    $questions = [
        1 => ["question" => "What comes next in the sequence: 2, 6, 12, 20, ?", "options" => ["A) 28", "B) 30", "C) 32", "D) 36"]],
        2 => ["question" => "Find the missing number: 8, 27, 64, ?", "options" => ["A) 81", "B) 125", "C) 216", "D) 343"]],
        3 => ["question" => "Which word does not belong to the group?", "options" => ["A) Apple", "B) Orange", "C) Mango", "D) Carrot"]],
        4 => ["question" => "A train running at 90 km/hr crosses a pole in 10 sec. What is its length?", "options" => ["A) 150m", "B) 200m", "C) 250m", "D) 300m"]],
        5 => ["question" => "If A is coded as 1, B as 2, then how is CAT coded?", "options" => ["A) 312", "B) 321", "C) 123", "D) 132"]],
        6 => ["question" => "If today is Monday, what will be the day after 63 days?", "options" => ["A) Wednesday", "B) Thursday", "C) Friday", "D) Saturday"]],
        7 => ["question" => "A man walks 10m North, then 10m East, then 10m South, then 10m West. Where is he now?", "options" => ["A) 10m East", "B) 10m West", "C) 10m North", "D) Same Place"]],
        8 => ["question" => "What is the next number in the series: 1, 1, 2, 3, 5, 8, ?", "options" => ["A) 10", "B) 11", "C) 13", "D) 15"]],
        9 => ["question" => "What is 20% of 150?", "options" => ["A) 25", "B) 30", "C) 35", "D) 40"]],
        10 => ["question" => "What is the sum of the angles in a triangle?", "options" => ["A) 180°", "B) 90°", "C) 270°", "D) 360°"]],
        11 => ["question" => "Which number is a prime number?", "options" => ["A) 15", "B) 21", "C) 39", "D) 37"]],
        12 => ["question" => "A clock shows 4:15. What is the angle between the hour and minute hands?", "options" => ["A) 30°", "B) 45°", "C) 52.5°", "D) 67.5°"]],
        13 => ["question" => "What is the next letter in the sequence: B, D, G, K, ?", "options" => ["A) P", "B) Q", "C) R", "D) S"]],
        14 => ["question" => "Solve: 5 + 3 × 6 - 2", "options" => ["A) 21", "B) 28", "C) 33", "D) 35"]],
        15 => ["question" => "Which is the odd one out?", "options" => ["A) 64", "B) 81", "C) 100", "D) 125"]],
        16 => ["question" => "If 3x - 5 = 16, what is x?", "options" => ["A) 5", "B) 7", "C) 8", "D) 9"]],
        17 => ["question" => "A boat travels 15km upstream in 3 hours. What is its speed?", "options" => ["A) 3km/hr", "B) 4km/hr", "C) 5km/hr", "D) 6km/hr"]],
        18 => ["question" => "Which shape has the most number of sides?", "options" => ["A) Hexagon", "B) Pentagon", "C) Octagon", "D) Decagon"]],
        19 => ["question" => "What is 7 × 9?", "options" => ["A) 56", "B) 63", "C) 72", "D) 81"]],
        20 => ["question" => "Which fraction is largest?", "options" => ["A) 1/4", "B) 2/5", "C) 3/7", "D) 4/9"]]
    ];

    foreach ($questions as $q_id => $q) {
        echo "<p><b>Q$q_id: {$q['question']}</b></p>";
        foreach ($q['options'] as $option) {
            $value = substr($option, 0, 1);
            echo "<input type='radio' name='q$q_id' value='$value' required> $option <br>";
        }
        echo "<br>";
    }
    ?>
    <input type="submit" value="Submit">
</form>
