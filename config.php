<?php
$host = 'localhost';      // XAMPP ke liye hamesha localhost
$user = 'root';           // default username
$pass = '';               // default password blank hota hai
$db   = 'blood_bank';     // apna DB ka naam (jo tum phpMyAdmin me create/import karoge)

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
} else {
    // echo "Database Connected Successfully"; // test ke liye
}
?>
