<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'] ?? null;
$name = "";
$blood_group = "";

if ($user_id) {
    $stmt = $conn->prepare("SELECT name, blood_group FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $name = $user['name'];
        $blood_group = $user['blood_group'];
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quantity = isset($_POST['quantity']) ? (int) trim($_POST['quantity']) : 0;
    $requests_date = $_POST['date_added'];

    // Validate quantity
    if ($quantity <= 0) {
        echo "<script>alert('Invalid quantity!'); window.history.back();</script>";
        exit();
    }

    // ✅ Update `date_added` to CURRENT_TIMESTAMP when updating quantity
    $insertQuery = "INSERT INTO blood_requests (id, name, blood_group, quantity, date_added) 
                    VALUES (?, ?, ?, ?, NOW()) 
                    ON DUPLICATE KEY UPDATE 
                        quantity = quantity + VALUES(quantity), 
                        date_added = NOW()";

    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("issi", $user_id, $name, $blood_group, $quantity);

    if ($stmt->execute()) {
        echo "<script>alert('Request added or updated successfully!'); window.location.href='user_dashboard.php';</script>";
    } else {
        die("Error: " . $stmt->error);
    }
    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Request</title>
    <style>
        body {
            background-size: cover;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            background: #1A1A2E;
            color: #FFF;
        }
        .form-container {
            background: rgba(21, 116, 131, 0.48);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: auto;
            margin-top: 50px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #ff4d4d;
            color: white;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            background: #cc0000;
        }
        .back-btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Add Request</h2>

        <a href="http://localhost/blood_bank/user_dashboard.php" class="back-btn">🏠 Back to Home</a>

        <form method="POST">
            <input type="text" name="name" value="<?= htmlspecialchars($name); ?>" readonly required>
            <input type="text" name="blood_group" value="<?= htmlspecialchars($blood_group); ?>" readonly required>
            <input type="number" name="quantity" placeholder="Enter Quantity (unit)" min="1" max="3" required>

           
            <!-- ✅ Date (Only visible but not editable) -->
        <input type="text" value="<?= date('Y-m-d H:i:s'); ?>" readonly>
        
            <button type="submit">Add Request</button>
        </form>
    </div>

</body>
</html>
