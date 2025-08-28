<?php
// Errors ko display karne ke liye:
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

// Check karein form submit hua ya nahi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging: $_POST array ko print karein
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Data get karein
    $name = $_POST['name'];
    $blood_group = $_POST['blood_group'];

    // Ensure karein ki values empty na ho
    if (!empty($name) && !empty($blood_group)) {
        $sql = "INSERT INTO blood_requests (name, blood_group, status) VALUES ('$name', '$blood_group', 'Pending')";
        if ($conn->query($sql) === TRUE) {
            echo "Request submitted successfully!";
            header("Location: request_form.php?success=1");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Missing values in form!";
    }
}
?>
