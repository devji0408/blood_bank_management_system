<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "blood_bank";

// Database connection banayein
$conn = new mysqli($servername, $username, $password, $database);

// Connection check karein
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<pre>";
    print_r($_POST); // Yeh check karega ki form se data aa raha hai ya nahi
    echo "</pre>";
}

?>
