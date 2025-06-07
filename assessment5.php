<?php
session_start();
include 'db_connect.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

echo "<h2>Assessment 5 - TOC and Compiler Design</h2>";

// Check if user has already taken the assessment
$check_query = "SELECT COUNT(*) FROM results WHERE user_id = ? AND assessment_name = 'Assessment 5'";
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
        1 => "C", 2 => "B", 3 => "A", 4 => "D", 5 => "C", 
        6 => "A", 7 => "D", 8 => "B", 9 => "C", 10 => "A", 
        11 => "D", 12 => "B", 13 => "C", 14 => "A", 15 => "B", 
        16 => "C", 17 => "D", 18 => "A", 19 => "B", 20 => "C"
    ];

    foreach ($correct_answers as $q_id => $answer) {
        if (isset($_POST["q$q_id"]) && $_POST["q$q_id"] === $answer) {
            $score++;
        }
    }

    // Store results in database
    $stmt = $conn->prepare("INSERT INTO results (user_id, assessment_name, score) VALUES (?, 'Assessment 5', ?)");
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
        1 => ["question" => "What is the main component of a DFA?", "options" => ["A) Stack", "B) Queue", "C) Finite States", "D) Memory"]],
        2 => ["question" => "Which of these is a regular language property?", "options" => ["A) Infinite Memory", "B) Closure Under Union", "C) Context-Free", "D) Turing Complete"]],
        3 => ["question" => "What is the primary function of a lexical analyzer?", "options" => ["A) Tokenizing Input", "B) Parsing Grammar", "C) Generating Code", "D) Optimizing Code"]],
        4 => ["question" => "Which phase of the compiler performs syntax checking?", "options" => ["A) Lexical Analysis", "B) Code Generation", "C) Semantic Analysis", "D) Syntax Analysis"]],
        5 => ["question" => "Which grammar type does a compiler use for parsing?", "options" => ["A) Unrestricted", "B) Context-Free", "C) Regular", "D) Recursive"]],
        6 => ["question" => "What does a parse tree represent?", "options" => ["A) Syntax Structure", "B) Machine Code", "C) Lexical Tokens", "D) Code Optimization"]],
        7 => ["question" => "Which parsing technique uses a stack?", "options" => ["A) LL Parsing", "B) Recursive Descent", "C) Predictive Parsing", "D) Shift-Reduce Parsing"]],
        8 => ["question" => "What is the purpose of intermediate code generation?", "options" => ["A) Code Interpretation", "B) Optimization", "C) Platform Independence", "D) Syntax Validation"]],
        9 => ["question" => "Which automaton is used for lexical analysis?", "options" => ["A) Turing Machine", "B) Pushdown Automata", "C) Finite Automaton", "D) Register Machine"]],
        10 => ["question" => "What is an ambiguous grammar?", "options" => ["A) Generates Multiple Parse Trees", "B) Has No Start Symbol", "C) Is Not Recognized by DFA", "D) Uses Left Recursion"]],
        11 => ["question" => "Which normal form is used to simplify context-free grammars?", "options" => ["A) Chomsky Normal Form", "B) Greibach Normal Form", "C) Backus-Naur Form", "D) Regular Expression"]],
        12 => ["question" => "Which component of a compiler optimizes the code?", "options" => ["A) Semantic Analyzer", "B) Code Optimizer", "C) Lexer", "D) Assembler"]],
        13 => ["question" => "Which algorithm is used for bottom-up parsing?", "options" => ["A) Recursive Descent", "B) Earley Parsing", "C) LR Parsing", "D) Predictive Parsing"]],
        14 => ["question" => "What is the function of a symbol table?", "options" => ["A) Stores Identifiers", "B) Generates Code", "C) Detects Errors", "D) Manages Memory"]],
        15 => ["question" => "Which automaton classifies context-free languages?", "options" => ["A) DFA", "B) Pushdown Automata", "C) Finite Automaton", "D) Turing Machine"]],
        16 => ["question" => "What is the primary function of a semantic analyzer?", "options" => ["A) Syntax Checking", "B) Meaning Verification", "C) Code Optimization", "D) Tokenizing Input"]],
        17 => ["question" => "Which parsing method is preferred for compilers?", "options" => ["A) Recursive Descent", "B) LL(1) Parsing", "C) LR Parsing", "D) Shift-Reduce"]],
        18 => ["question" => "What does an abstract syntax tree represent?", "options" => ["A) Program Structure", "B) Machine Code", "C) Error Detection", "D) Optimized Code"]],
        19 => ["question" => "Which grammar is used in programming languages?", "options" => ["A) Unrestricted", "B) Regular", "C) Context-Free", "D) Finite State"]],
        20 => ["question" => "Which tool converts high-level code to machine code?", "options" => ["A) Compiler", "B) Assembler", "C) Interpreter", "D) Linker"]]
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
