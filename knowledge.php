<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Knowledge</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #1A1A2E;
            color: #FFF;
            text-align: center;
            padding: 50px 20px;
            position: relative;
            overflow: auto; /* ✅ SCROLL ENABLE */
        }

        /* Particle Animation */
        #particles-js {
            position: absolute; /* ✅ FIXED FROM FIXED */
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: auto;
            background: #2C2C54;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(255, 0, 0, 0.3);
            height: auto; /* ✅ ENSURE FULL HEIGHT */
        }

        h1 {
            font-size: 32px;
            color: #FFC107;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .faq {
            text-align: left;
            margin-top: 20px;
        }

        .faq h3 {
            color: #FF4757;
            margin-bottom: 10px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: #E74C3C;
            color: white;
            font-size: 18px;
            font-weight: 600;
            border-radius: 30px;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(255, 71, 87, 0.5);
        }

        .back-btn:hover {
            background: #C0392B;
            box-shadow: 0 6px 14px rgba(255, 71, 87, 0.7);
            transform: scale(1.05);
        }
        .quiz-section {
        background: #2C2C54;
        padding: 30px;
        text-align: center;
        margin: 20px auto;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(255, 0, 0, 0.5);
        width: 80%;
    }

    .quiz-box {
        margin-top: 20px;
        background: #1A1A2E;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(255, 0, 0, 0.3);
    }

    .quiz-btn {
        padding: 10px 20px;
        margin: 10px;
        font-size: 18px;
        background: #ff4757;
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .quiz-btn:hover {
        background: #e74c3c;
    }
    </style>
</head>
<body>

<!-- Particle Background -->
<div id="particles-js"></div>

<div class="container">
    <h1>Blood Donation Facts & FAQs</h1>
    <p>🩸 <strong>One blood donation can save up to three lives.</strong></p>
    <p>🩸 <strong>A healthy adult can donate blood every 3 months.</strong></p>
    <p>🩸 <strong>Blood donation is completely safe, and there is no risk of infection.</strong></p>
    <p>🩸 <strong>Blood cannot be manufactured; it only comes from donors like you.</strong></p>

    <div class="faq">
        <h2>Frequently Asked Questions (FAQs)</h2>

        <h3>Q: Who can donate blood?</h3>
        <p>✅ Any healthy individual aged 18-65, weighing at least 50 kg, can donate.</p>

        <h3>Q: How often can I donate blood?</h3>
        <p>✅ You can donate whole blood every 3 months. Platelet donors can donate every 2 weeks.</p>

        <h3>Q: What should I eat before donating blood?</h3>
        <p>✅ Eat a light, healthy meal and drink plenty of water before donating.</p>

        <h3>Q: Is there any risk of infection?</h3>
        <p>✅ No, sterile, one-time-use needles are used, making donation completely safe.</p>

        <h3>Q: How long does the donation process take?</h3>
        <p>✅ The entire process takes around 30-45 minutes, while the actual donation lasts about 10 minutes.</p>

        <h3>Q: Can I donate blood if I have a tattoo?</h3>
        <p>✅ Yes, but only if your tattoo was done in a licensed facility and has healed for at least 6 months.</p>
    </div>

<!-- Blood Donation Quiz Section -->
<div class="quiz-section">
    <h2>🩸 Test Your Blood Donation Knowledge! 🧠</h2>
    <div class="quiz-box">
        <h3>1️⃣ Can you donate blood if you have a cold?</h3>
        <button class="quiz-btn" onclick="checkAnswer(this, false)">Yes</button>
        <button class="quiz-btn" onclick="checkAnswer(this, true)">No</button>
    </div>

    <div class="quiz-box">
        <h3>2️⃣ How often can a healthy person donate blood?</h3>
        <button class="quiz-btn" onclick="checkAnswer(this, true)">Every 3 months</button>
        <button class="quiz-btn" onclick="checkAnswer(this, false)">Once a year</button>
    </div>

    <div class="quiz-box">
        <h3>3️⃣ What is the universal donor blood type?</h3>
        <button class="quiz-btn" onclick="checkAnswer(this, false)">A+</button>
        <button class="quiz-btn" onclick="checkAnswer(this, true)">O-</button>
    </div>
</div>

    <a href="blood_bank.php" class="back-btn">Back to Home</a>
</div>

<!-- Blood Drop Animation -->
<script>
    particlesJS("particles-js", {
        particles: {
            number: { value: 40, density: { enable: true, value_area: 800 } },
            color: { value: "#ff4757" },
            shape: { type: "circle" },
            opacity: { value: 0.6, random: true },
            size: { value: 5, random: true },
            move: {
                enable: true,
                speed: 2,
                direction: "top",
                random: true,
                out_mode: "out"
            }
        }
    });
</script>

<!-- JavaScript for Answer Checking -->
<script>

    function checkAnswer(btn, isCorrect) {
        if (isCorrect) {
            btn.style.background = "green";
            btn.innerText = "✅ Correct!";
        } else {
            btn.style.background = "red";
            btn.innerText = "❌ Wrong!";
        }
    }
</script>
</body>
</html>
