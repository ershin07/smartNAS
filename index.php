<?php
// Load system data (later you will add UPS, HDD, fan, etc.)
$system = include "api/system.php";
$arduino = include __DIR__ . "/api/arduino.php";
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
        <h2>UPS & Fan Status</h2>

        <div class="row"><div class="label">Battery:</div><div class="value"><?= $arduino['BAT'] ?? 'N/A' ?>%</div></div>
        <div class="row"><div class="label">Temperature:</div><div class="value"><?= $arduino['TEMP'] ?? 'N/A' ?> °C</div></div>
        <div class="row"><div class="label">Fan Speed:</div><div class="value"><?= $arduino['FAN'] ?? 'N/A' ?>%</div></div>
        <div class="row"><div class="label">Power Source:</div><div class="value"><?= $arduino['POWER'] ?? 'N/A' ?></div></div>
    </section>

    <section class="card">
        <h2>Storage Status</h2>
        <div class="row"><div class="label">HDD0:</div><div class="value"><?= ($arduino['HDD0'] ?? 0) == 1 ? "Present" : "Missing" ?></div></div>
        <div class="row"><div class="label">HDD1:</div><div class="value"><?= ($arduino['HDD1'] ?? 0) == 1 ? "Present" : "Missing" ?></div></div>
        <div class="row"><div class="label">HDD2:</div><div class="value"><?= ($arduino['HDD2'] ?? 0) == 1 ? "Present" : "Missing" ?></div></div>
        <div class="row"><div class="label">HDD3:</div><div class="value"><?= ($arduino['HDD3'] ?? 0) == 1 ? "Present" : "Missing" ?></div></div>
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
