<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_id = $_POST["donor_id"];
    $blood_group = $_POST["blood_group"];
    $quantity = $_POST["quantity"];
    $date_donated = date("Y-m-d");

    $sql = "INSERT INTO donation_history (donor_id, blood_group, quantity, date_donated) VALUES ('$donor_id', '$blood_group', '$quantity', '$date_donated')";

    if ($conn->query($sql) === TRUE) {
        echo "Donation history updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
