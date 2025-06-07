<?php
session_start();
include 'db_connect.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

echo "<h2>Assessment 1 - Computer Science</h2>";

// Check if user has already taken the assessment
$check_query = "SELECT COUNT(*) FROM results WHERE user_id = ? AND assessment_name = 'Assessment 1'";
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
        1 => "B", 2 => "C", 3 => "A", 4 => "D", 5 => "C", 
        6 => "B", 7 => "A", 8 => "D", 9 => "C", 10 => "A", 
        11 => "B", 12 => "D", 13 => "C", 14 => "A", 15 => "B", 
        16 => "C", 17 => "D", 18 => "A", 19 => "B", 20 => "C"
    ];

    foreach ($correct_answers as $q_id => $answer) {
        if (isset($_POST["q$q_id"]) && $_POST["q$q_id"] === $answer) {
            $score++;
        }
    }

    // Store results in database

    // Fixed total number of questions
$stmt = $conn->prepare("INSERT INTO results (user_id, assessment_name, score) VALUES (?, 'Assessment 1', ?)");
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
        1 => ["question" => "Which data structure is used in recursion?", "options" => ["A) Queue", "B) Stack", "C) Linked List", "D) Tree"]],
        2 => ["question" => "What is the time complexity of binary search?", "options" => ["A) O(n)", "B) O(n log n)", "C) O(log n)", "D) O(1)"]],
        3 => ["question" => "Which sorting algorithm has the best time complexity in the worst case?", "options" => ["A) Merge Sort", "B) Quick Sort", "C) Bubble Sort", "D) Selection Sort"]],
        4 => ["question" => "Which of the following is not a relational database?", "options" => ["A) MySQL", "B) PostgreSQL", "C) Oracle", "D) MongoDB"]],
        5 => ["question" => "What does ACID stand for in DBMS?", "options" => ["A) Atom, Control, Isolation, Durability", "B) Access, Commit, Integrity, Database", "C) Atomicity, Consistency, Isolation, Durability", "D) Associative, Consistent, Inherent, Durable"]],
        6 => ["question" => "Which of these is not a CPU scheduling algorithm?", "options" => ["A) FCFS", "B) LRU", "C) SJF", "D) Round Robin"]],
        7 => ["question" => "Which layer in the OSI model handles routing?", "options" => ["A) Network", "B) Transport", "C) Session", "D) Application"]],
        8 => ["question" => "Which protocol is used for email retrieval?", "options" => ["A) SMTP", "B) TCP", "C) FTP", "D) IMAP"]],
        9 => ["question" => "Which data structure is used in BFS?", "options" => ["A) Stack", "B) Heap", "C) Queue", "D) Tree"]],
        10 => ["question" => "Which of the following is not an object-oriented programming language?", "options" => ["A) C", "B) C++", "C) Java", "D) Python"]],
        11 => ["question" => "Which SQL command is used to remove a record?", "options" => ["A) REMOVE", "B) DELETE", "C) ERASE", "D) DROP"]],
        12 => ["question" => "Which scheduling algorithm uses time slices?", "options" => ["A) SJF", "B) FCFS", "C) Priority Scheduling", "D) Round Robin"]],
        13 => ["question" => "Which of the following is a NoSQL database?", "options" => ["A) MySQL", "B) PostgreSQL", "C) MongoDB", "D) Oracle"]],
        14 => ["question" => "Which algorithm is used in public-key cryptography?", "options" => ["A) RSA", "B) DES", "C) AES", "D) MD5"]],
        15 => ["question" => "Which part of a computer executes instructions?", "options" => ["A) RAM", "B) CPU", "C) Hard Drive", "D) Monitor"]],
        16 => ["question" => "What is the main function of an operating system?", "options" => ["A) Compiling code", "B) Running applications", "C) Managing hardware and software", "D) Editing text"]],
        17 => ["question" => "What does TCP stand for?", "options" => ["A) Transfer Control Protocol", "B) Trusted Communication Protocol", "C) Transmission Computer Protocol", "D) Transmission Control Protocol"]],
        18 => ["question" => "Which data structure follows FIFO?", "options" => ["A) Queue", "B) Stack", "C) Tree", "D) Graph"]],
        19 => ["question" => "Which command is used to display the structure of a table in SQL?", "options" => ["A) SHOW", "B) DESCRIBE", "C) STRUCTURE", "D) DISPLAY"]],
        20 => ["question" => "What is the default port for HTTP?", "options" => ["A) 21", "B) 22", "C) 80", "D) 443"]]
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
