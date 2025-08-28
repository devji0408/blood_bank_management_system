<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

if (isset($_FILES["profile_img"])) {
    $user_id = $_SESSION['user_id'];
    $target_dir = "images/";
    $file_name = basename($_FILES["profile_img"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // ✅ Check if the file is an image
    if ($_FILES["profile_img"]["tmp_name"] != "") {
        $check = getimagesize($_FILES["profile_img"]["tmp_name"]);
        if ($check === false) {
            echo json_encode(["status" => "error", "message" => "File is not an image."]);
            $uploadOk = 0;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No file selected."]);
        $uploadOk = 0;
    }

    // ✅ Check file size (max 5MB)
    if ($_FILES["profile_img"]["size"] > 5000000) {
        echo json_encode(["status" => "error", "message" => "File is too large (max 5MB allowed)."]);
        $uploadOk = 0;
    }

    // ✅ Allow only certain formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo json_encode(["status" => "error", "message" => "Only JPG, JPEG & PNG files are allowed."]);
        $uploadOk = 0;
    }

    // ✅ Move uploaded file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
            // ✅ Update in database
            $query = $conn->prepare("UPDATE users SET profile_img = ? WHERE id = ?");
            $query->bind_param("si", $target_file, $user_id);

            if ($query->execute()) {
                echo json_encode(["status" => "success", "file_path" => $target_file]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to update profile in database."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to upload file."]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
