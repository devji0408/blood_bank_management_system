<?php
session_start();
include 'config.php';  // Going up one level to include from the 'blood_bank' folder

// ⏳ Timeout after 1 minute of inactivity
$timeout_duration = 60; // 1 minute

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Session expired, force logout
    session_unset();
    session_destroy();
    header("Location: index.php"); // Redirect to login after timeout
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity time

// Agar user login nahi hai ya role 'admin' nahi hai, to redirect karo
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}




// Get the total number of donors
$donorQuery = "SELECT COUNT(*) as total_donors FROM donors";
$donorResult = $conn->query($donorQuery);
$donorCount = $donorResult->fetch_assoc()['total_donors'];

// Get the total number of requests
$requestQuery = "SELECT COUNT(*) as total_requests FROM blood_requests";
$requestResult = $conn->query($requestQuery);
$requestCount = $requestResult->fetch_assoc()['total_requests'];

// Get the total number of approved requests
$approvedQuery = "SELECT COUNT(*) as total_approved FROM approved_requests WHERE status='Approved'";
$approvedResult = $conn->query($approvedQuery);
$approvedCount = $approvedResult->fetch_assoc()['total_approved'];
?>

<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Management Dashboard</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    
</head>
<body>
    <h1><span>Blood Bank Management Dashboard</span></h1>

    <div class="container">
        <div class="box">
            <i class="fas fa-hand-holding-heart"></i> <!-- Icon for donors -->
            <span>&#128147;</span> <!-- Heart symbol -->
            <h2>Total Donors</h2>
            <p><?php echo $donorCount; ?></p>
        </div>

        <div class="box">
            <i class="fas fa-tint"></i> <!-- Icon for requests -->
            <span>&#128167;</span> <!-- Drop of blood symbol -->
            <h2>Total Requests</h2>
            <p><?php echo $requestCount; ?></p>
        </div>

        <div class="box">
            <i class="fas fa-check-circle"></i> <!-- Icon for approved requests -->
            <span>&#9989;</span> <!-- Checkmark symbol -->
            <h2>Approved Requests</h2>
            <p><?php echo $approvedCount; ?></p>
        </div>
    </div>

    <div class="button-container">
        
        <a href="manage_requests.php">
            <button class="button">Manage Requests</button>
        </a>

        <a href="approved_requests.php">
            <button class="button">✔ Approved Requests</button>
        </a>

        <a href="manage_donors.php">
            <button class="button">Manage Donors</button>
        </a>
        <a href="logout.php" >
            <button class="button ">Logout</button>
        </a>

        </div>
        
    <script> 

    function redirectWithEffect(url) {
        document.body.style.transition = "all 0.8s ease"; // Smooth transition effect
        document.body.style.opacity = "0"; // Fade-out effect
        document.body.style.transform = "scale(0.9)"; // Zoom-out effect
        setTimeout(() => {
            window.location.href = url; // Redirect to given URL
        }, 800); // 0.8s delay for smooth transition
    }
</script>

    <script src="transition.js"></script>
</body>


<!-- Font Awesome Icons -->
<style>
         body {
            background: url('images/wp.jpg') no-repeat center center/cover;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
}

        h1 {
            text-align: center;
            color: #f1c40f;
            padding: 20px;
            font-size: 36px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            overflow: hidden;
        }

        h1 span {
            display: inline-block;
            white-space: nowrap;
            animation: scroll-text 8s linear infinite;
        }

        @keyframes scroll-text {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .container {
            display: flex;
            justify-content: space-around;
            margin: 50px;
        }

        .box
        {
            background: linear-gradient(135deg, #8e44ad, #3498db);
            padding: 30px;
            color: white;
            border-radius: 15px;
            width: 30%;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .box:hover 
        {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
        }

        .box 
        h2 
        {
            margin-bottom: 10px;
            font-size: 24px;
        }
        .box i, .box span 
        {
            font-size: 50px;
            margin-bottom: 15px;
            color: #f1c40f;
            display: block;
        }

        .box p 
        {
            font-size: 28px;
            margin: 0;
            font-weight: bold;
        }

        .button-container 
        {
            text-align: center;
            margin-bottom: 20px;
        }
         .button
         {
            padding: 12px 25px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border: none;
            border-radius: 8px;
            margin: 10px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .button:hover
         {
            background: linear-gradient(135deg, #c0392b, #e74c3c);
            transform: scale(1.1);
        }
        </style>

</html>
