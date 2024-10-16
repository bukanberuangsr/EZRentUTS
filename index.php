<?php
session_start();
include './db/db.php';
if (!isset($_SESSION['username'])){
    header("location: ./auth/login.php");
    exit();
}
$query = "SELECT * FROM items WHERE available > 0";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
// Handle adding new item
if (isset($_POST['add_item'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $available = intval($_POST['available']);
    $addQuery = "INSERT INTO items (name, description, available) VALUES ('$name', '$description', $available)";
    if (mysqli_query($conn, $addQuery)) {
        echo "Item added successfully.";
    } else {
        echo "Error adding item: " . mysqli_error($conn);
    }
}

// Handle deleting an item
if (isset($_POST['delete_item'])) {
    $id = intval($_POST['id']);
    $deleteQuery = "DELETE FROM items WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        echo "Item deleted successfully.";
    } else {
        echo "Error deleting item: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a class="logout" href="./auth/logout.php">Logout</a>
    </nav>
    <div class="hero">
        <h1>Welcome to the EZRental!</h1>
        <p>What would you like to rent today?</p>
    </div>
    <div class="content">
        <ul>
            <?php while($row = mysqli_fetch_assoc($result)) : ?>
            <li>
                <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                <p>Available Quantity: <?php echo $row['available']; ?></p>
                <a href="./pages/item_detail.php?id=<?php echo $row['id']; ?>">View Details</a>
            </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>