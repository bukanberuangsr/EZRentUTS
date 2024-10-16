<?php
session_start();
include '../../db/db.php';
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../../index.php');
    exit();
}
$error_message = '';
$success_message = '';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $available = $_POST['available'];

    // Handle the file upload
    if ($_FILES['image']['error'] == 0) {
        $target_dir = "../../uploads/"; // Folder to store uploaded images
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        // Check if the file type is allowed
        if (in_array($imageFileType, $allowed_types)) {
            // Move uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $target_file; // Save the file path
            } else {
                $error_message = "Sorry, there was an error uploading the image.";
            }
        } else {
            $error_message = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        $error_message = "No image uploaded.";
    }

    // If there's no error with the file upload and form input is valid
    if (empty($error_message) && !empty($name) && !empty($description) && is_numeric($available) && $available >= 0) {
        // Insert the new item into the database along with the image path
        $query = "INSERT INTO items (name, description, available, image) VALUES ('$name', '$description', $available, '$image')";
        if (mysqli_query($conn, $query)) {
            $success_message = "Item added successfully!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    } elseif (empty($error_message)) {
        $error_message = "Please fill out all fields correctly!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a class="logout" href="../../auth/logout.php">Logout</a>
    </nav>
    <div class="hero">
        <h1>Welcome to the Admin Dashboard</h1>
        <p>Do your stuff, Administrator. Have fun.</p>
    </div>
    <!-- Admin functionalities like Add/Delete items can be added here -->
    <div class="container">
        <h1>Upload New Item</h1>

        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data"> <!-- enctype added for file upload -->
            <label for="name">Item Name:</label><br>
            <input type="text" name="name" required><br><br>

            <label for="description">Description:</label><br>
            <textarea name="description" required></textarea><br><br>

            <label for="available">Available Quantity:</label><br>
            <input type="number" name="available" min="0" required><br><br>

            <label for="image">Upload Image:</label><br>
            <input type="file" name="image" required><br><br>

            <input type="submit" name="submit" value="Add Item">
        </form>
    </div>
</body>
</html>
