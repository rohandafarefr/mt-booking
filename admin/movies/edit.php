<?php
// Include database configuration file
require_once '../../includes/db_connection.php';

// Check if movie ID is provided
if(isset($_GET['movie_id'])) {
    $movie_id = $_GET['movie_id'];

    // Retrieve movie details based on movie ID
    $sql = "SELECT * FROM movies WHERE movie_id = $movie_id";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $description = $row['description'];
        $release_date = $row['release_date'];
        $image_url = $row['image'];
        $vip_price = $row['VIP'];
        $premium_price = $row['PREMIUM'];
        $gold_price = $row['GOLD'];
        // Add more fields as needed
    } else {
        // Movie not found
        echo "Movie not found.";
        exit();
    }
} else {
    // Movie ID not provided
    echo "Movie ID not provided.";
    exit();
}

// Check if form is submitted for updating movie details
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if delete button is clicked
    if(isset($_POST['delete'])) {
        // Delete related records from the tickets table first
        $delete_tickets_sql = "DELETE FROM tickets WHERE timing_id IN (SELECT timing_id FROM show_timings WHERE movie_id = $movie_id)";
        if($conn->query($delete_tickets_sql) === TRUE) {
            // Now delete the movie from the movies table
            $delete_movie_sql = "DELETE FROM movies WHERE movie_id = $movie_id";
            if($conn->query($delete_movie_sql) === TRUE) {
                // Redirect to movies list page
                header("Location: index.php");
                exit();
            } else {
                echo "Error deleting movie: " . $conn->error;
            }
        } else {
            echo "Error deleting tickets: " . $conn->error;
        }
    } else {
        // Retrieve form data
        $new_title = $_POST['title'];
        $new_description = $_POST['description'];
        $new_release_date = $_POST['release_date'];
        $new_vip_price = $_POST['vip_price'];
        $new_premium_price = $_POST['premium_price'];
        $new_gold_price = $_POST['gold_price'];
        // Add more fields as needed

        // Check if a new image file is uploaded
        if($_FILES['image']['error'] == UPLOAD_ERR_OK) {
            // Get file details
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_size = $_FILES['image']['size'];

            // Move uploaded file to destination directory
            $upload_dir = "../../uploads/";
            $new_image_url = $upload_dir . $file_name;
            if(move_uploaded_file($file_tmp, $new_image_url)) {
                // Update movie details in the database with new image URL and pricing information
                $update_sql = "UPDATE movies SET title = '$new_title', description = '$new_description', release_date = '$new_release_date', image = '$new_image_url', VIP = '$new_vip_price', PREMIUM = '$new_premium_price', GOLD = '$new_gold_price' WHERE movie_id = $movie_id";
                if($conn->query($update_sql) === TRUE) {
                    echo "Movie details updated successfully.";
                    $image_url = $new_image_url; // Update image URL to display new image
                } else {
                    echo "Error updating movie details: " . $conn->error;
                }
            } else {
                echo "Error uploading image file.";
            }
        } else {
            // Update movie details in the database without changing the image URL and pricing information
            $update_sql = "UPDATE movies SET title = '$new_title', description = '$new_description', release_date = '$new_release_date', VIP = '$new_vip_price', PREMIUM = '$new_premium_price', GOLD = '$new_gold_price' WHERE movie_id = $movie_id";
            if($conn->query($update_sql) === TRUE) {
                echo "Movie details updated successfully.";
            } else {
                echo "Error updating movie details: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Movie</title>
    <link rel="stylesheet" href="../../css/edit-movie.css">
</head>
<body>
    <div class="container">
        <h2>Edit Movie</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $title; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required><?php echo $description; ?></textarea>
            </div>
            <div class="form-group">
                <label for="release_date">Release Date:</label>
                <input type="date" id="release_date" name="release_date" value="<?php echo $release_date; ?>" required>
            </div>
            <div class="form-group">
                <label for="vip_price">VIP Price:</label>
                <input type="number" id="vip_price" name="vip_price" value="<?php echo $vip_price; ?>" required>
            </div>
            <div class="form-group">
                <label for="premium_price">Premium Price:</label>
                <input type="number" id="premium_price" name="premium_price" value="<?php echo $premium_price; ?>" required>
            </div>
            <div class="form-group">
                <label for="gold_price">Gold Price:</label>
                <input type="number" id="gold_price" name="gold_price" value="<?php echo $gold_price; ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Upload New Image:</label>
                <input type="file" id="image" name="image">
            </div>
            <img src="<?php echo $image_url; ?>" alt="<?php echo $title; ?>" width="200">
            <!-- Add more fields as needed -->
            <button type="submit">Update</button>
            <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</button>
        </form>
    </div>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
