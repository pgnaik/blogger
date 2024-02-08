<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

include_once "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $content = $_POST["content"];

    $sql = "UPDATE Blogs SET content = ? WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "si", $content, $id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION["message"] = "Blog updated successfully.";
            $_SESSION["alert_type"] = "success";
        } else {
            $_SESSION["message"] = "Error updating the blog. Please try again later.";
            $_SESSION["alert_type"] = "danger";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}

header("Location: index.php");
exit;
?>
