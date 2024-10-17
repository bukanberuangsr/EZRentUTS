<?php
session_start();
include '../../db/db.php';
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../../index.php');
    exit();
}

$error_message = '';
$success_message = '';

// Check if an item ID is provided
if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']);

    // Fetch the current item details
    $item_query = "SELECT * FROM items WHERE id = $item_id";
    $item_result = mysqli_query($conn, $item_query);

    if (mysqli_num_rows($item_result) > 0) {
        $item = mysqli_fetch_assoc($item_result);
    } else {
        $error_message = "Item not found!";
    }
} else {
    header('Location: ../../index.php'); // Redirect if no valid ID is passed
    exit();
}

// Update item logic
if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $available = intval($_POST['available']);

    // Handle file upload if a new image is uploaded
    if ($_FILES['image']['error'] == 0) {
        $target_dir = "../../uploads/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $target_file;
            } else {
                $error_message = "Sorry, there was an error uploading the image.";
            }
        } else {
            $error_message = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        // Use the old image if no new image is uploaded
        $image = $item['image'];
    }

    // Update the item if no errors occurred
    if (empty($error_message) && !empty($name) && !empty($description) && $available >= 0) {
        $update_query = "UPDATE items SET name = '$name', description = '$description', available = $available, image = '$image' WHERE id = $item_id";
        if (mysqli_query($conn, $update_query)) {
            $success_message = "Item updated successfully!";
            // Refresh item details after update
            $item_query = "SELECT * FROM items WHERE id = $item_id";
            $item_result = mysqli_query($conn, $item_query);
            $item = mysqli_fetch_assoc($item_result);
        } else {
            $error_message = "Error updating item: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Please fill out all fields correctly!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav>
        <a href="../../index.php">Home</a>
        <a class="logout" href="../../auth/logout.php">Logout</a>
    </nav>
    <div class="hero-sm">
        <h1>Edit Item</h1>
    </div>

    <div class="container">
        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="name">Item Name:</label><br>
            <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required><br><br>

            <label for="description">Description:</label><br>
            <textarea name="description" required><?php echo htmlspecialchars($item['description']); ?></textarea><br><br>

            <label for="available">Available Quantity:</label><br>
            <input type="number" name="available" value="<?php echo $item['available']; ?>" min="0" required><br><br>

            <label for="image">Upload New Image (optional):</label><br>
            <input type="file" name="image"><br><br>
            <p>Current Image: <br><img src="<?php echo $item['image']; ?>" alt="Current Image" style="width: 100px;"></p>

            <input type="submit" name="update" value="Update Item">
        </form>
        <a href="dashboard.php">Back to dashboard</a>
    </div>
</body>
</html>
