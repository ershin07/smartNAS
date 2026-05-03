<?php
// Sanitize input: Strip tags and whitespace
$mode = isset($_GET['mode']) ? strtoupper(trim(strip_tags($_GET['mode']))) : 'AUTO';

// Validation: "Allow-list" check
$valid_modes = ['AUTO', 'MANUAL'];
if (!in_array($mode, $valid_modes)) {
    http_response_code(400);
    die("Invalid Mode Signal");
}

file_put_contents('/tmp/fan_mode', $mode);
echo "OK";
?>