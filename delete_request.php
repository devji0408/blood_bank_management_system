<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM blood_requests WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: manage_requests.php");
        exit();
    } else {
        echo "Error deleting request: " . $conn->error;
    }
}
?>