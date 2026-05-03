<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SmartNAS Settings</title>
    <link rel="stylesheet" href="css/style.css?v=3">
</head>

<body>
<div class="container">

    <header>
        <h1>SmartNAS Settings</h1>
        <nav class="top-nav">
            <a href="index.php">Dashboard</a>
            <a href="settings.php">Settings</a>
        </nav>
    </header>

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
            <div class="label">Fan Speed:</div>
            <div class="value">
                <input type="range" id="fanSlider" min="0" max="100" value="50">
                <span id="fanValueDisplay">50%</span>
            </div>
        </div>
    </section>

</div>

<script>
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

document.getElementById('fanSlider').oninput = function() {
    document.getElementById('fanValueDisplay').textContent = this.value + "%";
};

document.getElementById('fanSlider').onchange = async function() {
    const speed = this.value;
    await fetch("api/fan.php?speed=" + speed);
};

</script>

</body>
</html>
