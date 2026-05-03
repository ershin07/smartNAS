<?php
// Set headers for a clean API response
header('Content-Type: text/plain');

$logdir = "/var/log/smartnas";
$today = date("Y-m-d"); // Sanitized by the system clock
$logfile = "$logdir/telemetry-$today.log";

// 1. Validate that the file actually resides in the intended directory
if (!file_exists($logfile) || strpos(realpath($logfile), $logdir) !== 0) {
    echo "ERROR=NOFILE";
    exit;
}

// 2. Escape the shell argument for safety
$escaped_log = escapeshellarg($logfile);
$lastLine = trim(shell_exec("tail -n 1 $escaped_log"));

if (empty($lastLine)) {
    echo "ERROR=EMPTY";
    exit;
}

// 3. Extract payload (Remove first 19 chars for timestamp)
$payload = trim(substr($lastLine, 19));

// 4. Final Output Sanitization
echo htmlspecialchars($payload, ENT_QUOTES, 'UTF-8');
?>