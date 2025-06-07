<?php
include 'db_connect.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $role = $_POST['role'];

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already registered. Please login.";
    } else {
        // Insert user into database
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        
        if ($stmt->execute()) {
            echo "Registration successful! <a href='login.html'>Login here</a>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $stmt->close();
}
$conn->close();
?>
