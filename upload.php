<?php

$target_dir = "uploads/";

$target_file = $target_dir . basename($_FILES["uploaded_file"]["name"]);

if (move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars(basename($_FILES["uploaded_file"]["name"])) . " has been uploaded.";
} else {
    echo "Sorry, there was an error uploading your file.";
}
?>


<!-- 

$_FILES["uploaded_file"]["tmp_name"]: temporary file location.

move_uploaded_file(): moves the file to the uploads/ folder.

htmlspecialchars(): sanitizes the filename for output. 

-->