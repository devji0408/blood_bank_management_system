<?php
include "config.php"; // Database connection


// ✅ Form Submission Handling
if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];
    $blood_group = $_POST['blood_group'];
    $contact = trim($_POST['contact']);

    // ✅ Check if username already exists (Using Prepared Statement)
    $check_query = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_query->bind_param("s", $username);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        $register_error = "Username already taken!";
    } else {
        // ✅ Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // ✅ Insert new user into database (Prepared Statement)
        $insert_query = $conn->prepare("INSERT INTO users (name, username, password, role, blood_group, contact) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_query->bind_param("ssssss", $name, $username, $hashed_password, $role, $blood_group, $contact);

        if ($insert_query->execute()) {
            $register_success = "Registration successful! You can now log in.";
        } else {
            $register_error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Blood Bank</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1c1f26, #2c3e50);
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #34495e;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            width: 350px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.7);
        }

        h2 {
            color: #f1c40f;
            margin-bottom: 20px;
            font-size: 26px;
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input, select {
            background-color: #2c3e50;
            color: #fff;
            outline: none;
            border: 1px solid #34495e;
        }

        input:focus, select:focus {
            border-color: #f1c40f;
            box-shadow: 0 0 8px rgba(241, 196, 15, 0.7);
        }

        button {
            background: linear-gradient(135deg, #ff6b6b, #ff4757);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(255, 71, 87, 0.5);
            transition: all 0.3s ease;
        }

        button:hover {
            background: linear-gradient(135deg, #ff4757, #ff6b6b);
            box-shadow: 0 6px 14px rgba(255, 71, 87, 0.7);
            transform: translateY(-2px);
        }
        .back-btn {
            font-weight: bold;
            padding: 10px;
            border-radius: 8px;
            color: blue;
            display: block;
            text-align: center;
            margin-top: 10px;
        }
        .back-btn:hover {
            background: linear-gradient(135deg, rgb(39, 30, 167), rgb(31, 44, 191));
            box-shadow: 0 6px 14px rgba(15, 49, 218, 0.8);
            transform: translateY(-2px);
            color: white;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 500;
        }

        .error {
            background-color: #ff4757;
            color: #fff;
        }

        .success {
            background-color: #2ecc71;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>User Registration</h2>

    <!-- ✅ Back to Home Button -->
    <a href="http://localhost/blood_bank/blood_bank.php" class="back-btn">🏠 Back to Home</a>

    <!-- ✅ Display Error/Success Messages -->
    <?php if (!empty($register_error)) : ?>
        <div class="message error"><?= $register_error; ?></div>
    <?php endif; ?>

    <?php if (!empty($register_success)) : ?>
        <div class="message success"><?= $register_success; ?></div>
    <?php endif; ?>

    <!-- ✅ Registration Form -->
    <form action="register.php" method="POST">
        <!-- Name -->
        <input type="text" name="name" placeholder="Full Name" required>

        <!-- Username -->
        <input type="text" name="username" placeholder="Username" required>

        <!-- Password -->
        <input type="password" name="password" placeholder="Password" required>

        <!-- Blood Group -->
        <select name="blood_group" required>
            <option value="">Select Blood Group</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
        </select>

        <!-- Contact -->
        <input type="text" name="contact" placeholder="Contact Number" required>

        <!-- Role -->
        <select name="role" required>
            
            <option value="user" selected>User</option>
        </select>

        <!-- Register Button -->
        <button type="submit" name="register">Register</button>
    </form>
</div>

</body>
</html>
