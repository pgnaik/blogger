<?php
session_start();



// Check if the blog ID is provided
if (!isset($_POST['id'])) {
    // Blog ID is not provided, handle accordingly (redirect, show error message, etc.)
    echo "Error: Blog ID not provided";
    exit;
}

// Include database connection
include_once "includes/db.php";

// Sanitize the input
$blogId = $_POST['id'];

// Prepare and execute the SQL query to increment the likes for the specified blog
$sql = "UPDATE Blogs SET likes = likes + 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blogId);
$stmt->execute();

// Check if the update was successful
if ($stmt->affected_rows > 0) {
    // Fetch and return the updated like count
    $sql = "SELECT likes FROM Blogs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $blogId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo $row['likes'];
} else {
    // Update failed, handle accordingly (redirect, show error message, etc.)
    echo "Error: Failed to like the blog";
}

// Close database connection
$conn->close();
?>
