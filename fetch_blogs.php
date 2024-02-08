<?php
// Include database connection
include_once "includes/db.php";

// Fetch blogs from the database
$sql = "SELECT Blogs.*, Users.username AS author FROM Blogs JOIN Users ON Blogs.author_id = Users.id";

// Check if the author value is set and not empty
if(isset($_POST['author']) && !empty($_POST['author'])) {
    // Sanitize the input to prevent SQL injection
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    
    // Append a WHERE clause to the SQL query to filter by the selected author
    $sql .= " WHERE Users.username = '$author'";
}

// Execute the SQL query
$result = mysqli_query($conn, $sql);

// Check if there are any blogs
if(mysqli_num_rows($result) > 0) {
    // Loop through each blog and display them
    while($row = mysqli_fetch_assoc($result)) {
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
                </h5>
            </div>
            <div id="collapse<?php echo $row['id']; ?>" class="collapse" aria-labelledby="heading<?php echo $row['id']; ?>" data-parent="#accordion">
                <div class="card-body">
                    <p><?php echo $row['content']; ?></p>
                    <div>
                        <span><b>Recommendations: </b></span>
                        <span class="badge badge-primary"><?php echo $row['recommendations']; ?></span>
                        <span>&nbsp;&nbsp;</span>
                        <span><b>Likes: </b></span>
                        <span class="badge badge-secondary"><?php echo $row['likes']; ?></span>
                    </div><br>
                    <p><b>Date Created: </b><?php echo $row['created_at']; ?></p>
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
