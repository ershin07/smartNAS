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

// Enable/disable slider based on mode
function updateSliderState() {
    if (fanMode.value === "AUTO") {
        fanSlider.disabled = true;
        fanSlider.style.opacity = "0.5";
    } else {
        fanSlider.disabled = false;
        fanSlider.style.opacity = "1";
    }
}

// When mode changes → send MODE command + update UI
fanMode.onchange = async function() {
    await fetch("api/fanmode.php?mode=" + fanMode.value);
    updateSliderState();
};

// Update displayed % while sliding
fanSlider.oninput = function() {
    fanValueDisplay.textContent = fanSlider.value + "%";
};

// Send DUTY command when slider released
fanSlider.onchange = async function() {
    await fetch("api/fan.php?speed=" + fanSlider.value);
};

// Initialize UI state on load
updateSliderState();

</script>

</body>
</html>
