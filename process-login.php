<!-- process-login.php -->
<?php
session_start();
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['id'];
        $_SESSION["username"] = $username;
        $_SESSION["message"] = "Authentication successful";
        $_SESSION["alert_type"] = "success";
        $_SESSION["loggedin"] = true;
        $_SESSION["role"] = "user";
        $_SESSION["id"] = $user_id;
        //$_SESSION["role"] = $row['`role`'];
        header("Location: index.php");
        exit();
    } else {
        $_SESSION["message"] = "Authentication Failure. Access Denied.";
        $_SESSION["alert_type"] = "danger";
        $_SESSION["loggedin"] = false;
        header("Location: index.php");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
