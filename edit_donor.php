<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if donor ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Donor ID");
}
$donor_id = intval($_GET['id']);

// Fetch donor details
$stmt = $conn->prepare("SELECT name, blood_group, quantity FROM donors WHERE id = ?");
$stmt->bind_param("i", $donor_id);
$stmt->execute();
$result = $stmt->get_result();

$donor = $result->fetch_assoc();
if (!$donor) {
    die("Donor not found");
}

// Update donor details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $blood_group = htmlspecialchars($_POST['blood_group']);
    $quantity = intval($_POST['quantity']); // Ensure it's an integer

    $update_stmt = $conn->prepare("UPDATE donors SET name = ?, blood_group = ?, quantity = ? WHERE id = ?");
    $update_stmt->bind_param("ssii", $name, $blood_group, $quantity, $donor_id);
    
    if ($update_stmt->execute()) {
        echo "<script>alert('Donor Updated Successfully!'); window.location.href='manage_donors.php';</script>";
    } else {
        echo "Error updating donor: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Donor</title>
    <style>
        body {
           
           background-size: cover;
           text-align: center;
           font-family: 'Poppins', sans-serif;
           background: #1A1A2E;
           color: #FFF;
       }
        .form-container {
            background:rgba(21, 116, 131, 0.48);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: auto;
            margin-top: 50px;
        }
        select, input, button {
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
    <h2>Edit Donor</h2>

    <!-- ✅ Back to Home Button -->
    <a href="http://localhost/blood_bank/manage_donors.php" class="back-btn">🏠 Back</a>

    <form method="POST">
        <input type="text" name="name" value="<?php echo $donor['name']; ?>" required placeholder="Enter Donor Name">
        
        <select name="blood_group" required>
            <option value="A+" <?php if($donor['blood_group'] == 'A+') echo 'selected'; ?>>A+</option>
            <option value="A-" <?php if($donor['blood_group'] == 'A-') echo 'selected'; ?>>A-</option>
            <option value="B+" <?php if($donor['blood_group'] == 'B+') echo 'selected'; ?>>B+</option>
            <option value="B-" <?php if($donor['blood_group'] == 'B-') echo 'selected'; ?>>B-</option>
            <option value="O+" <?php if($donor['blood_group'] == 'O+') echo 'selected'; ?>>O+</option>
            <option value="O-" <?php if($donor['blood_group'] == 'O-') echo 'selected'; ?>>O-</option>
            <option value="AB+" <?php if($donor['blood_group'] == 'AB+') echo 'selected'; ?>>AB+</option>
            <option value="AB-" <?php if($donor['blood_group'] == 'AB-') echo 'selected'; ?>>AB-</option>
        </select>

        <input type="number" name="quantity" value="<?php echo $donor['quantity']; ?>" placeholder="Enter Quantity (unit)" min="1" max="3" required>

        <button type="submit">Update Donor</button>
    </form>
</div>

</body>
</html>
