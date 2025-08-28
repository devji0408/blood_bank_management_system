<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

function redirectWithAlert($message, $url) {
    echo "<script>alert('$message'); window.location.href='$url';</script>";
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$blood_group = isset($_GET['blood_group']) ? trim($_GET['blood_group']) : '';

// Pagination setup
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$limit = 6; 
$offset = ($page - 1) * $limit;

// Secure Query using Prepared Statements
$sql = "SELECT * FROM blood_requests WHERE name LIKE ? ";
$params = ["%$search%"];
$types = "s";

if (!empty($blood_group)) {
    $sql .= " AND blood_group = ?";
    $params[] = $blood_group;
    $types .= "s";
}

$sql .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM blood_requests WHERE name LIKE ?";
$countParams = ["%$search%"];
$countTypes = "s";

if (!empty($blood_group)) {
    $countQuery .= " AND blood_group = ?";
    $countParams[] = $blood_group;
    $countTypes .= "s";
}

$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param($countTypes, ...$countParams);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Approve Request Function
if (isset($_GET['approve']) && is_numeric($_GET['approve'])) {
    $id = intval($_GET['approve']);

    $requestQuery = $conn->prepare("SELECT blood_group, quantity FROM blood_requests WHERE id = ?");
    $requestQuery->bind_param("i", $id);
    $requestQuery->execute();
    $requestResult = $requestQuery->get_result();

    if ($requestResult->num_rows > 0) {
        $requestData = $requestResult->fetch_assoc();
        $requestedBloodGroup = $requestData['blood_group'];
        $requestedQuantity = $requestData['quantity'];

        // Check Blood Stock
        $stockQuery = $conn->prepare("SELECT SUM(quantity) AS total_stock FROM donors WHERE blood_group = ?");
        $stockQuery->bind_param("s", $requestedBloodGroup);
        $stockQuery->execute();
        $stockResult = $stockQuery->get_result();
        $totalStock = $stockResult->fetch_assoc()['total_stock'] ?? 0;

        if ($totalStock < $requestedQuantity) {
            redirectWithAlert('Not enough blood stock available!', 'manage_requests.php');
        }

        // Deduct blood units from donors
        $donorQuery = $conn->prepare("SELECT id, quantity FROM donors WHERE blood_group = ? AND quantity > 0 ORDER BY id ASC");
        $donorQuery->bind_param("s", $requestedBloodGroup);
        $donorQuery->execute();
        $donorResult = $donorQuery->get_result();

        while ($requestedQuantity > 0 && $donorRow = $donorResult->fetch_assoc()) {
            $donorId = $donorRow['id'];
            $availableQuantity = $donorRow['quantity'];

            if ($availableQuantity >= $requestedQuantity) {
                $newQuantity = $availableQuantity - $requestedQuantity;
                $updateDonor = $conn->prepare("UPDATE donors SET quantity = ? WHERE id = ?");
                $updateDonor->bind_param("ii", $newQuantity, $donorId);
                $updateDonor->execute();
                $requestedQuantity = 0;
            } else {
                $requestedQuantity -= $availableQuantity;
                $zero = 0;
                $updateDonor = $conn->prepare("UPDATE donors SET quantity = ? WHERE id = ?");
                $updateDonor->bind_param("ii", $zero, $donorId);
                $updateDonor->execute();
            }
        }

        // Move request to approved_requests
        $moveQuery = $conn->prepare("INSERT IGNORE INTO approved_requests (id, name, blood_group, quantity, status, date) 
                                      SELECT id, name, blood_group, quantity, 'Approved', NOW() FROM blood_requests WHERE id = ?");
        $moveQuery->bind_param("i", $id);
        $moveQuery->execute();

        // Delete request from blood_requests
        $deleteQuery = $conn->prepare("DELETE FROM blood_requests WHERE id = ?");
        $deleteQuery->bind_param("i", $id);
        if ($deleteQuery->execute()) {
            redirectWithAlert('Request Approved Successfully!', 'manage_requests.php');
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}

// Delete Request Function
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $deleteQuery = $conn->prepare("DELETE FROM blood_requests WHERE id = ?");
    $deleteQuery->bind_param("i", $id);
    if ($deleteQuery->execute()) {
        redirectWithAlert('Request Deleted Successfully!', 'manage_requests.php');
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Requests</title>
   
</head>
<body>

<div class="container">
    <h2>Manage Blood Requests</h2>
    
    <a href="http://localhost/blood_bank/admin_dashboard.php" class="btn home-btn">🏠 Back to Home</a>
    
    <div class="search-filter-container">
        <form method="GET" action="">
            <input type="text" name="search" class="search-bar" placeholder="Search by Name" value="<?php echo htmlspecialchars($search); ?>">
            <select name="filter" class="filter-dropdown">
                <option value="">Filter by Blood Group</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>

    <<table border="1">
    <tr>
        <th>S.No</th>
        <th>Name</th>
        <th>Blood Group</th>
        <th>Quantity</th>
        <th>Date</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php
    $serialNumber = 1;
    while ($row = $result->fetch_assoc()) {
        $statusClass = ($row["status"] == "Approved") ? "status-approved" : "status-pending";
        echo "<tr>
                <td>" . $serialNumber++ . "</td>
                <td>" . htmlspecialchars($row["name"]) . "</td>
                <td>" . htmlspecialchars($row["blood_group"]) . "</td>
                <td>" . htmlspecialchars($row["quantity"]) . " units</td>
                <td>" . date("d-m-Y H:i:s", strtotime($row["date_added"])) . "</td>
                <td><span class='{$statusClass}'>" . htmlspecialchars($row["status"]) . "</span></td>
                <td>
                    <a class='approve-btn' href='manage_requests.php?approve=" . urlencode($row["id"]) . "'>✔ Approve</a> 
                   <a class='delete-btn' href='delete_request.php?id=" . urlencode($row["id"]) . "' onclick='confirmDelete(event);'>🗑️</a>
                    
                </td>
              </tr>";
    }
    ?>
</table>

<script>
function confirmDelete(event) {
    if (!confirm("Are you sure you want to delete this donor?")) {
        event.preventDefault(); // 🚫 Cancel delete request
    }
}
</script>




</body>
<style>
        /* Floating Shapes */
.floating-shapes {
    position: fixed;
    width: 100%;
    height: 100%;
    z-index: -1;
}

.shape {
    position: absolute;
    background: rgba(255, 71, 87, 0.5);
    border-radius: 50%;
    opacity: 0.6;
    animation: floating-shape-animation infinite ease-in-out alternate;
}

@keyframes floating-shape-animation {
    0% { transform: translateY(0px) scale(1); opacity: 0.6; }
    50% { transform: translateY(-20px) scale(1.1); opacity: 1; }
    100% { transform: translateY(0px) scale(1); opacity: 0.6; }
}

/* Button */
.btn {
    display: inline-block;
    padding: 12px 25px;
    background: #FF4757;
    color: white;
    text-decoration: none;
    border-radius: 30px;
    transition: 0.3s ease-in-out;
    font-weight: bold;
}

.btn:hover {
    background: #FF6B81;
    transform: scale(1.1);
}

        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color:rgba(23, 13, 26, 0.88);
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        h2 {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            margin: 5px;
        }

        .home-btn {
            background-color: #007bff;
        }
        .home-btn:hover {
            background-color: #0056b3;
        }
        .search-filter-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .search-bar, .filter-dropdown {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #dc3545;
            color: white;
        }
        .status-approved {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .status-pending {
            background-color: #ffc107;
            color: black;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .approve-btn, .delete-btn {
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .approve-btn {
            background-color: #28a745;
            color: white;
        }
        .approve-btn:hover {
            background-color: #218838;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .delete-btn:hover {
            background-color: #c82333;
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
    </style>
</html>
