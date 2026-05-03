<?php
if (isset($_GET['speed'])) {
    $speed = intval($_GET['speed']);
    file_put_contents("/tmp/fan_duty", $speed);
    echo "OK";
}