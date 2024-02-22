<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db_connection.php';

$title = $description = $release_date = $image = "";
$title_err = $description_err = $release_date_err = $image_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter movie title.";
    } else {
        $title = trim($_POST["title"]);
    }

    if (empty(trim($_POST["description"]))) {
        $description_err = "Please enter movie description.";
    } else {
        $description = trim($_POST["description"]);
    }

    if (empty(trim($_POST["release_date"]))) {
        $release_date_err = "Please enter release date.";
    } else {
        $release_date = trim($_POST["release_date"]);
    }

    if ($_FILES["image"]["error"] == 0) {
        $image_info = getimagesize($_FILES["image"]["tmp_name"]);
        if ($image_info !== false) {
            if ($_FILES["image"]["size"] > 500000) { 
                $image_err = "Sorry, your file is too large.";
            } else {
                $image = "uploads/" . uniqid() . "_" . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $image);
            }
        } else {
            $image_err = "File is not an image.";
        }
    }

    if (empty($title_err) && empty($description_err) && empty($release_date_err) && empty($image_err)) {
        $sql = "INSERT INTO movies (title, description, release_date, image) VALUES (?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $param_title, $param_description, $param_release_date, $param_image);

            $param_title = $title;
            $param_description = $description;
            $param_release_date = $release_date;
            $param_image = $image;

            if ($stmt->execute()) {
                header("Location: movies.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Movie</title>
    <link rel="stylesheet" href="../css/add_movie.css">
</head>
<body>
        <div class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="book_ticket.php">Book Ticket</a></li>
                <li><a href="add_movie.php">Add Movies</a></li>
                <li><a href="add_show_timing.php">Show Timings</a></li>
                <li><a href="booking_history.php">Bookings</a></li>
                <li><a href="../backend/logout.php">Logout</a></li>
            </ul>
        </div>
    <div class="container">

        <h1>Add Movie</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                <label>Title:</label>
                <input type="text" name="title" value="<?php echo $title; ?>">
                <span class="error"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>">
                <label>Description:</label>
                <textarea name="description"><?php echo $description; ?></textarea>
                <span class="error"><?php echo $description_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($release_date_err)) ? 'has-error' : ''; ?>">
                <label>Release Date:</label>
                <input type="date" name="release_date" value="<?php echo $release_date; ?>">
                <span class="error"><?php echo $release_date_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($image_err)) ? 'has-error' : ''; ?>">
                <label>Poster:</label>
                <input type="file" name="image">
                <span class="error"><?php echo $image_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Add">
                <input type="reset" value="Reset">
            </div>
        </form>
    </div>
</body>
</html>
