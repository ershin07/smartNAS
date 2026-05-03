<?php
// api/fan.php

if (!isset($_GET['speed'])) {
    echo "Missing speed";
    exit;
}

$speed = intval($_GET['speed']);
if ($speed < 0) $speed = 0;
if ($speed > 100) $speed = 100;

// Format EXACTLY as Arduino expects
$cmd = "DUTY:" . $speed . "%\n";

// Send to Arduino serial port
file_put_contents("/dev/ttyACM0", $cmd);

echo "OK";
