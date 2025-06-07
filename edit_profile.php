<?php
session_start();
include("db_connect.php"); // Include database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details from database
$query = "SELECT name, email, department, profile_photo FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $profile_photo = $user['profile_photo']; // Default to existing photo

    // Handle Profile Photo Upload
    if (!empty($_FILES["profile_photo"]["name"])) {
        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true); // Create folder if it doesn't exist
        }

        $file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES["profile_photo"]["name"]);
        $target_file = "uploads/" . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "<script>alert('Error: Only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        } elseif (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            $profile_photo = $file_name; // Update profile photo path
        } else {
            echo "<script>alert('Error uploading the profile photo.');</script>";
        }
    }

    // Update user details (including profile photo)
    $update_query = "UPDATE users SET name=?, email=?, department=?, profile_photo=? WHERE user_id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $name, $email, $department, $profile_photo, $user_id);
    $stmt->execute();

    // Handle Password Change
    if (!empty($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $update_password_query = "UPDATE users SET password=? WHERE user_id=?";
        $stmt = $conn->prepare($update_password_query);
        $stmt->bind_param("si", $new_password, $user_id);
        $stmt->execute();
    }

    echo "<script>alert('Profile updated successfully!'); window.location.href='dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
        }
        label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        img {
            margin-top: 10px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Profile</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label>Department:</label>
        <select name="department" required>
            <option value="Computer Science and Engineering" <?php echo ($user['department'] == "Computer Science and Engineering") ? 'selected' : ''; ?>>Computer Science and Engineering</option>
            <option value="Electrical and Electronics Engineering" <?php echo ($user['department'] == "Electrical and Electronics Engineering") ? 'selected' : ''; ?>>Electrical and Electronics Engineering</option>
            <option value="Electronics and Communication Engineering" <?php echo ($user['department'] == "Electronics and Communication Engineering") ? 'selected' : ''; ?>>Electronics and Communication Engineering</option>
            <option value="Civil Engineering" <?php echo ($user['department'] == "Civil Engineering") ? 'selected' : ''; ?>>Civil Engineering</option>
            <option value="Mechanical Engineering" <?php echo ($user['department'] == "Mechanical Engineering") ? 'selected' : ''; ?>>Mechanical Engineering</option>
            <option value="Chemical Engineering" <?php echo ($user['department'] == "Chemical Engineering") ? 'selected' : ''; ?>>Chemical Engineering</option>
        </select>

        <label>Profile Photo:</label>
        <input type="file" name="profile_photo" accept="image/*">
        <?php if (!empty($user['profile_photo'])): ?>
            <img src="uploads/<?php echo $user['profile_photo']; ?>" alt="Profile Photo">
        <?php endif; ?>

        <label>New Password (Leave blank if not changing):</label>
        <input type="password" name="new_password">

        <input type="submit" class="btn" value="Update Profile">
    </form>
</div>

</body>
</html>


