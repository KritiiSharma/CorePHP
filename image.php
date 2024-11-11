<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kriti";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Folder where images will be saved
    $targetDir = "uploads/";
    
    // Get the file information
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Check if file is an image
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileType, $allowedTypes)) {
        // Upload the file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            // Insert file name into database
            $sql = "INSERT INTO images (file_name, uploaded_on) VALUES ('$fileName', NOW())";
            if ($conn->query($sql) === TRUE) {
                echo "Image uploaded and data saved successfully.";
            } else {
                echo "Database insertion error: " . $conn->error;
            }
        } else {
            echo "There was an error uploading your file.";
        }
    } else {
        echo "Only JPG, JPEG, PNG, & GIF files are allowed.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
        <label for="image">Select Image:</label>
        <input type="file" name="image" id="image" required>
        <button type="submit" name="submit">Upload</button>
    </form>
</body>
</html>
