<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch User Details for Autofill
$query = $conn->prepare("SELECT name, blood_group FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

$name = isset($user['name']) ? $user['name'] : 'Unknown User';
$blood_group = isset($user['blood_group']) ? $user['blood_group'] : 'Not Set';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quantity = (int) $_POST['quantity'];
    $donation_date = date('Y-m-d H:i:s'); // ✅ Set current date
    $hospital = $_POST['hospital']; // ✅ Get hospital name

    // ✅ Check if donor already exists in the "donors" table
    $checkQuery = "SELECT id, quantity FROM donors WHERE id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // ✅ Update quantity & hospital
        $row = $result->fetch_assoc();
        $newQuantity = $row['quantity'] + $quantity;

        $updateQuery = "UPDATE donors SET quantity = ?, hospital = ?, date_added = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("issi", $newQuantity, $hospital, $donation_date, $row['id']);
        $stmt->execute();

        echo "<script>alert('Donor quantity updated successfully!'); window.location.href='user_dashboard.php';</script>";
    } else {
        // ✅ Insert new donor into "donors" table
        $insertQuery = "INSERT INTO donors (id, name, blood_group, quantity, hospital, date_added) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ississ", $user_id, $name, $blood_group, $quantity, $hospital, $donation_date);
        $stmt->execute();

        echo "<script>alert('New donor added successfully!'); window.location.href='user_dashboard.php';</script>";
    }

    // ✅ Insert into donation history
    $insert_history = "INSERT INTO donor_history (donor_id, donation_date, quantity, hospital_name) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_history);
    $stmt->bind_param("isis", $user_id, $donation_date, $quantity, $hospital);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Donor</title>
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
    <h2>Add Donor</h2>

    <a href="user_dashboard.php" class="back-btn">🏠 Back to Home</a>

    <form method="POST">
        <!-- ✅ Name & Blood Group Autofill -->
        <input type="text" name="name" value="<?= htmlspecialchars($name); ?>" readonly>
        <input type="text" name="blood_group" value="<?= htmlspecialchars($blood_group); ?>" readonly>

         <select name="hospital" id="hospital" required>
            <option value="">-- Select Hospital --</option>
            <option value="Dev Hospital">Dev Hospital</option>
            <option value="AIIMS">AIIMS</option>
            <option value="Apollo Hospital">Apollo Hospital</option>
            <option value="Fortis Hospital">Fortis Hospital</option>
            <option value="Medanta Hospital">Medanta Hospital</option>
        </select>


        <input type="number" name="quantity" placeholder="Enter Quantity (unit)" min="1" max="3" required>

        <!-- ✅ Date (Only visible but not editable) -->
        <input type="text" value="<?= date('Y-m-d H:i:s'); ?>" readonly>
        
        <button type="submit">Add Donor</button>
    </form>
</div>

</body>
</html>
