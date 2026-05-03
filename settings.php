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
                    <input type="range" id="fanSlider" min="50" max="100" value="<?php echo $current_duty; ?>">
                    
                    <!-- UI shows the real speed (50% to 100%) -->
                    <span id="fanValueDisplay" style="font-weight: bold; color: #00e0c6; margin-left: 10px;"></span>
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
    
    // UI shows 50-100% directly because of your new HTML min/max
    fanValueDisplay.textContent = fanSlider.value + "%";
}

// Mode Change Logic
fanMode.onchange = async function() {
    await fetch("api/fanmode.php?mode=" + fanMode.value);
    
    if (fanMode.value === "MANUAL") {
        // Map 50-100 slider to 0-100 PWM API
        const pwmValue = (fanSlider.value - 50) * 2;
        await fetch("api/fan.php?speed=" + pwmValue);
    }
    
    updateSliderState();
};

// Continuous update for the UI text (1:1 mapping now)
fanSlider.oninput = function() {
    fanValueDisplay.textContent = this.value + "%";
};

// Send command with the converted mapping
fanSlider.onchange = async function() {
    // Map 50-100 slider to 0-100 PWM API
    const pwmValue = (this.value - 50) * 2;
    await fetch("api/fan.php?speed=" + pwmValue);
};

// Initial state check
updateSliderState();
</script>

</body>
</html>