<?php
session_start();
include 'db_connect.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

echo "<h2>Assessment 6 - Computer Networks and Network Security</h2>";

// Check if user has already taken the assessment
$check_query = "SELECT COUNT(*) FROM results WHERE user_id = ? AND assessment_name = 'Assessment 6'";
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
        1 => "C", 2 => "A", 3 => "D", 4 => "B", 5 => "C", 
        6 => "D", 7 => "A", 8 => "B", 9 => "C", 10 => "D", 
        11 => "A", 12 => "C", 13 => "B", 14 => "D", 15 => "A", 
        16 => "B", 17 => "C", 18 => "D", 19 => "A", 20 => "B"
    ];

    foreach ($correct_answers as $q_id => $answer) {
        if (isset($_POST["q$q_id"]) && $_POST["q$q_id"] === $answer) {
            $score++;
        }
    }

    // Store results in database
    $stmt = $conn->prepare("INSERT INTO results (user_id, assessment_name, score) VALUES (?, 'Assessment 6', ?)");
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
        1 => ["question" => "What is the primary function of a router?", "options" => ["A) Encrypt data", "B) Store network configurations", "C) Forward data packets", "D) Control bandwidth"]],
        2 => ["question" => "Which protocol is used for secure data transmission over the Internet?", "options" => ["A) HTTPS", "B) HTTP", "C) FTP", "D) SMTP"]],
        3 => ["question" => "What is a firewall used for?", "options" => ["A) Enhancing network speed", "B) Connecting different networks", "C) Storing user data", "D) Filtering network traffic"]],
        4 => ["question" => "Which OSI layer is responsible for end-to-end communication?", "options" => ["A) Data Link", "B) Transport", "C) Network", "D) Session"]],
        5 => ["question" => "What does VPN stand for?", "options" => ["A) Virtual Private Node", "B) Virtual Protocol Network", "C) Virtual Private Network", "D) Variable Private Network"]],
        6 => ["question" => "Which protocol is used for sending emails?", "options" => ["A) POP3", "B) IMAP", "C) FTP", "D) SMTP"]],
        7 => ["question" => "What is an IP address?", "options" => ["A) A unique identifier for a network device", "B) A type of encryption method", "C) A type of network topology", "D) A file-sharing protocol"]],
        8 => ["question" => "Which of the following is NOT a type of network topology?", "options" => ["A) Star", "B) Mesh", "C) Ring", "D) Bridge"]],
        9 => ["question" => "What does DNS stand for?", "options" => ["A) Domain Name System", "B) Digital Network Service", "C) Dynamic Name Server", "D) Data Name Storage"]],
        10 => ["question" => "Which of these is a security threat?", "options" => ["A) Firewall", "B) VPN", "C) Phishing", "D) Proxy"]],
        11 => ["question" => "Which encryption method is commonly used for secure web communication?", "options" => ["A) SSL/TLS", "B) SHA", "C) AES", "D) DES"]],
        12 => ["question" => "Which layer of the OSI model deals with MAC addresses?", "options" => ["A) Network", "B) Transport", "C) Data Link", "D) Application"]],
        13 => ["question" => "What is a DDoS attack?", "options" => ["A) Data Distribution over System", "B) Distributed Denial of Service", "C) Digital Data Output Security", "D) Direct Data Operation System"]],
        14 => ["question" => "What is a proxy server used for?", "options" => ["A) Directly connecting devices", "B) Enhancing security and privacy", "C) Monitoring real-time traffic", "D) Encrypting passwords"]],
        15 => ["question" => "Which protocol is used to assign IP addresses dynamically?", "options" => ["A) TCP", "B) DHCP", "C) UDP", "D) ARP"]],
        16 => ["question" => "What does ARP stand for?", "options" => ["A) Address Routing Protocol", "B) Address Resolution Protocol", "C) Automated Routing Process", "D) Advanced Routing Path"]],
        17 => ["question" => "Which security concept ensures that data has not been altered?", "options" => ["A) Confidentiality", "B) Integrity", "C) Availability", "D) Authentication"]],
        18 => ["question" => "What is the default port for HTTPS?", "options" => ["A) 21", "B) 22", "C) 443", "D) 80"]],
        19 => ["question" => "What is the purpose of an Intrusion Detection System (IDS)?", "options" => ["A) Preventing cyberattacks", "B) Monitoring network traffic for threats", "C) Encrypting sensitive information", "D) Assigning IP addresses"]],
        20 => ["question" => "Which layer of the OSI model establishes sessions between applications?", "options" => ["A) Network", "B) Transport", "C) Session", "D) Presentation"]]
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