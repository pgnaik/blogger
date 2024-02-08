<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Redirect user to login page
    header("Location: login.php");
    exit;
}

// Include database connection
include_once "includes/db.php";

// Check if blog ID is provided in URL parameters
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Prepare a delete statement
    $sql = "DELETE FROM Blogs WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Blog deleted successfully
            $_SESSION["message"] = "Blog deleted successfully.";
            $_SESSION["alert_type"] = "success";
        } else {
            // Error while deleting blog
            $_SESSION["message"] = "Error deleting the blog. Please try again later.";
            $_SESSION["alert_type"] = "danger";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
} else {
    // Blog ID not provided
    $_SESSION["message"] = "Invalid blog ID.";
    $_SESSION["alert_type"] = "danger";
}

// Redirect back to the index page
header("Location: index.php");
exit;
?>
