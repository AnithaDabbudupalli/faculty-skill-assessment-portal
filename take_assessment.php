<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$assessment_id = $_GET['id'] ?? null;

if (!$assessment_id) {
    echo "Invalid assessment!";
    exit();
}

// Fetch assessment details
$assessmentQuery = $conn->prepare("SELECT * FROM assessments WHERE id = ?");
$assessmentQuery->bind_param("i", $assessment_id);
$assessmentQuery->execute();
$assessmentResult = $assessmentQuery->get_result();

if ($assessmentResult->num_rows === 0) {
    echo "Assessment not found!";
    exit();
}

$assessment = $assessmentResult->fetch_assoc();

// Fetch questions for the assessment
$questionsQuery = $conn->prepare("SELECT * FROM questions WHERE assessment_id = ?");
$questionsQuery->bind_param("i", $assessment_id);
$questionsQuery->execute();
$questionsResult = $questionsQuery->get_result();

$questions = [];
while ($row = $questionsResult->fetch_assoc()) {
    $questions[] = $row;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    $totalQuestions = count($questions);
    
    foreach ($questions as $question) {
        $q_id = $question['id'];
        $correct_answer = $question['correct_answer'];
        $user_answer = $_POST["q$q_id"] ?? null;

        if ($user_answer === $correct_answer) {
            $score++;
        }
    }

    // Store the score in database
    $insertScoreQuery = $conn->prepare("INSERT INTO assessment_results (user_id, assessment_id, score) VALUES (?, ?, ?)");
    $insertScoreQuery->bind_param("iii", $user_id, $assessment_id, $score);
    $insertScoreQuery->execute();

    echo "<h2>Assessment Completed</h2>";
    echo "<p>Your Score: $score / $totalQuestions</p>";
    echo "<a href='dashboard.php'>Go Back</a>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $assessment['title']; ?></title>
</head>
<body>
    <h2><?php echo $assessment['title']; ?></h2>
    <form method="POST">
        <?php foreach ($questions as $index => $question): ?>
            <p><?php echo ($index + 1) . ". " . $question['question_text']; ?></p>
            <input type="radio" name="q<?php echo $question['id']; ?>" value="A"> A) <?php echo $question['option_a']; ?><br>
            <input type="radio" name="q<?php echo $question['id']; ?>" value="B"> B) <?php echo $question['option_b']; ?><br>
            <input type="radio" name="q<?php echo $question['id']; ?>" value="C"> C) <?php echo $question['option_c']; ?><br>
            <input type="radio" name="q<?php echo $question['id']; ?>" value="D"> D) <?php echo $question['option_d']; ?><br>
            <br>
        <?php endforeach; ?>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
