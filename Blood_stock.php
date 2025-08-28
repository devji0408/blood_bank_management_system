<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$blood_group = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';

// Pagination setup
$limit = 6; // Donors per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch donors with filters
$sql = "SELECT id, name, blood_group, quantity, date_added FROM donors WHERE name LIKE '%$search%'";

if ($blood_group != '')
 {
    $sql .= " AND blood_group = '$blood_group'";
 }

$sql .= " ORDER BY date_added DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM donors WHERE name LIKE '%$search%'";

if ($blood_group != '')
 {
    $countQuery .= " AND blood_group = '$blood_group'";
 }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Donors</title>
    <style>
        body {
           
            background-size: cover;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            background: #1A1A2E;
            color: #FFF;
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
            background-color: #d32f2f;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f8d7da;
        }
        .edit-btn, .delete-btn ,.history-btn{
            padding: 8px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            color: white;
        }
        .edit-btn { background-color: #1976d2; }
        .edit-btn:hover { background-color: #1565c0; }
        .delete-btn { background-color: #d32f2f; }
        .delete-btn:hover { background-color: #b71c1c; }
        .history-btn  {background-color:rgb(182, 16, 224);}
        .history-btn:hover {background-color:rgb(242, 18, 220);}
        .add-donor-btn {
            display: inline-block;
            background-color: #388e3c;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .add-donor-btn:hover {
            background-color: #2e7d32;
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
</head>
<body>

<div class="container">
    <h2>Blood Stock</h2>

    <!-- ✅ Back to Home Button -->
    <a href="http://localhost/blood_bank/user_dashboard.php" class="back-btn">🏠 Back to Home</a>

    <!-- Search and Filter -->
    <form method="GET">
        <input type="text" name="search" placeholder="Search donor by name" value="<?php echo $search; ?>" style="padding: 8px;">
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
        <button type="submit">Filter</button>
    </form>

    <table>
<tr>
    <th>S.No</th>
    <th>Name</th>
    <th>Blood Group</th>
    <th>Quantity (unit)</th>
    <th>Date</th>
</tr>

<?php
$serialNumber = $offset + 1;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $serialNumber++ . "</td>
                <td>" . htmlspecialchars($row["name"]) . "</td>
                <td>" . htmlspecialchars($row["blood_group"]) . "</td>
                <td>" . htmlspecialchars($row["quantity"]) . "</td>
                <td>" . htmlspecialchars($row["date_added"]) . "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No donors found</td></tr>";
}
?>

</table>

</div>

</body>
</html>
