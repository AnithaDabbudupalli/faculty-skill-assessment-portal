<?php
session_start();
include 'db_connect.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

echo "<h2>Assessment 3 - Data Structures</h2>";

// Check if user has already taken the assessment
$check_query = "SELECT COUNT(*) FROM results WHERE user_id = ? AND assessment_name = 'Assessment 3'";
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
        1 => "B", 2 => "C", 3 => "D", 4 => "A", 5 => "C", 
        6 => "B", 7 => "D", 8 => "C", 9 => "A", 10 => "B", 
        11 => "D", 12 => "C", 13 => "A", 14 => "B", 15 => "D", 
        16 => "C", 17 => "A", 18 => "B", 19 => "D", 20 => "C"
    ];

    foreach ($correct_answers as $q_id => $answer) {
        if (isset($_POST["q$q_id"]) && $_POST["q$q_id"] === $answer) {
            $score++;
        }
    }

    // Store results in database
    $stmt = $conn->prepare("INSERT INTO results (user_id, assessment_name, score) VALUES (?, 'Assessment 3', ?)");
    $stmt->bind_param("ii", $user_id, $score);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "<script>alert('Test submitted! Your score: $score / 20'); window.location.href='dashboard.php';</script>";
    exit();
}

// Questions for the assessment
$questions = [
    1 => ["question" => "Which data structure follows FIFO principle?", "options" => ["A) Stack", "B) Queue", "C) Linked List", "D) Graph"]],
    2 => ["question" => "What is the time complexity of inserting at the beginning of a linked list?", "options" => ["A) O(n)", "B) O(log n)", "C) O(1)", "D) O(n log n)"]],
    3 => ["question" => "Which sorting algorithm is the most efficient in the worst case?", "options" => ["A) Bubble Sort", "B) Selection Sort", "C) Insertion Sort", "D) Merge Sort"]],
    4 => ["question" => "Which data structure is used in BFS?", "options" => ["A) Queue", "B) Stack", "C) Heap", "D) Tree"]],
    5 => ["question" => "What is the worst-case time complexity of Quick Sort?", "options" => ["A) O(n log n)", "B) O(log n)", "C) O(n^2)", "D) O(n)"]],
    6 => ["question" => "Which of the following data structures can be used to implement a priority queue?", "options" => ["A) Stack", "B) Heap", "C) Queue", "D) Linked List"]],
    7 => ["question" => "Which traversal method is used for Depth First Search?", "options" => ["A) Level Order", "B) Inorder", "C) BFS", "D) Preorder"]],
    8 => ["question" => "Which data structure is used for recursion?", "options" => ["A) Queue", "B) Heap", "C) Stack", "D) Linked List"]],
    9 => ["question" => "Which of the following operations has the lowest time complexity in a hash table?", "options" => ["A) Searching", "B) Sorting", "C) Traversing", "D) Inserting"]],
    10 => ["question" => "Which type of tree is used in databases?", "options" => ["A) Binary Tree", "B) B-Tree", "C) AVL Tree", "D) Red-Black Tree"]],
    11 => ["question" => "What is the maximum number of children a binary tree node can have?", "options" => ["A) 1", "B) 2", "C) 3", "D) Any number"]],
    12 => ["question" => "Which sorting algorithm is the best for nearly sorted data?", "options" => ["A) Bubble Sort", "B) Quick Sort", "C) Insertion Sort", "D) Merge Sort"]],
    13 => ["question" => "What is the space complexity of Merge Sort?", "options" => ["A) O(n)", "B) O(1)", "C) O(log n)", "D) O(n log n)"]],
    14 => ["question" => "Which operation is faster in a doubly linked list than in a singly linked list?", "options" => ["A) Searching", "B) Deleting a node", "C) Insertion at end", "D) Traversing"]],
    15 => ["question" => "Which of the following data structures provides constant-time access to elements by index?", "options" => ["A) Queue", "B) Stack", "C) Linked List", "D) Array"]],
    16 => ["question" => "What is the best case time complexity of Bubble Sort?", "options" => ["A) O(n^2)", "B) O(n)", "C) O(n log n)", "D) O(1)"]],
    17 => ["question" => "Which of the following is a self-balancing binary search tree?", "options" => ["A) AVL Tree", "B) B-Tree", "C) Heap", "D) Binary Tree"]],
    18 => ["question" => "Which algorithm is used for finding the shortest path in a graph?", "options" => ["A) Dijkstra's Algorithm", "B) Bubble Sort", "C) Kruskal's Algorithm", "D) Merge Sort"]],
    19 => ["question" => "Which data structure is used in LRU cache implementation?", "options" => ["A) Stack", "B) Hash Map & Doubly Linked List", "C) Queue", "D) Binary Tree"]],
    20 => ["question" => "Which of the following operations has the best time complexity in a balanced BST?", "options" => ["A) O(n)", "B) O(log n)", "C) O(n^2)", "D) O(1)"]]
];

?>

<form method="POST" action="">
    <?php
    foreach ($questions as $q_id => $q_data) {
        echo "<p><strong>Q$q_id. " . $q_data["question"] . "</strong></p>";
        foreach ($q_data["options"] as $option) {
            $option_value = substr($option, 0, 1); // Extract option letter (A, B, C, D)
            echo "<input type='radio' name='q$q_id' value='$option_value' required> $option <br>";
        }
        echo "<br>";
    }
    ?>
    <input type="submit" value="Submit">
</form>
