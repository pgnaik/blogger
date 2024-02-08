$(document).ready(function () {
    $("#loginForm").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "http://localhost/authentication/process-login1.php",
            data: $(this).serialize(),
            success: function (response) {
                if (response === "success") {
                    $("#message").html('<div class="alert alert-success">Login successful!</div>');
                    // Redirect to index.php after a short delay (e.g., 1 second)
                    setTimeout(function () {
                        window.location.href = "index.php";
                    }, 1000);
                } else {
                    $("#message").html('<div class="alert alert-danger">Invalid username or password</div>');
                }
            }
        });
    });
});
