<?php
// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to index.php with a query parameter for the logout message
header("location: index.php?logout=true");
exit;
?>
