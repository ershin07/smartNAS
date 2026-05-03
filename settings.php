<?php
// Read current state from the temp files
// Use defaults if files don't exist yet
$current_mode = trim(@file_get_contents('/tmp/fan_mode')) ?: 'AUTO';
$current_duty = trim(@file_get_contents('/tmp/fan_duty')) ?: '50';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SmartNAS Settings</title>
    <link rel="stylesheet" href="css/style.css?v=4">
</head>

<body>
<div class="container">

    <header>
        <h1>SmartNAS Settings</h1>
    </header>

    <section class="card top-controls">
        <!-- Use top-controls WITHOUT the "card" class here -->
    <div class="top-controls">
        <div class="control-buttons">
            <button class="dashboard-btn" onclick="window.open('http://pinas.local:80', '_blank')">
                Dashboard
            </button>

            <button class="setting-btn active" onclick="location.href='settings.php'">
                Settings
            </button>

             <button class="omv-btn" onclick="window.open('http://pinas.local:8080', '_blank')">
                OMV
            </button>
        </div>
    </div>
    </section>

    <section class="card">
        <h2>System Control</h2>

        <div class="row">
            <div class="label">Safe Shutdown:</div>
            <div class="value">
                <button id="shutdownBtn">Shutdown NAS</button>
            </div>
        </div>

        <div class="row">
            <div class="label">Reboot System:</div>
            <div class="value">
                <button id="rebootBtn">Reboot NAS</button>
            </div>
        </div>

        <div class="row">
            <div class="label">Fan Mode:</div>
            <div class="value">
                <select id="fanMode">
                    <!-- Check which mode is active and add 'selected' -->
                    <option value="AUTO" <?php echo ($current_mode == 'AUTO') ? 'selected' : ''; ?>>Auto</option>
                    <option value="MANUAL" <?php echo ($current_mode == 'MANUAL') ? 'selected' : ''; ?>>Manual</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="label">Fan Speed:</div>
                <div class="value">
                    <!-- Internal value stays 0-100 for your API -->
                    <input type="range" id="fanSlider" min="0" max="100" value="<?php echo $current_duty; ?>">
                </div>
                <button id="applyFanBtn" class="btn" style="display: none; margin-top: 10px; background-color: #00e0c6; color: #121212; width: 100%;">
                    Apply Changes
                </button>
        </div>

    </section>

</div>

<script>
// Elements
const fanMode = document.getElementById('fanMode');
const fanSlider = document.getElementById('fanSlider');
const applyFanBtn = document.getElementById('applyFanBtn');

function updateSliderState() {
    const isAuto = (fanMode.value === "AUTO");
    fanSlider.disabled = isAuto;
    fanSlider.style.opacity = isAuto ? "0.5" : "1";
    // Hide apply button if switching back to AUTO
    if (isAuto) applyFanBtn.style.display = "none";
}

// 1. Show the button when the slider is moved
fanSlider.oninput = function() {
    applyFanBtn.style.display = "block";
};

// 2. Send the API request ONLY when Apply is clicked
applyFanBtn.onclick = async function() {
    const speed = fanSlider.value;
    await fetch("api/fan.php?speed=" + speed);
    
    // Hide the button again after successful update
    applyFanBtn.style.display = "none";
    alert("Fan speed updated to " + speed + "% (Hardware Base: 50%)");
};

// Mode Change Logic
fanMode.onchange = async function() {
    await fetch("api/fanmode.php?mode=" + this.value);
    
    if (this.value === "MANUAL") {
        // Show button to confirm the manual setting
        applyFanBtn.style.display = "block";
    }
    updateSliderState();
};

// Buttons for Power
document.getElementById('shutdownBtn').onclick = async () => {
    if (confirm("Shutdown NAS?")) await fetch("api/shutdown.php");
};

document.getElementById('rebootBtn').onclick = async () => {
    if (confirm("Reboot NAS?")) await fetch("api/reboot.php");
};

// Initial state
updateSliderState();
</script>

</body>
</html>