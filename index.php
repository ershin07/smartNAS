<?php
// Load system data (later you will add UPS, HDD, fan, etc.)
$system = include "api/system.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SmartNAS Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <header>
        <h1>SmartNAS Dashboard</h1>
        <p class="subtitle">Raspberry Pi 5 • Custom UPS • RAID Storage</p>
    </header>

    <section class="card">
        <h2>System Status</h2>
        <div class="row">
            <div class="label">CPU Temp:</div>
            <div class="value"><?= $system['cpu_temp'] ?> °C</div>
        </div>
        <div class="row">
            <div class="label">CPU Load:</div>
            <div class="value"><?= $system['cpu_load'] ?>%</div>
        </div>
        <div class="row">
            <div class="label">Uptime:</div>
            <div class="value"><?= $system['uptime'] ?></div>
        </div>
    </section>

    <section class="card">
        <h2>Network</h2>
        <div class="row">
            <div class="label">IP Address:</div>
            <div class="value"><?= $system['ip'] ?></div>
        </div>
    </section>

</div>

</body>
</html>
