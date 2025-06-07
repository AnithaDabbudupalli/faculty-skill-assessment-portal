<?php
session_start();
require 'db_connect.php';

$user_id = 1; // Change to session user ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $update_query = "UPDATE users SET password=? WHERE user_id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_password, $user_id);
    
    if ($stmt->execute()) {
        echo "Password changed successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}
?>
<form method="POST">
    New Password: <input type="password" name="password" required><br>
    <input type="submit" value="Change Password">
</form>
