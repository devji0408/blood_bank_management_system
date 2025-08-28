<?php
session_start();
include 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    $redirectPage = ($_SESSION['role'] === 'admin') ? "admin_dashboard.php" : "user_dashboard.php";
    header("Location: $redirectPage");
    exit();
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $type = $_POST['type'];

    // Prepared statement to prevent SQL Injection
    $query = "SELECT id, username, password, role FROM users WHERE username = ? AND role = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $type);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verify user exists & password is correct
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Store session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            header("Location: " . ($type === 'admin' ? "admin_dashboard.php" : "user_dashboard.php"));
            exit();
        }
    }

    $login_error = "Invalid Username, Password, or Role!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script defer src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1A1A2E, rgba(37, 56, 71, 0.43));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #FFF;
            overflow: hidden;
            position: relative;
        }

        /* Floating Blood Drop Shapes */
        .shape {
            position: absolute;
            width: 50px;
            height: 50px;
            background: rgba(255, 0, 0, 0.5);
            border-radius: 50%;
            animation: float 6s infinite alternate ease-in-out;
        }

        .shape:nth-child(1) { top: 10%; left: 15%; animation-duration: 5s; }
        .shape:nth-child(2) { top: 40%; left: 80%; animation-duration: 7s; }
        .shape:nth-child(3) { top: 70%; left: 30%; animation-duration: 6s; }
        .shape:nth-child(4) { top: 20%; left: 60%; animation-duration: 8s; }

        @keyframes float {
            0% { transform: translateY(0); }
            100% { transform: translateY(-20px); }
        }

        /* Login Card */
        .card {
            background: rgba(15, 18, 19, 0.81);
            padding: 30px;
            border-radius: 16px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #FFC107;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 12px;
            border-radius: 8px;
            color: #FFF;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .form-control:focus {
            border-color: #FFC107;
            box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3);
            outline: none;
        }

        /* Password Field with Eye Icon */
        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #FFF;
        }

        .btn-danger {
            background-color: #FFC107;
            color: #2C2C54;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-danger:hover {
            background-color: #FFD54F;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #FFC107;
            text-decoration: none;
            font-weight: 500;
        }

        .back-btn:hover {
            color: #FFD54F;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Floating Shapes -->
<div class="shape"></div>
<div class="shape"></div>
<div class="shape"></div>
<div class="shape"></div>

<div class="card">
    <h2>🔐 Blood Bank Login</h2>

    <form method="POST">
        <input type="text" name="username" placeholder="👤 Username" class="form-control" required>
        
        <!-- Password Field with Eye Icon -->
        <div class="password-container">
            <input type="password" id="password" name="password" placeholder="🔒 Password" class="form-control" required>
            <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
        </div>

        <select name="type" class="form-control">
            <option value="admin">👨‍💼 Admin</option>
            <option value="user">🙋‍♂️ User</option>
        </select>

        <button type="submit" class="btn btn-danger">🚀 Login</button>
    </form>

    <a href="http://localhost/blood_bank/blood_bank.php" class="back-btn">🏠 Back to Home</a>
</div>

<!-- Password Toggle Script -->
<script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        var eyeIcon = document.querySelector(".toggle-password");

        passwordField.type = passwordField.type === "password" ? "text" : "password";
        eyeIcon.classList.toggle("fa-eye");
        eyeIcon.classList.toggle("fa-eye-slash");
    }
</script>

</body>
</html>
