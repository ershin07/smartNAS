<?php
if (!isset($_GET['speed'])) {
    echo "Missing speed";
    exit;
}

$speed = intval($_GET['speed']);
$speed = max(0, min(100, $speed)); 

$device = "/dev/ttyACM0";
exec("stty -F $device 9600 raw -echo");

$cmd = "DUTY:" . $speed . ";\n";

$fp = fopen($device, "w");
if ($fp) {
    fwrite($fp, $cmd);
    fclose($fp);
    echo "OK";
} else {
    echo "Error: Device busy.";
}