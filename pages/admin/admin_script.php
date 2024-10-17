<!--
 This script is used to create an admin account.
 To use it, simply open this file with a web browser.
 Be sure to start your web server and database.
-->
<?php
include './db/db.php';
$username = "admin";
$password = password_hash('admin123', PASSWORD_DEFAULT);
$is_admin = 1;
$query = "INSERT INTO users (username, userpass, is_admin) VALUES ('$username','$password','$is_admin')";
if (mysqli_query($conn, $query)) {
    echo "Admin Account has been created";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>