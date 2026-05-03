<?php
// Sanitize input: Force to integer type
$speed = isset($_GET['speed']) ? intval($_GET['speed']) : 50;

// Validation: Range check (0-100%)
if ($speed < 0 || $speed > 100) {
    http_response_code(400);
    die("Out of Range");
}

file_put_contents('/tmp/fan_duty', $speed);
echo "OK";
?>