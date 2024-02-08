$(document).ready(function () {
    $("#loginForm").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "http://localhost/authentication/process-login1.php",
            data: $(this).serialize(),
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            success: function (response) {

                if (response === "successful") {
                    console.log("ok");
                    $("#message").html('<div class="alert alert-success">Login successful!</div>');
                } else {
                    console.log("not ok");
                    $("#message").html('<div class="alert alert-danger">Invalid username or password</div>');
                }
            }
        });
    });
});
