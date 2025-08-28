<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if(isset($_POST['clear_history'])){
    $query = "DELETE FROM approved_requests";
    $stmt = $conn->prepare($query);
    
    if ($stmt->execute()) {
        echo "<script>alert('Approved requests history cleared successfully!'); window.location.href='approved_requests.php';</script>";
    } else {
        error_log("Error clearing history: " . $stmt->error);
        echo "<script>alert('Error clearing history. Please try again later.');</script>";
    }
}

// Fetch approved requests securely
$query = "SELECT id, name, blood_group, quantity, status, date FROM approved_requests ORDER BY date DESC";
$result = $conn->query($query);

if (!$result) {
    error_log("Query Failed: " . $conn->error);
    die("<script>alert('Error fetching data.');</script>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Approved Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: linear-gradient(135deg,rgb(26, 20, 20),rgb(25, 20, 17)); /* Smooth gradient */
            overflow: hidden;
            position: relative;
        }

        /* Floating Shapes */
        .floating-shape {
            position: absolute;
            width: 50px;
            height: 50px;
            background: rgba(236, 21, 21, 0.69);
            border-radius: 50%;
            animation: floatAnimation 6s infinite ease-in-out;
        }

        .floating-shape:nth-child(1) {
            top: 10%;
            left: 20%;
            animation-duration: 7s;
        }

        .floating-shape:nth-child(2) {
            top: 50%;
            left: 70%;
            animation-duration: 5s;
        }

        .floating-shape:nth-child(3) {
            top: 80%;
            left: 30%;
            animation-duration: 6s;
        }

        @keyframes floatAnimation {
            0% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0); }
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: rgba(34, 14, 14, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgb(40, 26, 26);
        }

        h2 {
            background-color:rgb(37, 71, 79);
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            color: white;
            box-shadow: 0px 0px 10px rgba(6, 4, 4, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color:rgb(13, 166, 222);
            color: white;
        }

        .clear-history {
            background: red;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s;
        }

        .clear-history:hover {
            background: darkred;
        }

        .back-btn {
            display: inline-block;
            background: #343a40;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: 15px;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: black;
        }
    </style>
</head>
<body>

<!-- Floating Shapes -->
<div class="floating-shape"></div>
<div class="floating-shape"></div>
<div class="floating-shape"></div>

<div class="container">
    <h2>Approved Blood Requests</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Blood Group</th>
            <th>Quantity</th>
            <th>Approval Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['blood_group']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['date']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <!-- Clear History Button -->
    <form method="POST" onsubmit="return confirm('Are you sure you want to clear all approved requests history?');">
        <button type="submit" name="clear_history" class="clear-history">Clear History</button>
    </form>

    <!-- Back to Dashboard Button -->
    <br>
    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>
