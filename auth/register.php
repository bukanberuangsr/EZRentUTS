<?php
include '../db/db.php';
session_start();

$error_message = '';
$success_message = '';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        $error_message = 'Password dan konfirmasi password tidak cocok!';
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk menyimpan user baru
        $query = "INSERT INTO users (username, userpass) VALUES ('$username', '$hashed_password')";

        if (mysqli_query($conn, $query)) {
            $success_message = 'Registrasi berhasil! Silakan login.';
            header('Location: login.php');
        } else {
            $error_message = 'Sign up failed: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/auth.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <h1 class="header">Sign Up</h1>
        <?php
        if ($error_message) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        if ($success_message) {
            echo "<p style='color: green;'>$success_message</p>";
        }
        ?>
        <form action="" method="post">
            <label for="username">Username:</label><br>
            <input class="input" type="text" name="username" required>
            <br><br>
            <label for="password">Password:</label><br>
            <input class="input" type="password" name="password" required>
            <br><br>
            <label for="confirm_password">Confirm your Password:</label><br>
            <input class="input" type="password" name="confirm_password" required>
            <br><br>
            <input class="submit" type="submit" name="submit" value="Sign Up">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>