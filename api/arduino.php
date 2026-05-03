<?php

$logdir = "/var/log/smartnas";
$today = date("Y-m-d");
$logfile = "$logdir/telemetry-$today.log";

if (!file_exists($logfile)) {
    return ["error" => "No telemetry file for today"];
}

$lastLine = trim(shell_exec("tail -n 1 $logfile"));

// Remove timestamp (first 19 chars)
$payload = trim(substr($lastLine, 19));

// Split into key=value pairs
$parts = explode(";", $payload);
$data = [];

foreach ($parts as $p) {
    if (strpos($p, "=") !== false) {
        list($key, $value) = explode("=", $p, 2);
        $data[$key] = $value;
    }
}

return $data;
