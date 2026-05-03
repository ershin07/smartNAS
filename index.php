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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        <div class="system-status-card">

            <!-- LEFT SIDE -->
            <div class="system-left">

                <div class="row">
                    <div class="label">CPU Temp:</div>
                    <div class="value" id="cpuTemp"><?= $system['CPU_TEMP'] ?> °C</div>
                </div>

                <div class="row">
                    <div class="label">CPU Load:</div>
                    <div class="value" id="cpuLoad"><?= $system['CPU_LOAD'] ?>%</div>
                </div>

                <div class="row">
                    <div class="label">Memory Used:</div>
                    <div class="value" id="memUsed"><?= $system['MEM'] ?? '--' ?></div>
                </div>

                <div class="row">
                    <div class="label">SD Total:</div>
                    <div class="value" id="sdTotal"><?= $system['SD_TOTAL'] ?? '--' ?></div>
                </div>

                <div class="row">
                    <div class="label">SD Used:</div>
                    <div class="value" id="sdUsed"><?= $system['SD_USED'] ?? '--' ?></div>
                </div>

                <div class="row">
                    <div class="label">SD Free:</div>
                    <div class="value" id="sdFree"><?= $system['SD_FREE'] ?? '--' ?></div>
                </div>

                <div class="row">
                    <div class="label">Uptime:</div>
                    <div class="value" id="uptime"><?= $system['UPTIME'] ?></div>
                </div>

            </div>

            <!-- RIGHT SIDE: REAL-TIME GRAPHS -->
            <div class="system-right">
                <canvas id="cpuLoadChart"></canvas>
                <canvas id="memUsedChart"></canvas>
            </div>

        </div>
    </section>


    <!-- NETWORK -->
    <section class="card">
        <h2>Network</h2>

        <div class="row">
            <div class="label">IP Address:</div>
            <div class="value ip" id="ipAddress"><?= $system['IP'] ?></div>
        </div>
    </section>

    <!-- UPS & FAN -->
    <section class="card">
        <h2>UPS & Fan Status</h2>

        <div class="row">
            <div class="label">Battery:</div>
            <div class="value" id="batValue"><?= $system['BAT'] ?>%</div>
        </div>

        <div class="row">
            <div class="label">Temperature:</div>
            <div class="value" id="tempValue"><?= $system['TEMP'] ?> °C</div>
        </div>

        <div class="row">
            <div class="label">Fan Speed:</div>
            <div class="value" id="fanValue"><?= $system['FAN'] ?>%</div>
        </div>

        <div class="row">
            <div class="label">Power Source:</div>
            <div class="value" id="powerValue"><?= $system['POWER'] ?></div>
        </div>
    </section>


    <!-- STORAGE -->
    <section class="card">
        <h2>Storage Status</h2>

        <div class="row">
            <div class="label">HDD0:</div>
            <div class="value" id="hdd0"><?= $system['HDD0'] == 1 ? "Online" : "Offline" ?></div>
        </div>

        <div class="row">
            <div class="label">HDD1:</div>
            <div class="value" id="hdd1"><?= $system['HDD1'] == 1 ? "Online" : "Offline" ?></div>
        </div>

        <div class="row">
            <div class="label">HDD2:</div>
            <div class="value" id="hdd2"><?= $system['HDD2'] == 1 ? "Online" : "Offline" ?></div>
        </div>

        <div class="row">
            <div class="label">HDD3:</div>
            <div class="value" id="hdd3"><?= $system['HDD3'] == 1 ? "Online" : "Offline" ?></div>
        </div>
    </section>

</div>

<!-- REALTIME TELEMETRY SCRIPT -->
<script>
let cpuLoadData = [];
let memUsedData = [];
let chartLabels = [];

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
        document.getElementById("memUsed").textContent = data.MEM + "%";
        document.getElementById("sdTotal").textContent = data.SD_TOTAL;
        document.getElementById("sdUsed").textContent = data.SD_USED;
        document.getElementById("sdFree").textContent = data.SD_FREE;
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

        // GRAPH UPDATE
        let now = new Date().toLocaleTimeString();
        chartLabels.push(now);

        cpuLoadData.push(parseInt(data.CPU_LOAD));
        memUsedData.push(parseInt(data.MEM));

        if (chartLabels.length > 20) {
            chartLabels.shift();
            cpuLoadData.shift();
            memUsedData.shift();
        }

        cpuLoadChart.update();
        memUsedChart.update();

    } catch (e) {
        console.log("System fetch error:", e);
    }
}

const cpuLoadChart = new Chart(document.getElementById('cpuLoadChart'), {
    type: 'line',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'CPU Load (%)',
            data: cpuLoadData,
            borderColor: 'rgb(54, 162, 235)',
            tension: 0.3
        }]
    },
    options: {
        animation: false,
        scales: { y: { beginAtZero: true, max: 100 } }
    }
});

const memUsedChart = new Chart(document.getElementById('memUsedChart'), {
    type: 'line',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'Memory Used (%)',
            data: memUsedData,
            borderColor: 'rgb(255, 159, 64)',
            tension: 0.3
        }]
    },
    options: {
        animation: false,
        scales: { y: { beginAtZero: true, max: 100 } }
    }
});

setInterval(updateSystemStatus, 2000);
updateSystemStatus();
</script>

</body>
</html>
