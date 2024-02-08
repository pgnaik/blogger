<?php
// Start the session
session_start();

// Check for session messages and display Bootstrap alert
if (isset($_SESSION["message"])) {
    $alert_type = isset($_SESSION["alert_type"]) ? $_SESSION["alert_type"] : "info";
    echo '<div class="alert alert-' . $alert_type . ' alert-dismissible fade show" role="alert">
            ' . $_SESSION["message"] . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>';

    // Clear session messages
    unset($_SESSION["message"]);
    unset($_SESSION["alert_type"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-body p {
            text-align: justify;
            /* Justify content */
        }

        .badge {
            border-radius: 50%;
            /* Make badges circular */
            width: 40px;
            /* Set width */
            height: 40px;
            /* Set height */
            line-height: 30px;
            /* Center content vertically */
            text-align: center;
            /* Center content horizontally */
            font-size: 16px;
        }

        .jumbotron {
            background-color: transparent;
            /* Transparent background */
            border: 2px solid #007bff;
            /* Blue border */
            color: #3cba92;
            /* Green text color */
            border-radius: 10px;
            /* Rounded corners */
            padding: 5px;
            /* Add padding */
            width: 30%;
            /* Decrease width */
            margin: 0 auto;
            /* Center align */
            text-align: center;
            /* Center align text */
            line-height: 2;
            /* Thick line */
            font-weight: bold;
        }

        .author-badge {
            background-color: transparent;
            /* Transparent background */
            border: 2px solid maroon;
            /* Maroon border */
            border-radius: 10px;
            padding: 5px 10px;
            display: inline-block;
            /* Make it inline-block to align with the text */
        }

        .author-name {
            color: maroon;
            /* Maroon text color */
            display: inline;
            /* Make it inline to allow text alignment */
        }

        /* Custom styles for the dropdown */
        .custom-dropdown {
            width: 150px;
            /* Adjust width as needed */
        }

        /* Style for the update form */
        .update-form {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <?php include_once "includes/db.php"; ?>
    <!-- Navbar with options for Register, Login, and Logout -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">CSIBER Blogger</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <?php
                // Check if user is logged in, if yes, show Logout button, else show Login button
                if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                    echo '<a class="btn btn-outline-primary my-2 my-sm-0 mr-2" href="logout.php">Logout</a>';

                    // Check if user role is 'user' to show blog-related buttons
                    if ($_SESSION["role"] === "user") {
                        echo '<a class="btn btn-outline-primary my-2 my-sm-0 mr-2" href="insert_blog.php">Insert Blog</a>';
                    }
                } else {
                    echo '<a class="btn btn-outline-primary my-2 my-sm-0 mr-2" href="register.php">Register</a>
                          <a class="btn btn-outline-primary my-2 my-sm-0" href="login.php">Login</a>';
                }
                ?>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">BlogVibe</h1>
        </div><br>
        <!-- Dropdown list to select authors -->
        <div class="form-group">

            <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) { ?>
                <label for="authorSelect">Select Author:</label>
                <select class="custom-select custom-dropdown" id="authorSelect">
                    <option value="">All Authors</option>
                    <?php
                    // Include database connection
                    // Assuming you have a separate file for database connection

                    // Fetch authors from the database
                    $sql = "SELECT DISTINCT username FROM Users";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['username'] . "'>" . $row['username'] . "</option>";
                    }
                    ?>
                </select>
            <?php } ?>
        </div>

        <!-- Parent container with the ID "accordion" for blogs -->
        <div id="accordion">
            <?php
            // Fetch blogs from the database
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                // If user is logged in, fetch only their own blogs
                $sql = "SELECT Blogs.*, Users.username AS author FROM Blogs JOIN Users ON Blogs.author_id = Users.id WHERE Users.username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $_SESSION['username']);
            } else {
                // If user is not logged in, fetch all blogs
                $sql = "SELECT Blogs.*, Users.username AS author FROM Blogs JOIN Users ON Blogs.author_id = Users.id";
                $stmt = $conn->prepare($sql);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are any blogs
            
            if (mysqli_num_rows($result) > 0) {
                // Loop through each blog
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <!-- Collapsible panel for each blog -->
                    <div class="card">
                        <div class="card-header" id="heading<?php echo $row['id']; ?>">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $row['id']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $row['id']; ?>">
                                    <?php echo $row['title']; ?>
                                </button>
                                <span class="author-badge">
                                    <span class="author-name" style="color: maroon; text-align: right;">Posted By <?php echo $row['author']; ?></span>
                                </span>

                                <div><br>
    <button class="btn btn-outline-primary recommend-btn" data-blog-id="<?php echo $row['id']; ?>">
        <i class="fas fa-thumbs-up"></i> Recommend
    </button>
    <button class="btn btn-outline-secondary like-btn" data-blog-id="<?php echo $row['id']; ?>">
        <i class="fas fa-heart"></i> Like
    </button>
</div><br>



                                <?php
                                // Check if user role is 'user' to show update and delete buttons
                                if (isset($_SESSION["role"]) && $_SESSION["role"] === "user") {
                                    echo '<a href="#" class="btn btn-outline-primary ml-2 update-btn" data-blog-id="' . $row['id'] . '"><i class="fas fa-edit"></i></a>';
                                    echo '<a href="delete_blog.php?id=' . $row['id'] . '" class="btn btn-outline-danger ml-2"><i class="fas fa-trash-alt"></i></a>';
                                }
                                ?>
                            </h5>
                        </div>
                        <div id="collapse<?php echo $row['id']; ?>" class="collapse" aria-labelledby="heading<?php echo $row['id']; ?>" data-parent="#accordion">
    <div class="card-body">
        <!-- Static text -->
        <div class="static-content" data-blog-id="<?php echo $row['id']; ?>">
            <p><?php echo $row['content']; ?></p>
            <div>
    <span><b>Recommendations: </b></span>
    <span id="recommendCount_<?php echo $row['id']; ?>" class="badge badge-primary"><?php echo $row['recommendations']; ?></span>
    <span>&nbsp;&nbsp;</span> <!-- Add space between badges -->
    <span><b>Likes: </b></span>
    <span id="likeCount_<?php echo $row['id']; ?>" class="badge badge-secondary"><?php echo $row['likes']; ?></span>
</div>
            <br>
            <p><b>Date Created: </b><?php echo $row['created_at']; ?></p> <!-- Display date created -->
        </div>
        
        <!-- Update form -->
        <form action="update_blog.php" method="post" class="update-form" data-blog-id="<?php echo $row['id']; ?>" style="display: none;">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="form-group">
                <label for="updateTitle">Title</label>
                <input type="text" name="title" id="updateTitle" class="form-control" value="<?php echo $row['title']; ?>">
            </div>
            <div class="form-group">
                <label for="updateContent">Content</label>
                <textarea name="content" id="updateContent" class="form-control" rows="5"><?php echo $row['content']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-outline-success update-btn" data-blog-id="' . $row['id'] . '"><i class="fas fa-check-square"></i></button>
            <button type="button" class="btn btn-outline-secondary cancel-btn"><i class="fas fa-times"></i></button>

        </form>
    </div>
</div>

                    </div>
            <?php
                }
            } else {
                // No blogs found
                echo "<p>No blogs found.</p>";
            }

            // Close database connection
            mysqli_close($conn);
            ?>
        </div> <!-- End of parent container with the ID "accordion" -->
    </div>

    <!-- Include jQuery and Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script>
    $(document).ready(function() {
        // AJAX function inside change event handler
        $('#authorSelect').change(function() {
            var author = $(this).val();
            $.ajax({
                url: 'fetch_blogs.php', // URL for fetching blogs based on author
                type: 'POST',
                data: {
                    author: author
                },
                success: function(response) {
                    $('#accordion').html(response); // Insert fetched blogs into accordion
                }
            });
        });
      });

      $(document).ready(function() {
        // AJAX function inside change event handler
        $('#authorSelect').change(function() {
            var author = $(this).val();
            $.ajax({
                url: 'fetch_blogs.php', // URL for fetching blogs based on author
                type: 'POST',
                data: {
                    author: author
                },
                success: function(response) {
                    $('#accordion').html(response); // Insert fetched blogs into accordion
                }
            });
        });

        // Function to handle the click event on update buttons
        $('.update-btn').click(function(e) {
            e.preventDefault();
            var blogId = $(this).data('blog-id');
            $.ajax({
                url: 'get_blog.php?id=' + blogId, // URL to fetch blog data
                type: 'GET',
                success: function(response) {
                    var data = JSON.parse(response);
                    console.log(data);
                    $('#updateTitle').val(data.title);
                    $('#updateContent').val(data.content);
                }
            });
            $('.update-form[data-blog-id="' + blogId + '"]').toggle();
        });

        // Function to handle the form submission for updating blog content
        $('.update-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: 'update_blog.php', // URL to update the blog content
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Process the response as needed
                    console.log("r="+response);
                    // For example, you can display a success message or reload the page
                    location.reload();
                }
            });
        });

        // Function to handle the click event on cancel buttons
        $('.cancel-btn').click(function(e) {
            e.preventDefault();
            var blogId = $(this).closest('.update-form').data('blog-id');
            $('.update-form[data-blog-id="' + blogId + '"]').toggle();
        });
    });

    // JavaScript for recommending and liking
$(document).ready(function() {
    // Function to handle recommending a blog
    $('.recommend-btn').click(function() {
        var blogId = $(this).data('blog-id');
        $.ajax({
            url: 'recommend_blog.php', // URL to recommend the blog
            type: 'POST',
            data: { id: blogId },
            success: function(response) {
                
                // Update the GUI with the new recommendation count
                $('#recommendCount_' + blogId).text(response);
            }
        });
    });

    // Function to handle liking a blog
    $('.like-btn').click(function() {
        var blogId = $(this).data('blog-id');
        $.ajax({
            url: 'like_blog.php', // URL to like the blog
            type: 'POST',
            data: { id: blogId },
            success: function(response) {
                // Update the GUI with the new like count
                $('#likeCount_' + blogId).text(response);
            }
        });
    });
});


</script>



</body>

</html>