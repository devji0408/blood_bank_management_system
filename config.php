<?php
$host = 'dash.infinityfree.com'; // InfinityFree का DB host (phpMyAdmin में दिखता है)
$user = 'mybloodbank';        // DB username
$pass = 'GnDukQB1w0azvPf';     // DB password
$db   = 'if0_39308890_blood_bank';  // DB name

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
