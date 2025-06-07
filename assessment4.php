<?php
session_start();
include 'db_connect.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

echo "<h2>Assessment 4 - C Programming</h2>";

// Check if user has already taken the assessment
$check_query = "SELECT COUNT(*) FROM results WHERE user_id = ? AND assessment_name = 'Assessment 4'";
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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    $total_questions = 20;

    // Correct answers
    $correct_answers = [
        1 => "C", 2 => "A", 3 => "D", 4 => "B", 5 => "C", 
        6 => "D", 7 => "A", 8 => "B", 9 => "D", 10 => "C", 
        11 => "A", 12 => "D", 13 => "B", 14 => "C", 15 => "A", 
        16 => "B", 17 => "D", 18 => "A", 19 => "C", 20 => "B"
    ];

    foreach ($correct_answers as $q_id => $answer) {
        if (isset($_POST["q$q_id"]) && $_POST["q$q_id"] === $answer) {
            $score++;
        }
    }

    // Store results in database
    $stmt = $conn->prepare("INSERT INTO results (user_id, assessment_name, score) VALUES (?, 'Assessment 4', ?)");
    $stmt->bind_param("ii", $user_id, $score);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "<script>alert('Test submitted! Your score: $score / 20'); window.location.href='dashboard.php';</script>";
    exit();
}

// Questions for the assessment
$questions = [
    1 => ["question" => "Which of the following is a valid C identifier?", 
          "options" => ["A) 1variable", "B) &main", "C) _varName", "D) #define"]],
    2 => ["question" => "What is the size of an `int` in C (on most systems)?", 
          "options" => ["A) 4 bytes", "B) 2 bytes", "C) 8 bytes", "D) 16 bytes"]],
    3 => ["question" => "Which operator is used to access the value at the address stored in a pointer?", 
          "options" => ["A) &", "B) ->", "C) []", "D) *"]],
    4 => ["question" => "What will `printf(\"%d\", 5 / 2);` output?", 
          "options" => ["A) 2.5", "B) 2", "C) 5", "D) 2.0"]],
    5 => ["question" => "Which library function is used to find the length of a string?", 
          "options" => ["A) strcpy()", "B) strcat()", "C) strlen()", "D) strcmp()"]],
    6 => ["question" => "Which of the following is a storage class in C?", 
          "options" => ["A) virtual", "B) public", "C) friend", "D) static"]],
    7 => ["question" => "Which loop is guaranteed to execute at least once?", 
          "options" => ["A) do-while", "B) while", "C) for", "D) if"]],
    8 => ["question" => "Which keyword is used to define a macro in C?", 
          "options" => ["A) define", "B) #define", "C) const", "D) typedef"]],
    9 => ["question" => "What is the default return type of a function in C if no return type is specified?", 
          "options" => ["A) float", "B) char", "C) double", "D) int"]],
    10 => ["question" => "Which data structure uses Last In First Out (LIFO) order?", 
          "options" => ["A) Queue", "B) Array", "C) Stack", "D) Linked List"]],
    11 => ["question" => "Which of the following is not a valid data type in C?", 
          "options" => ["A) bool", "B) char", "C) int", "D) float"]],
    12 => ["question" => "Which operator is used for bitwise AND operation?", 
          "options" => ["A) &&", "B) ||", "C) ==", "D) &"]],
    13 => ["question" => "What is the output of `printf(\"%c\", 65);`?", 
          "options" => ["A) 65", "B) A", "C) Error", "D) a"]],
    14 => ["question" => "Which function is used to allocate memory dynamically?", 
          "options" => ["A) scanf()", "B) malloc()", "C) free()", "D) realloc()"]],
    15 => ["question" => "What is the index of the first element in an array in C?", 
          "options" => ["A) 0", "B) 1", "C) -1", "D) n"]],
    16 => ["question" => "Which of the following is not a loop structure in C?", 
          "options" => ["A) for", "B) switch", "C) while", "D) do-while"]],
    17 => ["question" => "Which function is used to compare two strings?", 
          "options" => ["A) strcat()", "B) strlen()", "C) strcpy()", "D) strcmp()"]],
    18 => ["question" => "Which operator is used to access structure members?", 
          "options" => ["A) .", "B) ->", "C) &", "D) *"]],
    19 => ["question" => "Which header file is required for input/output functions?", 
          "options" => ["A) conio.h", "B) stdlib.h", "C) stdio.h", "D) string.h"]],
    20 => ["question" => "Which of the following is an escape sequence in C?", 
          "options" => ["A) \\n", "B) \\t", "C) \\\\", "D) All of the above"]]
];

?>

<form method="POST" action="">
    <?php
    foreach ($questions as $q_id => $q_data) {
        echo "<p><b>{$q_id}. {$q_data['question']}</b></p>";
        foreach ($q_data['options'] as $option) {
            $option_value = substr($option, 0, 1); // Extract option letter (A, B, C, D)
            echo "<label><input type='radio' name='q$q_id' value='$option_value'> $option</label><br>";
        }
    }
    ?>
    <br>
    <input type="submit" value="Submit">
</form>
