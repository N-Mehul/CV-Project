<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])){

    // check for upload errors early
    if($_FILES['image']['error'] !== UPLOAD_ERR_OK){
        switch($_FILES['image']['error']){
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $error = "The uploaded file exceeds the maximum allowed size.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $error = "The file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $error = "No file was selected for upload.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $error = "Temporary folder missing on server.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $error = "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $error = "A PHP extension stopped the file upload.";
                break;
            default:
                $error = "Unknown upload error (code: ".
                          intval($_FILES['image']['error']).
                          ").";
        }
    }

    if(empty($error)){
        $uploadDir = __DIR__ . '/uploads/';

        if(!is_dir($uploadDir)){
            mkdir($uploadDir,0777,true);
        }

        $tmp = $_FILES['image']['tmp_name'];
        $name = time().'_'.basename($_FILES['image']['name']);
        $target = $uploadDir.$name;

        if(move_uploaded_file($tmp,$target)){

        /* ------------------ RUN PYTHON MODEL ------------------ */

        $predicted = "Unknown";
        $confidence = 0;

        // Validate image file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $fileType = $_FILES['image']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            $error = "Only JPEG and PNG images are allowed.";
        } else {
            // Try different python commands
            $pythonCommand = null;
            $testOutput = [];
            $testRetval = 0;
            
            // Try 'python' first
            exec('python --version', $testOutput, $testRetval);
            if ($testRetval === 0) {
                $pythonCommand = 'python';
            } else {
                // Try 'python3'
                exec('python3 --version', $testOutput, $testRetval);
                if ($testRetval === 0) {
                    $pythonCommand = 'python3';
                }
            }
            
            if ($pythonCommand === null) {
                $predicted = "Error: Python not found";
            } else {
                // Use absolute path for the script
                $scriptPath = __DIR__ . '/model/prediction.py';
                $cmd = $pythonCommand . ' ' . escapeshellarg($scriptPath) . ' ' . escapeshellarg($target);

                $output = [];
                $retval = 0;

                exec($cmd, $output, $retval);

                $predicted = "Error: Unable to process image";
                
                if ($retval === 0 && !empty($output)) {
                    $predicted = "Unknown";
                    foreach ($output as $line) {
                        $trimmedLine = trim($line);
                        if (strpos($trimmedLine, 'Disease:') === 0) {
                            $predicted = trim(substr($trimmedLine, 8));
                            $confidence = 1; // Confidence not available in exact output format
                            break;
                        } elseif ($trimmedLine === "No plant detected. Please upload a clear plant leaf image.") {
                            $predicted = "NOT_A_LEAF";
                            break;
                        }
                    }
                } else {
                    $predicted = "Error: Prediction failed - Python error";
                }
            }

            /* ------------------ SAVE RESULT ------------------ */

            $uid = $_SESSION['user_id'];

            $fn = mysqli_real_escape_string($conn,$name);
            $pred = mysqli_real_escape_string($conn,$predicted);

            // Reconnect if the connection was lost during the slow python prediction process
            if (!mysqli_ping($conn)) {
                mysqli_close($conn);
                require 'db.php';
            }

            $query = "INSERT INTO uploads (user_id,filename,predicted) VALUES ('$uid','$fn','$pred')";
            
            if(mysqli_query($conn, $query)){
                header("Location: result.php?file=".$name);
                exit;
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        }

    } else {

        $error = "Upload failed.";

    }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Leaf Image - Plant Disease Detection</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="dashboard-layout">

<?php 
$activePage = 'upload';
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
            <h1>Upload Leaf Image</h1>
            <div class="tagline">
                <div class="tagline-bar"></div>
                Identify plant health & species
            </div>
        </div>
    </div>

    <?php if(!empty($error)): ?>
    <div class="error-message">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <?php echo $error; ?>
    </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" id="upload-form">
        <input type="file" name="image" id="file-input" accept="image/*" required>
        
        <div class="drop-zone" id="drop-zone">
            <div class="cloud-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19c.4 0 .7-.1.9-.3.2-.2.3-.5.3-.9 0-.3-.1-.6-.3-.9-.2-.2-.5-.3-.9-.3H16c0-3.3-2.7-6-6-6-2.5 0-4.6 1.5-5.4 3.7C3.1 14.9 2 16.3 2 18c0 2.2 1.8 4 4 4h11.5Z"/><path d="m12 12-4 4h8l-4-4Z"/><path d="M12 22v-6"/></svg>
            </div>
            <div class="drop-zone-text">Drag & drop or click to browse</div>
            <div class="file-info-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                JPG, PNG up to 10MB
            </div>
        </div>

        <div class="selected-file-list" id="selected-file-list" style="display: none;">
            <div class="file-item">
                <div class="file-item-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C10 14.52 12 13 13 12"/></svg>
                </div>
                <div class="file-details">
                    <div class="file-name" id="display-filename">image.jpg</div>
                    <div class="file-meta">
                        <span id="display-filesize">2.4 MB</span>
                        <span class="status-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            selected
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button type="button" class="btn-premium btn-outline" id="browse-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Choose File
            </button>
            <button type="submit" class="btn-premium btn-filled">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Upload
            </button>
        </div>
    </form>

    <a href="dashboard.php" class="btn-back">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Dashboard
    </a>
</div>
</div>

<script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const browseBtn = document.getElementById('browse-btn');
    const fileList = document.getElementById('selected-file-list');
    const fileNameDisplay = document.getElementById('display-filename');
    const fileSizeDisplay = document.getElementById('display-filesize');
    const form = document.getElementById('upload-form');

    // Trigger file selection
    browseBtn.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('click', () => fileInput.click());

    // Handle file selection
    fileInput.addEventListener('change', handleFiles);

    // Drag and drop handlers
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
        });
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFiles();
    });

    function handleFiles() {
        const file = fileInput.files[0];
        if (file) {
            fileNameDisplay.textContent = file.name;
            fileSizeDisplay.textContent = formatBytes(file.size);
            fileList.style.display = 'block';
        }
    }

    function formatBytes(bytes, decimals = 1) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
</script>

</body>
</html>