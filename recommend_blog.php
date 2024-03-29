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

// Prepare and execute the SQL query to increment the recommendations for the specified blog
$sql = "UPDATE Blogs SET recommendations = recommendations + 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blogId);
$stmt->execute();

// Check if the update was successful
if ($stmt->affected_rows > 0) {
    // Fetch and return the updated recommendation count
    $sql = "SELECT recommendations FROM Blogs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $blogId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo $row['recommendations'];
} else {
    // Update failed, handle accordingly (redirect, show error message, etc.)
    echo "Error: Failed to recommend the blog";
}

// Close database connection
$conn->close();
?>
