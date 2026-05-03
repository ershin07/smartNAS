<?php
if (!isset($_GET['mode'])) {
    echo "Missing mode";
    exit;
}

$mode = $_GET['mode'];
if ($mode !== "AUTO" && $mode !== "MANUAL") {
    echo "Invalid mode";
    exit;
}

$device = "/dev/ttyACM0";
// Set baud rate to 9600 and set raw mode to prevent data mangling
exec("stty -F $device 9600 raw -echo");

$cmd = "MODE:" . $mode . ";\n";

$fp = fopen($device, "w");
if ($fp) {
    fwrite($fp, $cmd);
    fclose($fp);
    echo "OK";
} else {
    echo "Error: Device busy or permissions denied.";
}