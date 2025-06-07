<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT user_id, name, password, department, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $name, $hashed_password, $department, $role);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["name"] = $name;
            $_SESSION["department"] = $department;
            $_SESSION["role"] = $role;

            echo "Login successful! Redirecting...";

            // Redirect to assessment list first
            header("refresh:2;url=assessment_list.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='logi.php';</script>";
        }
    } else {
        echo "<script>alert('No account found with this email!'); window.location.href='logi.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
