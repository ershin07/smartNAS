<?php

function parse_simple_text($text) {
    $result = [];
    $pairs = explode(";", trim($text));

    foreach ($pairs as $pair) {
        if (strpos($pair, "=") !== false) {
            list($key, $value) = explode("=", $pair, 2);
        } elseif (strpos($pair, ":") !== false) {
            list($key, $value) = explode(":", $pair, 2);
        } else {
            continue;
        }

        $result[trim($key)] = trim($value);
    }

    return $result;
}

$system_raw = file_get_contents("api/system.php");
$arduino_raw = file_get_contents("api/arduino.php");

$system = parse_simple_text($system_raw);
$arduino = parse_simple_text($arduino_raw);

?>

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
            <div class="value" id="CPU_TEMP"><?= $system['cpu_temp'] ?> °C</div>
        </div>

        <div class="row">
            <div class="label">CPU Load:</div>
            <div class="value" id="CPU_LOAD"><?= $system['cpu_load'] ?>%</div>
        </div>

        <div class="row">
            <div class="label">Uptime:</div>
            <div class="value" id="UPTIME"><?= $system['uptime'] ?></div>
        </div>
    </section>

     <!-- NETWORK -->
    <section class="card">
        <h2>Network</h2>

        <div class="row">
            <div class="label">IP Address:</div>
            <div class="value ip"><?= str_replace(" ", "\n", $system['IP']) ?></div>
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

   

</div>

<!-- REALTIME TELEMETRY SCRIPT -->
<script>
async function updateSystemStatus() {
    try {
        const response = await fetch("api/system.php?nocache=" + Date.now());
        const text = await response.text();

        const data = {};
        text.trim().split(";").forEach(pair => {
            if (pair.includes("=")) {
                const [key, value] = pair.split("=");
                data[key.trim()] = value.trim();
            }
        });

        document.getElementById("cpuTemp").textContent = data.CPU_TEMP + " °C";
        document.getElementById("cpuLoad").textContent = data.CPU_LOAD + " %";
        document.getElementById("uptime").textContent = data.UPTIME;
        document.getElementById("ipAddress").textContent = data.IP;

    } catch (e) {
        console.log("System fetch error:", e);
    }
}


setInterval(() => {
    updateTelemetry();
    updateSystemStatus();
}, 2000);

updateTelemetry();
updateSystemStatus();
</script>
</body>
</html>
