<?php
if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
    // Write to a temporary file instead of the serial port
    file_put_contents("/tmp/fan_mode", $mode);
    echo "OK";
}