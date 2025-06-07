<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Store the selected assessment in session and redirect to login
    $_SESSION['pending_assessment'] = $_POST['assessment_title'];
    header("Location: login.php");
    exit();
}

// If user is logged in, redirect to the assessment page
$assessment_title = $_POST['assessment_title'];
header("Location: take_assessment.php?title=" . urlencode($assessment_title));
exit();
?>
