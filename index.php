<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaf AI | Plant Disease Detection</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* GLOBAL STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f9fbf9;
            color: #333;
            line-height: 1.6;
        }

        /* NAVIGATION */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 8%;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #2e7d32;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* HERO SECTION */
        .hero {
            background: linear-gradient(135deg, #0f3d2e 0%, #2e7d32 100%);
            color: white;
            padding: 100px 8%;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .hero h1 {
            font-size: clamp(32px, 5vw, 56px);
            margin-bottom: 20px;
            letter-spacing: -1px;
            max-width: 800px;
        }

        .hero p {
            font-size: 1.1rem;
            margin-bottom: 35px;
            opacity: 0.9;
            max-width: 600px;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            padding: 14px 32px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-white {
            background: white;
            color: #2e7d32;
        }

        .btn-white:hover {
            background: #e8f5e9;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-outline:hover {
            background: rgba(255,255,255,0.1);
        }

        /* FEATURES SECTION */
        .features-container {
            padding: 80px 8%;
            text-align: center;
        }

        .section-title {
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 32px;
            color: #1b4332;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .feature-box {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            border: 1px solid #eee;
        }

        .feature-box:hover {
            transform: translateY(-10px);
            border-color: #2e7d32;
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 20px;
            display: block;
        }

        .feature-box h3 {
            margin-bottom: 15px;
            color: #2e7d32;
            font-size: 22px;
        }

        .feature-box p {
            color: #666;
            font-size: 15px;
        }

        /* FOOTER */
        footer {
            background: #0f3d2e;
            color: rgba(255,255,255,0.7);
            text-align: center;
            padding: 40px 20px;
            margin-top: 50px;
        }

        footer strong {
            color: white;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .hero { padding: 60px 5%; }
            .features-grid { gap: 15px; }
        }
    </style>
</head>
<body>

<nav>
    <div class="logo">🌿 Leaf AI</div>
    <div>
        <a href="login.php" style="text-decoration:none; color:#2e7d32; font-weight:600; margin-right:20px;">Login</a>
        <a href="register.php" class="btn btn-white" style="padding: 8px 20px; background:#2e7d32; color:white;">Sign Up</a>
    </div>
</nav>

<header class="hero">
    <h1>Plant Disease Detection</h1>
    <p>Upload a photo of your crop's leaves and get instant disease identification.</p>
    <div class="hero-buttons">
        <a href="login.php" class="btn btn-white">Get Started Now</a>
        <a href="#features" class="btn btn-outline">Learn More</a>
    </div>
</header>

<section class="features-container" id="features">
    <div class="section-title">
        <h2>Why Choose Leaf AI?</h2>
    </div>
    
    <div class="features-grid">
        <div class="feature-box">
            <span class="feature-icon">📷</span>
            <h3>Image Upload</h3>
            <p>Simply take a photo using your phone or upload a file. Our system processes images in seconds.</p>
        </div>

        <div class="feature-box">
            <span class="feature-icon">🧠</span>
            <h3>AI Detection</h3>
            <p>Powered by deep learning to identify 38+ different types of plant diseases with 95% accuracy.</p>
        </div>

        <div class="feature-box">
            <span class="feature-icon">📊</span>
            <h3>Detailed Reports</h3>
            <p>Receive a comprehensive breakdown of the health status and severity of the infection.</p>
        </div>

        <div class="feature-box">
            <span class="feature-icon">💊</span>
            <h3>Treatment Plans</h3>
            <p>Get immediate biological and chemical treatment suggestions to save your crops.</p>
        </div>
    </div>
</section>

<footer>
    <p>© 2026 <strong>Plant Disease Detection</strong> | Empowering Farmers Worldwide</p>
</footer>

</body>
</html>