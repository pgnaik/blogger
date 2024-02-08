<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Registration Page</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .registration-container {
            max-width: 500px;
            margin: 50px auto;
        }

        .registration-form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .registration-heading {
            text-align: center;
            margin-bottom: 30px;
            color: #28a745; /* Green color */
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }
        
        .registration-heading {
            text-align: center;
            margin-bottom: 30px;
            color: #28a745; /* Green color */
            background-color: #e8f4e8; /* Light green background */
            padding: 10px 20px; /* Adjust padding as needed */
            border-radius: 10px; /* Rounded corners */
        }

        /* Password strength indicator styles */
        .password-strength {
            margin-top: 10px;
        }

        .progress {
            height: 10px;
            margin-bottom: 0;
        }

        .progress-bar {
            transition: width 0.3s ease-in-out;
        }
    </style>
</head>

<body>

    <div class="container registration-container">
        <div class="registration-form">
            <h2 class="registration-heading">Register for an Account</h2>
            <form id="registrationForm" action="process-registration.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-city"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="state">State:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-map"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="state" name="state" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="pincode">Pincode:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="pincode" name="pincode" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Username:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <input type="password" class="form-control" id="password" name="password" required><br>
                    </div>
                </div>

                <!-- Password strength indicator -->
                <div class="password-strength" style="display: none;">
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 30%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div><br>

                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
            <p class="footer-text">Already have an account? <a href="#">Login here</a></p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        $('#password').on('input', function() {
            var password = $(this).val();
            var strength = 0;

            // Check for length
            if (password.length >= 8) {
                strength += 25;
            }

            // Check for numbers
            if (password.match(/\d+/)) {
                strength += 25;
            }

            // Check for uppercase letters
            if (password.match(/[A-Z]+/)) {
                strength += 25;
            }

            // Check for special characters
            if (password.match(/[!@#$%^&*(),.?":{}|<>]/)) {
                strength += 25;
            }

            // Update progress bar width and color
            $('.progress-bar').css('width', strength + '%');
            if (strength <= 25) {
                $('.progress-bar').removeClass('bg-warning bg-success').addClass('bg-danger');
            } else if (strength <= 50) {
                $('.progress-bar').removeClass('bg-danger bg-success').addClass('bg-warning');
            } else {
                $('.progress-bar').removeClass('bg-danger bg-warning').addClass('bg-success');
            }
            // Show password strength indicator
        $('.password-strength').show();
        });
        
    </script>

</body>

</html>
