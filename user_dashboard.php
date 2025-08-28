<?php
session_start();
include 'config.php';

// ⏳ Timeout after 1 minute of inactivity
$timeout_duration = 60; // 1 minute

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Session expired
    session_unset();
    session_destroy();
    header("Location: index.php"); // Redirect to login after timeout
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity time

// ⛔ Check if user is logged in and has 'user' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php"); // Redirect to home if not user
    exit();
}


$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT username, profile_img, blood_group FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Powered Dashboard - Blood Bank</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            background: linear-gradient(135deg,rgb(35, 30, 66),rgb(40, 31, 47));
            min-height: 100vh;
            overflow: hidden;
            position: relative;
            transition: 0.3s;
            color: yellow;
        }

        /* Dark Mode */
        body.dark-mode {
            background: #1e1e1e;
            color: white;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            padding: 20px;
            height: 100vh;
            box-shadow: 4px 0 12px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 0 15px 15px 0;
        }

        /* Profile Image */
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #f1c40f;
            object-fit: cover;
            transition: 0.3s;
        }

        .profile-img:hover {
            transform: scale(1.1);
        }

        /* Upload Button */
        .button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background: linear-gradient(135deg, #ff6b6b, #ff4757);
            color: white;
            font-size: 18px;
            cursor: pointer;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: 0.3s;
        }

        .button:hover {
            background:rgb(54, 44, 108);
            transform: scale(1.05);
        }
     /* Blood Compatibility Checker */
     .blood-checker {
            position: absolute;
            top: 30px;
            right: 30px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-size: 16px;
            width: 280px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 8px;
            border-radius: 5px;
            border: none;
        }
        /* Dark Mode Toggle */
        .dark-mode-toggle {
            position: absolute;
            top: 20px;
            left: 20px;
            background: white;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        /* Floating Blood Cells */
        .floating-shape {
            position: absolute;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: floatAnimation 8s infinite ease-in-out;
        }

        .floating-shape:nth-child(1) { top: 10%; left: 20%; animation-duration: 6s; }
        .floating-shape:nth-child(2) { top: 40%; left: 80%; animation-duration: 7s; }
        .floating-shape:nth-child(3) { top: 70%; left: 30%; animation-duration: 5s; }

        @keyframes floatAnimation {
            0% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.1); }
            100% { transform: translateY(0) scale(1); }
        }
/* AI Suggestions */
.ai-suggestions {
            margin: top 25px;
            padding: 160px;
}
    </style>
</head>
<body>

<!-- Floating Blood Cells -->
<div class="floating-shape"></div>
<div class="floating-shape"></div>
<div class="floating-shape"></div>

<!-- Dark Mode Toggle -->
<div class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</div>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Profile Image -->
    <img src="<?= !empty($user['profile_img']) ? $user['profile_img'] : 'images/default-profile.png'; ?>" 
         alt="Profile" class="profile-img" id="profile-preview">

    <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="profile_img" id="profile-img" accept="image/*" style="display:none;">
        <button type="button" class="button" onclick="document.getElementById('profile-img').click()">📤 Upload Image</button>
    </form>

    <div class="user-info">
        <h2><?= $user['username']; ?></h2>
        <span class="blood-group">🩸 Blood Group: <?= !empty($user['blood_group']) ? $user['blood_group'] : 'N/A'; ?></span>
    </div>

    <!-- Action Buttons -->
    <div class="button-container">
        <button class="button" onclick="window.location.href='add_request.php'">
            <i class="fas fa-plus-circle"></i> Add Request
        </button>
        <button class="button" onclick="window.location.href='blood_stock.php'">
            <i class="fas fa-tint"></i> Blood Stock
        </button>
        <button class="button" onclick="window.location.href='add_donor.php'">
            <i class="fas fa-user-plus"></i> Add Donor
        </button>
        <button class="button" onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>
</div>

<!-- AI Suggestions -->
<div class="ai-suggestions">
    <h3>🔮 AI Suggestions</h3>
    <p id="quote">"Be the reason for someone’s heartbeat – Donate blood!"</p>
</div>

<!-- Blood Compatibility Checker -->
<div class="blood-checker">
    <h3>🩸 Blood Compatibility Checker</h3>
    <select id="bloodType">
        <option value="A+">A+</option>
        <option value="A-">A-</option>
        <option value="B+">B+</option>
        <option value="B-">B-</option>
        <option value="O+">O+</option>
        <option value="O-">O-</option>
        <option value="AB+">AB+</option>
        <option value="AB-">AB-</option>
    </select>
    <button class="button" onclick="checkCompatibility()">Check</button>
    <p id="result"></p>
</div>

<script>
    
    // ✅ AI Quote Change
    const quotes = [
        "Donate blood, save lives!",
        "Your blood donation can save up to 3 lives!",
        "Be a hero, donate blood today!",
        "A drop of blood can save a life!",
         "Real heroes don’t wear capes, they donate blood!",
         "A little pain for you, a lifetime gain for someone else.",
         "Every drop counts. Give blood, give life!",
         "Blood donation: A small act of kindness with a global impact.",
         "Blood is replaceable, but a life is not.",
            "Your 15 minutes can mean someone’s forever!",
         "The gift of blood is the gift of life. Make a difference today!",
    ];
    
    function changeQuote() {
        document.getElementById('quote').innerText = quotes[Math.floor(Math.random() * quotes.length)];
    }
    
    setInterval(changeQuote, 5000);

    // Blood compatibility checker

    function checkCompatibility() {
        const bloodType = document.getElementById("bloodType").value;
        const compatibility = {
            "A+": "Can receive A+, A-, O+, O-",
            "O+": "Can receive O+, O-",
            "B+": "Can receive B+, B-, O+, O-",
            "AB+": "Can receive all blood types"
        };
        document.getElementById("result").innerText = compatibility[bloodType] || "Check manually!";
    }
    
    // Dark Mode 
    function toggleDarkMode() {
        document.body.classList.toggle("dark-mode");
    }

    // ✅ Profile Image Upload
    document.getElementById('profile-img').addEventListener('change', function() {
        let formData = new FormData();
        formData.append('profile_img', this.files[0]);

        fetch('upload_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('profile-preview').src = data.file_path;
            }
        });
    });
</script>

</body>
</html>
