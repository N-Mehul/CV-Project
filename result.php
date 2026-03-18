<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

if(empty($_GET['file'])){
    echo "No file specified";
    exit;
}

$file = basename($_GET['file']);
$path = "uploads/".$file;

$res = mysqli_query($conn,"
SELECT predicted, created_at
FROM uploads
WHERE filename='".mysqli_real_escape_string($conn,$file)."'
LIMIT 1
");

$predicted = "Not available";
$created_at = "";

if($res && mysqli_num_rows($res)){
    $row = mysqli_fetch_assoc($res);
    $predicted = $row['predicted'];
    $created_at = $row['created_at'];
}

// Function to format disease names for better display
function formatDiseaseName($disease) {
    if (!$disease) return "Prediction Unavailable";
    
    $disease = trim($disease);
    
    // If it's an error message, display it as is
    if (strpos($disease, 'Error:') === 0) {
        return htmlspecialchars($disease);
    }
    
    // If it's "Healthy", return it formatted
    if ($disease === 'Healthy') {
        return 'Healthy (No Disease Detected)';
    }
    
    if ($disease === "Unknown" || $disease === "Unable_to_classify" || $disease === "Unable to classify" || $disease === "NOT_A_LEAF") {
        return "Unable to Classify";
    }
    
    // Clean up common folder prefixes/suffixes for disease names
    $disease = str_replace('___', ' - ', $disease);
    $disease = str_replace('__', ' ', $disease);
    $disease = str_replace('_', ' ', $disease);
    
    // Specific cleanup for common typos or odd folder naming
    $disease = str_replace('Tomato  Tomato', 'Tomato', $disease);
    
    return ucwords(strtolower($disease));
}

// Function to get disease information
function getDiseaseInfo($disease) {
    if (!$disease) {
        return 'No information available.';
    }
    
    $disease = trim($disease);
    
    // If it's an error message, return it as is
    if (strpos($disease, 'Error:') === 0) {
        return htmlspecialchars($disease);
    }
    
    // If it's "Healthy"
    if ($disease === 'Healthy') {
        return 'The plant appears to be healthy with no signs of disease detected.';
    }
    
    // If it's "Unknown" or not a leaf
    if ($disease === 'Unknown' || $disease === 'NOT_A_LEAF') {
        return 'This does not appear to be a plant leaf image. Please upload a plant leaf.';
    }
    
    // If it's Unable to classify
    if ($disease === 'Unable to classify' || $disease === 'Unable_to_classify') {
        return 'The leaf is unclear or cannot be confidently classified. Please provide a clearer image.';
    }
    
    $info = [
        'Apple___Scab' => 'A fungal disease that causes olive-green velvety spots on leaves and fruit, often leading to deformed fruit and leaf drop.',
        'Corn___Rust' => 'A fungal disease causing rectangular reddish-brown pustules on corn leaves, which can reduce yield and plant vigor.',
        'Pepper__bell___Bacterial_spot' => 'Causes small water-soaked spots on leaves and fruit. It is a serious bacterial disease.',
        'Pepper__bell___healthy' => 'The pepper plant appears healthy with no signs of disease.',
        'Potato___Early_blight' => 'A fungal disease causing dark, concentric spots on older potato leaves.',
        'Potato___Late_blight' => 'A devastating disease that can rapidly turn potato leaves and stems black and slimy.',
        'Potato___healthy' => 'The potato plant appears healthy with no signs of disease.',
        'Tomato_Bacterial_spot' => 'Causes small black spots on tomato leaves, often with a yellow halo.',
        'Tomato_Early_blight' => 'A common fungal disease causing dark spots with concentric rings on tomato leaves.',
        'Tomato_Late_blight' => 'A serious disease that can destroy tomato plants rapidly in cool, wet weather.',
        'Tomato_Leaf_Mold' => 'A fungal disease causing yellow spots on the upper leaf surface and olive-green mold on the underside.',
        'Tomato_Septoria_leaf_spot' => 'Causes small circular spots with dark brown margins and gray centers on tomato foliage.',
        'Tomato_Spider_mites_Two_spotted_spider_mite' => 'Not a disease but a pest infestation that leaves tiny yellow dots on leaves.',
        'Tomato__Target_Spot' => 'A fungal disease characterized by brown circular spots with dark rings (like a target).',
        'Tomato__Tomato_YellowLeaf__Curl_Virus' => 'A viral disease causing severe stunting and upward curling of tomato leaves.',
        'Tomato__Tomato_mosaic_virus' => 'Causes mottling and distortion of leaves, often with light and dark green patches.',
        'Tomato_healthy' => 'The tomato plant appears healthy with no signs of disease.',
        'Tomato___Healthy' => 'The tomato plant appears healthy with no signs of disease.',
        'Tomato___Early_blight' => 'A common fungal disease causing dark spots with concentric rings on tomato leaves.',
        'Tomato___Late_blight' => 'A serious disease that can destroy tomato plants rapidly in cool, wet weather.',
        'Unknown' => 'The system could not classify this plant or determine the disease type.'
    ];
    
    return isset($info[$disease]) ? $info[$disease] : 'Disease information not available.';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Disease Detection Result</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="dashboard-layout">

<?php 
$activePage = 'upload'; // Keep detection active
include 'sidebar.php'; 
?>

<div class="main">
<div class="upload-card-wrapper">
<div class="upload-card">
    <div class="header">
        <div class="icon-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C10 14.52 12 13 13 12"/></svg>
        </div>
        <div class="header-text">
            <h1>Detection Result</h1>
            <div class="tagline">
                <div class="tagline-bar"></div>
                Analysis complete
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-bottom: 25px;">
        <img src="<?php echo htmlspecialchars($path); ?>" style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 20px; border: 1px solid var(--light-green);">
    </div>

    <div style="background: var(--light-green); padding: 25px; border-radius: 24px; margin-bottom: 25px;">
        <h3 style="color: var(--primary-green); margin-bottom: 10px; font-weight: 600;">Prediction Report</h3>
        <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 15px;">
            <strong>Detected:</strong> <?php echo htmlspecialchars(formatDiseaseName($predicted)); ?>
        </p>
        
        <div style="background: rgba(255,255,255,0.6); padding: 15px; border-radius: 16px; font-size: 14px; color: var(--text-dark); line-height: 1.6;">
            <?php echo htmlspecialchars(getDiseaseInfo($predicted)); ?>
        </div>
    </div>

    <div class="action-buttons">
        <a href="upload.php" class="btn-premium btn-filled" style="text-decoration: none;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Scan New
        </a>
        <a href="dashboard.php" class="btn-premium btn-outline" style="text-decoration: none;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Dashboard
        </a>
    </div>

</div>
</div>
</div>

</body>
</html>