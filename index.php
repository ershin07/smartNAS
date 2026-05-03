<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SmartNAS Dashboard</title>
    <link rel="stylesheet" href="css/style.css?v=1">
</head>

<body>
<div class="container">

    <header>
        <h1>SmartNAS Dashboard</h1>
        <div class="subtitle">Raspberry Pi 5 • Custom UPS • RAID Storage</div>
    </header>

    <!-- SYSTEM STATUS -->
    <section class="card">
        <h2>System Status</h2>

        <div class="row">
            <div class="label">CPU Temp:</div>
            <div class="value" id="cpuTemp"><?= $system['cpu_temp'] ?> °C</div>
        </div>

        <div class="row">
            <div class="label">CPU Load:</div>
            <div class="value" id="cpuLoad"><?= $system['cpu_load'] ?>%</div>
        </div>

        <div class="row">
            <div class="label">Uptime:</div>
            <div class="value" id="uptime"><?= $system['uptime'] ?></div>
        </div>
    </section>

    <!-- UPS & FAN -->
    <section class="card">
        <h2>UPS & Fan Status</h2>

        <div class="row">
            <div class="label">Battery:</div>
            <div class="value" id="batValue"><?= $arduino['BAT'] ?>%</div>
        </div>

        <div class="row">
            <div class="label">Temperature:</div>
            <div class="value" id="tempValue"><?= $arduino['TEMP'] ?> °C</div>
        </div>

        <div class="row">
            <div class="label">Fan Speed:</div>
            <div class="value" id="fanValue"><?= $arduino['FAN'] ?>%</div>
        </div>

        <div class="row">
            <div class="label">Power Source:</div>
            <div class="value" id="powerValue"><?= $arduino['POWER'] ?></div>
        </div>
    </section>

    <!-- STORAGE -->
    <section class="card">
        <h2>Storage Status</h2>

        <div class="row">
            <div class="label">HDD0:</div>
            <div class="value <?= ($arduino['HDD0'] ?? 0) == 1 ? 'present' : 'missing' ?>">
                <?= ($arduino['HDD0'] ?? 0) == 1 ? "Present" : "Missing" ?>
            </div>
        </div>

        <div class="row">
            <div class="label">HDD1:</div>
            <div class="value <?= ($arduino['HDD1'] ?? 0) == 1 ? 'present' : 'missing' ?>">
                <?= ($arduino['HDD1'] ?? 0) == 1 ? "Present" : "Missing" ?>
            </div>
        </div>

        <div class="row">
            <div class="label">HDD2:</div>
            <div class="value <?= ($arduino['HDD2'] ?? 0) == 1 ? 'present' : 'missing' ?>">
                <?= ($arduino['HDD2'] ?? 0) == 1 ? "Present" : "Missing" ?>
            </div>
        </div>

        <div class="row">
            <div class="label">HDD3:</div>
            <div class="value <?= ($arduino['HDD3'] ?? 0) == 1 ? 'present' : 'missing' ?>">
                <?= ($arduino['HDD3'] ?? 0) == 1 ? "Present" : "Missing" ?>
            </div>
        </div>
    </section>

    <!-- NETWORK -->
    <section class="card">
        <h2>Network</h2>

        <div class="row">
            <div class="label">IP Address:</div>
            <div class="value ip"><?= str_replace(" ", "\n", $system['ip']) ?></div>
        </div>
    </section>

</div>

<!-- REALTIME TELEMETRY SCRIPT -->
<script>
async function updateTelemetry() {
    try {
        const response = await fetch("api/arduino.php");
        const data = await response.json();

        // UPS & FAN
        document.getElementById("batValue").textContent = data.BAT + "%";
        document.getElementById("tempValue").textContent = data.TEMP + " °C";
        document.getElementById("fanValue").textContent = data.FAN + "%";
        document.getElementById("powerValue").textContent = data.POWER;

        // SYSTEM (optional if you want realtime system stats)
        // document.getElementById("cpuTemp").textContent = data.cpu_temp + " °C";
        // document.getElementById("cpuLoad").textContent = data.cpu_load + "%";
        // document.getElementById("uptime").textContent = data.uptime;

    } catch (e) {
        console.log("Telemetry fetch error:", e);
    }
}

// Update every 5 seconds
setInterval(updateTelemetry, 5000);

// Update immediately on page load
updateTelemetry();
</script>

</body>
</html>
