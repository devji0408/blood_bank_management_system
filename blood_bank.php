<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Management</title>
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
        }

        /* Header */
        header {
            background:rgba(131, 131, 64, 0.82);
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 10;
        }

        .header-title {
            color: #FFF;
            font-size: 26px;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 700px;
        }

        .hero h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 4px 10px rgba(255, 71, 87, 0.7);
            animation: glow 1.5s infinite alternate;
        }

        .hero p {
            font-size: 18px;
            font-weight: 400;
            margin-bottom: 25px;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #FFCCBC;
            color: #B71C1C;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
        }

        .btn:hover {
            background: #FFAB91;
            box-shadow: 0 6px 12px rgba(255, 193, 7, 0.5);
            transform: translateY(-3px) scale(1.05);
        }

        /* Knowledge Button */
        .knowledge-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: #1e90ff;
            color: #fff;
            font-size: 18px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(30, 144, 255, 0.5);
            text-decoration: none;
        }

        .knowledge-btn:hover {
            background: #007bff;
            box-shadow: 0 6px 12px rgba(30, 144, 255, 0.7);
            transform: translateY(-2px);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) scale(0.8);
            width: 60%;
            background-color: rgba(0, 0, 0, 0.9);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(255, 0, 0, 0.5);
            transition: all 0.3s ease-in-out;
            opacity: 0;
        }

        .modal.show {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .modal-content {
            background: #2C2C54;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
        }

        .close {
            float: right;
            font-size: 28px;
            cursor: pointer;
            color: white;
        }

        .close:hover {
            color: #ff4757;
        }

        
        /* Glow Animation */
    
        @keyframes floating-drops {
    0% { transform: translateY(0); opacity: 0.7; }
    50% { transform: translateY(-20px); opacity: 1; }
    100% { transform: translateY(0); opacity: 0.7; }
}
   /* Smooth Page Transitions */
body {
    opacity: 1;
    transform: translateX(0);
    transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
}

.fade-slide-out {
    opacity: 0;
    transform: translateX(-30px);
}


    </style>
</head>
<body>

<!-- Knowledge Modal -->
<div id="knowledgeModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Blood Donation Facts & FAQs</h2>
        <p>🩸 Blood donation is a life-saving act that can help up to three patients with a single donation.</p>
        <p>🩸 A healthy adult can donate blood every 3 months.</p>
        <p>🩸 There is no risk of infection when donating blood.</p>
        <p>🩸 Blood cannot be manufactured; it only comes from generous donors.</p>
        <a href="http://localhost/blood_bank/knowledge.php" class="btn">View More</a>
    </div>
</div>

<!-- Header -->
<header>
    <h2 class="header-title">Blood Bank Management System</h2>
</header>

<!-- Hero Section -->
<section class="hero">
    <div id="particles-js"></div>
    <div class="hero-content">
        <h1>Donate Blood, Save Lives</h1>
        <p>Your small act of kindness can make a big difference.</p>
        <a href="#" class="btn" onclick="redirectWithEffect('http://localhost/blood_bank/index.php')">Login</a>
        <a href="#" class="btn" onclick="redirectWithEffect('http://localhost/blood_bank/register.php')">Register</a>

        <br><br>
        <a href="#" class="knowledge-btn" onclick="openModal()">Learn More</a>
    </div>
</section>

<!-- Footer -->
<footer>
    &copy; 2025 Blood Bank Management System | All Rights Reserved
</footer>

<!-- JS -->
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


    function openModal() {
        document.getElementById("knowledgeModal").style.display = "block";
        setTimeout(() => document.getElementById("knowledgeModal").classList.add("show"), 10);
    }

    function closeModal() {
        document.getElementById("knowledgeModal").classList.remove("show");
        setTimeout(() => document.getElementById("knowledgeModal").style.display = "none", 300);
    }
    document.addEventListener("DOMContentLoaded", function () {
    document.body.classList.remove("fade-slide-out");
});

    function redirectWithEffect(url) {
        document.body.style.transition = "all 0.8s ease"; // Smooth transition effect
        document.body.style.opacity = "0"; // Fade-out effect
        document.body.style.transform = "scale(0.9)"; // Zoom-out effect
        setTimeout(() => {
            window.location.href = url; // Redirect to given URL
        }, 800); // 0.8s delay for smooth transition
    }



</script>

</body>
</html>
