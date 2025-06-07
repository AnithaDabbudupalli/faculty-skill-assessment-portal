<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $department = $_POST['department'];
    $role = $_POST['role']; // Get selected role

    $sql = "INSERT INTO users (name, email, password, department, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssss", $name, $email, $password, $department, $role);
        if ($stmt->execute()) {
            echo "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Faculty Portal</title>
</head>
<body>
    <h2>Signup</h2>
    <form action="signup.php" method="POST">
        <label>Full Name:</label>
        <input type="text" name="fullname" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <label>Department:</label>
        <select name="department" required>
            <option value="Computer Science and Engineering">Computer Science and Engineering</option>
            <option value="Electrical and Electronics Engineering">Electrical and Electronics Engineering</option>
            <option value="Electronics and Communication Engineering">Electronics and Communication Engineering</option>
            <option value="Civil Engineering">Civil Engineering</option>
            <option value="Mechanical Engineering">Mechanical Engineering</option>
            <option value="Chemical Engineering">Chemical Engineering</option>
        </select><br><br>

        <label>Role:</label>
        <select name="role" required>
            <option value="faculty">Faculty</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <input type="submit" value="Sign Up">
    </form>
</body>
</html>
