<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$blood_group = isset($_GET['blood_group']) ? trim($_GET['blood_group']) : '';
$hospital = isset($_GET['hospital']) ? trim($_GET['hospital']) : '';
$date = isset($_GET['date']) ? trim($_GET['date']) : ''; // Date input

// Pagination setup
$limit = 6;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Secure Query Using Prepared Statements
$sql = "SELECT id, name, blood_group, quantity, date_added, hospital FROM donors WHERE name LIKE ?";
$params = ["%$search%"];
$types = "s";

if (!empty($blood_group)) {
    $sql .= " AND blood_group = ?";
    $params[] = $blood_group;
    $types .= "s";
}

if (!empty($hospital)) {
    $sql .= " AND hospital LIKE ?";
    $params[] = "%$hospital%";
    $types .= "s";
}

if (!empty($date)) {
    $sql .= " AND DATE(date_added) = ?";
    $params[] = $date;
    $types .= "s";
}

$sql .= " ORDER BY date_added DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM donors WHERE name LIKE ?";
$paramsCount = ["%$search%"];
$typesCount = "s";

if (!empty($blood_group)) {
    $countQuery .= " AND blood_group = ?";
    $paramsCount[] = $blood_group;
    $typesCount .= "s";
}

if (!empty($hospital)) {
    $countQuery .= " AND hospital LIKE ?";
    $paramsCount[] = "%$hospital%";
    $typesCount .= "s";
}

if (!empty($date)) {
    $countQuery .= " AND DATE(date_added) = ?";
    $paramsCount[] = $date;
    $typesCount .= "s";
}

$stmtCount = $conn->prepare($countQuery);
$stmtCount->bind_param($typesCount, ...$paramsCount);
$stmtCount->execute();
$countResult = $stmtCount->get_result();
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Donors</title>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
</head>
<body>

<!-- Floating Shapes Background -->
<div id="particles-js"></div>

<div class="container">
    <h2>Manage Donors</h2>

    <!-- ✅ Back to Home Button -->
    <a href="admin_dashboard.php" class="back-btn">🏠 Back to Home</a>

 <!-- Search and Filter -->
<form method="GET">
    <input type="text" name="search" placeholder="Search donor by name" value="<?php echo htmlspecialchars($search); ?>" style="padding: 8px;">

    <select name="blood_group">
        <option value="">Filter by Blood Group</option>
        <option value="A+" <?php if ($blood_group == "A+") echo "selected"; ?>>A+</option>
        <option value="A-" <?php if ($blood_group == "A-") echo "selected"; ?>>A-</option>
        <option value="B+" <?php if ($blood_group == "B+") echo "selected"; ?>>B+</option>
        <option value="B-" <?php if ($blood_group == "B-") echo "selected"; ?>>B-</option>
        <option value="O+" <?php if ($blood_group == "O+") echo "selected"; ?>>O+</option>
        <option value="O-" <?php if ($blood_group == "O-") echo "selected"; ?>>O-</option>
        <option value="AB+" <?php if ($blood_group == "AB+") echo "selected"; ?>>AB+</option>
        <option value="AB-" <?php if ($blood_group == "AB-") echo "selected"; ?>>AB-</option>
    </select>

    <input type="text" name="hospital" placeholder="Enter Hospital Name" value="<?php echo htmlspecialchars($hospital ?? ''); ?>" style="padding: 8px;">

    <input type="date" name="date" value="<?php echo htmlspecialchars($date ?? ''); ?>" style="padding: 8px;">

    <button type="submit">Filter</button>
</form>


    <!-- ✅ Responsive Table -->
    <div class="table-container">
        <table>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Blood Group</th>
                <th>Quantity</th>
                <th>Date</th>
                <th>Hospital</th>
                <th>Actions</th>
            </tr>

            <?php
            $serialNumber = 1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $serialNumber++ . "</td>
                            <td>" . htmlspecialchars($row["name"]) . "</td>
                            <td>" . htmlspecialchars($row["blood_group"]) . "</td>
                            <td>" . htmlspecialchars($row["quantity"]) . "</td>
                            <td>" . htmlspecialchars($row["date_added"]) . "</td>
                            <td>" . htmlspecialchars($row["hospital"]) . "</td>
                            <td class='actions'>
                                <a class='action-btn edit-btn' href='edit_donor.php?id=" . urlencode($row["id"]) . "'><span>✏️</span></a>
                                <a class='delete-btn' href='delete_donor.php?id=" . urlencode($row["id"]) . "' onclick='confirmDelete(event);'>🗑️</a>
                                <a class='action-btn history-btn' href='donor_history.php?id=" . urlencode($row["id"]) . "'><span>📜</span></a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No donors found</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<script>
function confirmDelete(event) {
    if (!confirm("Are you sure you want to delete this donor?")) {
        event.preventDefault(); // 🚫 Cancel delete request
    }
}
</script>

<style>
    /* Floating Shapes Effect */
    #particles-js {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    body {
        font-family: Arial, sans-serif;
        background: #1A1A2E;
        color: white;
        text-align: center;
    }

    .container {
        position: relative;
        z-index: 2;
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

    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(56, 117, 125, 0.9);
        color: white;
        border-radius: 10px;
        overflow: show;
        white-space: nowrap;
    }

    th, td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
        
    }

    th {
        background-color:rgb(34, 17, 131);
        color: white;
    }

    tr:nth-child(even) {
        background-color:rgb(197, 168, 27);
    }

    .actions {
    display: flex;
    justify-content: center;
    gap: 4px;
    flex-wrap: wrap;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;  /* Thoda aur compact */
    color: white;
    width: 30px;
    height: 30px;
}

/* Individual button colors */
.edit-btn { background-color: #1976d2; }
.delete-btn { background-color: #d32f2f; }
.history-btn { background-color: rgb(182, 16, 224); }

/* Hover Effects */
.action-btn:hover {
    opacity: 0.8;
    transform: scale(1.1); /* Smooth effect */
    transition: 0.2s;
}


    .edit-btn { background-color: #1976d2; }
    .delete-btn { background-color:rgb(244, 15, 15); }
    .history-btn { background-color: rgb(182, 16, 224); }
</style>

</html>
