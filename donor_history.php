<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$donor_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($donor_id <= 0) {
    echo "Invalid Donor ID";
    exit();
}

// Pagination setup
$limit = 6;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Fetch donor details
$donorQuery = "SELECT name, blood_group FROM donors WHERE id = ?";
$stmt = $conn->prepare($donorQuery);
$stmt->bind_param("i", $donor_id);
$stmt->execute();
$donorResult = $stmt->get_result();
$donor = $donorResult->fetch_assoc();

if (!$donor) {
    echo "Donor not found.";
    exit();
}

// Fetch donation history
$sql = "SELECT hospital_name, quantity, donation_date FROM donor_history WHERE donor_id = ? ORDER BY donation_date DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $donor_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM donor_history WHERE donor_id = ?";
$stmtCount = $conn->prepare($countQuery);
$stmtCount->bind_param("i", $donor_id);
$stmtCount->execute();
$countResult = $stmtCount->get_result();
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Donor History</title>
</head>
<body>

<div class="container">
    <h2>History of <?php echo htmlspecialchars($donor['name']); ?> (<?php echo htmlspecialchars($donor['blood_group']); ?>)</h2>
    <a href="manage_donors.php" class="back-btn">🏠 Back to Donors</a>

    <table>
        <tr>
            <th>Hospital Name</th>
            <th>Quantity (unit)</th>
            <th>Date</th>
        </tr>
        
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["hospital_name"]) . "</td>
                        <td>" . htmlspecialchars($row["quantity"]) . "</td>
                        <td>" . htmlspecialchars($row["donation_date"]) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No donation history found</td></tr>";
        }
        ?>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="donor_history.php?id=<?php echo $donor_id; ?>&page=<?php echo ($page - 1); ?>">⬅️ Previous</a>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
            <a href="donor_history.php?id=<?php echo $donor_id; ?>&page=<?php echo ($page + 1); ?>">Next ➡️</a>
        <?php endif; ?>
    </div>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #1A1A2E;
        color: white;
        text-align: center;
    }
    .container {
        width: 80%;
        margin: 20px auto;
    }
    h2 {
        background-color: #d32f2f;
        color: white;
        padding: 10px;
        border-radius: 5px;
    }
    .back-btn {
        background: #007bff;
        color: white;
        padding: 10px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }
    .back-btn:hover {
        background: #0056b3;
    }
    table {
        width: 100%;
        margin-top: 10px;
        border-collapse: collapse;
        background: rgba(255, 255, 255, 0.9);
        color: black;
        border-radius: 10px;
        overflow: hidden;
    }
    th, td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }
    th {
        background: #d32f2f;
        color: white;
    }
    .pagination {
        margin-top: 20px;
    }
    .pagination a {
        color: white;
        padding: 8px 12px;
        margin: 5px;
        background: #d32f2f;
        text-decoration: none;
        border-radius: 5px;
    }
</style>
</body>
</html>
