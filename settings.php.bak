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
        <div class="control-buttons">
            <a href="index.php" class="btn-nav">Dashboard</a>

            <button class="icon-btn" onclick="location.href='settings.php'">
                <span class="hamburger"></span>
            </button>
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
                    <option value="AUTO">Auto</option>
                    <option value="MANUAL">Manual</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="label">Fan Speed:</div>
            <div class="value">
                <input type="range" id="fanSlider" min="0" max="100" value="50">
                <span id="fanValueDisplay">50%</span>
            </div>
        </div>

    </section>

</div>

<script>
// Buttons
document.getElementById('shutdownBtn').onclick = async () => {
    if (!confirm("Shutdown NAS?")) return;
    await fetch("api/shutdown.php");
    alert("Shutdown command sent.");
};

document.getElementById('rebootBtn').onclick = async () => {
    if (!confirm("Reboot NAS?")) return;
    await fetch("api/reboot.php");
    alert("Reboot command sent.");
};

// Fan controls
const fanMode = document.getElementById('fanMode');
const fanSlider = document.getElementById('fanSlider');
const fanValueDisplay = document.getElementById('fanValueDisplay');

function updateSliderState() {
    const isAuto = (fanMode.value === "AUTO");
    fanSlider.disabled = isAuto;
    fanSlider.style.opacity = isAuto ? "0.5" : "1";
}

// Mode Change Logic
fanMode.onchange = async function() {
    // 1. Tell Arduino to switch modes
    await fetch("api/fanmode.php?mode=" + fanMode.value);
    
    // 2. If switched to MANUAL, immediately sync the fan speed to the current slider position
    if (fanMode.value === "MANUAL") {
        await fetch("api/fan.php?speed=" + fanSlider.value);
    }
    
    updateSliderState();
};

// Continuous update for the UI text
fanSlider.oninput = function() {
    fanValueDisplay.textContent = fanSlider.value + "%";
};

// Send command only when the user lets go of the slider (efficiency)
fanSlider.onchange = async function() {
    await fetch("api/fan.php?speed=" + fanSlider.value);
};

// Initial state check
updateSliderState();

</script>

</body>
</html>
