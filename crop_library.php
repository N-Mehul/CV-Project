<?php
session_start();

if(!isset($_SESSION['uid'])){
    header("location:login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Crop Library</title>

<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- SIDEBAR -->
<?php 
$activePage = 'crop_library';
include 'sidebar.php'; 
?>


<!-- MAIN -->
<div class="main">

    <div class="topbar">
        <h1>Crop Library</h1>
    </div>

    <p style="color: var(--text-muted); margin-bottom: 20px;">
        Search for crops or click a disease name to learn about its causes and solutions.
    </p>

    <!-- SEARCH BAR -->
    <div class="search-container">
        <span class="search-icon">🔍</span>
        <input type="text" id="cropSearch" class="search-bar" placeholder="Search for crops or diseases (e.g. Tomato, Blight)..." onkeyup="filterCrops()">
    </div>

    <p id="searchPlaceholder" style="text-align: center; color: var(--text-muted); margin: 40px 0; font-size: 18px;">
        Type in the search bar above to explore crops and diseases.
    </p>

    <div class="stats" id="cropGrid" style="display: none;">

        <div class="stat-box" data-tags="tomato early blight late leaf mold" style="display: none;">
            <h3>🌱 Tomato</h3>
            <p style="font-size: 16px; color: #666; margin-top: 15px;">
                <span class="disease-link" onclick="showDetails('Tomato Early Blight')">Early Blight</span>, 
                <span class="disease-link" onclick="showDetails('Tomato Late Blight')">Late Blight</span>, 
                <span class="disease-link" onclick="showDetails('Leaf Mold')">Leaf Mold</span>
            </p>
        </div>

        <div class="stat-box" data-tags="potato late blight scab" style="display: none;">
            <h3>🥔 Potato</h3>
            <p style="font-size: 16px; color: #666; margin-top: 15px;">
                <span class="disease-link" onclick="showDetails('Potato Late Blight')">Late Blight</span>, 
                <span class="disease-link" onclick="showDetails('Potato Scab')">Scab</span>
            </p>
        </div>

        <div class="stat-box" data-tags="maize leaf spot rust" style="display: none;">
            <h3>🌽 Maize</h3>
            <p style="font-size: 16px; color: #666; margin-top: 15px;">
                <span class="disease-link" onclick="showDetails('Maize Leaf Spot')">Leaf Spot</span>, 
                <span class="disease-link" onclick="showDetails('Maize Rust')">Rust</span>
            </p>
        </div>

        <div class="stat-box" data-tags="apple scab black rot" style="display: none;">
            <h3>🍎 Apple</h3>
            <p style="font-size: 16px; color: #666; margin-top: 15px;">
                <span class="disease-link" onclick="showDetails('Apple Scab')">Scab</span>, 
                <span class="disease-link" onclick="showDetails('Apple Black Rot')">Black Rot</span>
            </p>
        </div>

    </div>

    <div id="preventionTips" style="display: none;">
        <br>
        <h3>Basic Prevention Tips</h3>
        <ul style="margin-top:10px;line-height:25px; color: var(--text-muted);">
            <li>✔ Use healthy seeds</li>
            <li>✔ Avoid over-watering</li>
            <li>✔ Remove infected leaves</li>
            <li>✔ Use recommended fertilizers</li>
            <li>✔ Rotate crops</li>
        </ul>
    </div>

</div>

<!-- DISEASE DETAIL MODAL -->
<div class="modal-overlay" id="detailModal" onclick="closeModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <span class="modal-close" onclick="document.getElementById('detailModal').classList.remove('active')">&times;</span>
        <h2 id="modal-title">Disease Name</h2>
        
        <div class="modal-section">
            <div class="modal-section-title">❓ How it is caused</div>
            <div class="modal-section-body" id="modal-cause"></div>
        </div>

        <div class="modal-section">
            <div class="modal-section-title">🛡️ How to overcome it</div>
            <div class="modal-section-body" id="modal-solution"></div>
        </div>
    </div>
</div>

<script>
const diseaseData = {
    'Tomato Early Blight': {
        cause: "Hot, wet weather and old leaves left on the soil from last year.",
        solution: "Remove any leaves with spots and put a layer of straw or mulch on the soil to stop dirt from splashing onto the plant."
    },
    'Tomato Late Blight': {
        cause: "Cool, rainy days. It spreads very fast through wind and water.",
        solution: "Make sure plants have space for air to move. If it gets very wet, use protective sprays early to save the crop."
    },
    'Leaf Mold': {
        cause: "Mostly happens in greenhouses or very humid gardens with no wind.",
        solution: "Open windows or use fans to improve air. Cut extra branches to let the plant breathe better."
    },
    'Potato Late Blight': {
        cause: "Cold and damp weather. It can destroy the whole plant and the potatoes underground quickly.",
        solution: "Cover the potatoes well with soil (hilling). Throw away any rotten potatoes immediately so they don't spread germs."
    },
    'Potato Scab': {
        cause: "Dry soil and too much lime. This makes the potato skin look rough and corky.",
        solution: "Keep the soil damp while potatoes are growing. Avoid adding too much wood ash or lime to the soil."
    },
    'Maize Leaf Spot': {
        cause: "Hot and sticky weather, and leaving old corn stalks in the field.",
        solution: "Bury old corn stalks in the soil after harvest. Don't plant corn in the same spot every year."
    },
    'Maize Rust': {
        cause: "The wind blows tiny germs from other fields during warm weather.",
        solution: "Plant your corn early in the season. Use seeds that are known to be strong against rust."
    },
    'Apple Scab': {
        cause: "Rainy spring weather and old leaves on the ground near the tree.",
        solution: "Clean up and burn fallen leaves in the fall. Prune the tree branches so sunlight can reach the middle."
    },
    'Apple Black Rot': {
        cause: "Injuries on the tree bark and old, dried-up fruit left hanging on the branches.",
        solution: "Cut off dead or sick branches. Always remove and throw away any dried-up fruit you see on the tree."
    }
};

function showDetails(disease) {
    const data = diseaseData[disease];
    if (data) {
        document.getElementById('modal-title').innerText = disease;
        document.getElementById('modal-cause').innerText = data.cause;
        document.getElementById('modal-solution').innerText = data.solution;
        document.getElementById('detailModal').classList.add('active');
    }
}

function closeModal(e) {
    document.getElementById('detailModal').classList.remove('active');
}

function filterCrops() {
    const input = document.getElementById('cropSearch').value.toLowerCase();
    const grid = document.getElementById('cropGrid');
    const boxes = grid.getElementsByClassName('stat-box');
    const placeholder = document.getElementById('searchPlaceholder');
    const tips = document.getElementById('preventionTips');

    if (input.trim() === "") {
        grid.style.display = "none";
        tips.style.display = "none";
        placeholder.style.display = "block";
        return;
    }

    grid.style.display = "grid";
    tips.style.display = "block";
    placeholder.style.display = "none";

    let hasResults = false;
    for (let box of boxes) {
        const tags = box.getAttribute('data-tags').toLowerCase();
        if (tags.includes(input)) {
            box.style.display = "";
            hasResults = true;
        } else {
            box.style.display = "none";
        }
    }

    if (!hasResults) {
        // Optional: show a 'No results' message if you like
    }
}
</script>

</body>
</html>
