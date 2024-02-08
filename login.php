<?php
// Check if the user is already logged in, if yes, redirect to dashboard or home page
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard.php"); // Redirect to dashboard.php or home page
    exit;
}

// Include necessary files
require_once "includes/db.php"; // Assuming you have a separate file for database connection

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirect user to dashboard.php or home page
                            header("location: dashboard.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "Invalid password.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "Username not found.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        .jumbotron-heading {
            font-family: 'Arial', sans-serif;
            font-size: 24px;
            font-weight: bold;
        }

        .custom-jumbotron {
            padding: 20px; /* Adjust padding as needed */
            height: 200px; /* Adjust the height */
        }
    </style>
    <title>Login System</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="jumbotron bg-primary text-white text-center custom-jumbotron">
                            <img src="assets/images/siber.jpg" alt="Authentication Image" class="img-fluid rounded-circle mb-3" style="max-width: 100px; height: auto;">
                            <h1 class="display-4 jumbotron-heading">Authentication Form</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="loginForm" action="process-login.php" method="POST">
                            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
                                <span class="help-block"><?php echo $username_err; ?></span>
                            </div>
                            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <span class="help-block"><?php echo $password_err; ?></span>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
