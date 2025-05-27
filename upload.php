<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["uploaded_file"]["name"]);
$file_name = basename($_FILES["uploaded_file"]["name"]);

// DB connection (change with your DB credentials)
$servername = "localhost";
$username = "root";      // your MySQL username
$password = "dbadmin";          // your MySQL password
$dbname = "storage"; // your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Upload file
if (move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $target_file)) {
    echo "The file " . htmlspecialchars($file_name) . " has been uploaded.";

    // Insert file info into DB
    $stmt = $conn->prepare("INSERT INTO uploaded_files (file_name, file_path) VALUES (?, ?)");
    $stmt->bind_param("ss", $file_name, $target_file);

    if ($stmt->execute()) {
        echo "<br>File info saved to database.";
    } else {
        echo "<br>Error saving to database: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Sorry, there was an error uploading your file.";
}

$conn->close();
?>



<!-- 

$_FILES["uploaded_file"]["tmp_name"]: temporary file location.

move_uploaded_file(): moves the file to the uploads/ folder.

htmlspecialchars(): sanitizes the filename for output. 

-->