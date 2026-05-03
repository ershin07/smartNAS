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
            <div class="value" id="cpuTemp"><?= $system['CPU_TEMP'] ?> °C</div>
            <div class="value" id="cpuLoad"><?= $system['CPU_LOAD'] ?>%</div>
            <div class="value" id="uptime"><?= $system['UPTIME'] ?></div>
    </section>

     <!-- NETWORK -->
    <section class="card">
        <h2>Network</h2>
        <div class="value ip" id="ipAddress"><?= $system['IP'] ?></div>
    </section>

    <!-- UPS & FAN -->
    <section class="card">
        <div class="value" id="batValue"><?= $system['BAT'] ?>%</div>
        <div class="value" id="tempValue"><?= $system['TEMP'] ?> °C</div>
        <div class="value" id="fanValue"><?= $system['FAN'] ?>%</div>
        <div class="value" id="powerValue"><?= $system['POWER'] ?></div>
    </section>

    <!-- STORAGE -->
    <section class="card">
        <h2>Storage Status</h2>
        <div class="value" id="hdd0"><?= $system['HDD0'] == 1 ? "Online" : "Offline" ?></div>
        <div class="value" id="hdd1"><?= $system['HDD1'] == 1 ? "Online" : "Offline" ?></div>
        <div class="value" id="hdd2"><?= $system['HDD2'] == 1 ? "Online" : "Offline" ?></div>
        <div class="value" id="hdd3"><?= $system['HDD3'] == 1 ? "Online" : "Offline" ?></div>
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
            let key, value;

            if (pair.includes("=")) {
                [key, value] = pair.split("=");
            } else if (pair.includes(":")) {
                [key, value] = pair.split(":");
            }

            if (key && value) {
                data[key.trim()] = value.trim();
            }
        });

        // SYSTEM
        document.getElementById("cpuTemp").textContent = data.CPU_TEMP + " °C";
        document.getElementById("cpuLoad").textContent = data.CPU_LOAD + " %";
        document.getElementById("uptime").textContent = data.UPTIME;
        document.getElementById("ipAddress").textContent = data.IP;

        // UPS & FAN
        document.getElementById("batValue").textContent = data.BAT + "%";
        document.getElementById("tempValue").textContent = data.TEMP + " °C";
        document.getElementById("fanValue").textContent = data.FAN + "%";
        document.getElementById("powerValue").textContent = data.POWER;

        // HDDs
        document.getElementById("hdd0").textContent = data.HDD0 == 1 ? "Online" : "Offline";
        document.getElementById("hdd1").textContent = data.HDD1 == 1 ? "Online" : "Offline";
        document.getElementById("hdd2").textContent = data.HDD2 == 1 ? "Online" : "Offline";
        document.getElementById("hdd3").textContent = data.HDD3 == 1 ? "Online" : "Offline";

    } catch (e) {
        console.log("System fetch error:", e);
    }
}

setInterval(updateSystemStatus, 2000);
updateSystemStatus();
</script>
</body>
</html>
