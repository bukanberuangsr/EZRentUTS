<?php
session_start();
include '../db/db.php';

if (isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit();
}

$error_message = '';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['userpass'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
        if ($user['is_admin']) {
            header('Location: ../pages/admin/dashboard.php'); // Redirect admin users to the admin dashboard
        } else {
            header('Location: ../index.php'); // Redirect regular users to the home page
        }
        exit();
    } else {
        $error_message = 'Username or Password is Incorrect!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/auth.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h1 class="header">Login</h1>
        <?php
        if ($error_message) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>
        <form action="" method="post">
            <label for="username">Username:</label><br>
            <input class="input" type="text" name="username" required>
            <br><br>
            <label for="password">Password:</label><br>
            <input class="input" type="password" name="password" required>
            <br><br>
            <input class="submit" type="submit" name="submit" value="Login">
        </form>
        <p>Don't have an account yet? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>