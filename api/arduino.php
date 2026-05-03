<?php

$logdir = "/var/log/smartnas";
$today = date("Y-m-d");
$logfile = "$logdir/telemetry-$today.log";

if (!file_exists($logfile)) {
    echo "ERROR=NOFILE";
    exit;
}

$lastLine = trim(shell_exec("tail -n 1 $logfile"));

// Remove timestamp (first 19 chars)
$payload = trim(substr($lastLine, 19));

// Output the raw key=value;key=value string
echo $payload;
