<?php
// Include database connection
include_once "includes/db.php";

// Check if the blog id is provided in the GET request
if (isset($_GET['id'])) {
    $blogId = $_GET['id'];

    // Fetch blog data from the database based on the provided id
    $sql = "SELECT * FROM Blogs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $blogId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the blog exists
    if ($result->num_rows > 0) {
        // Fetch the blog data
        $blogData = $result->fetch_assoc();

        // Return the blog data as JSON
        echo json_encode($blogData);
    } else {
        // Blog not found
        echo json_encode(["error" => "Blog not found"]);
    }
} else {
    // No blog id provided
    echo json_encode(["error" => "No blog id provided"]);
}

// Close database connection
$conn->close();
?>
