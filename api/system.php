<?php

$cpu_temp = trim(shell_exec("cat /sys/class/thermal/thermal_zone0/temp")) / 1000;
$cpu_load = trim(shell_exec("awk '{print $1*100}' /proc/loadavg"));
$uptime = trim(shell_exec("uptime -p"));
$ip = trim(shell_exec("hostname -I"));

return [
    "cpu_temp" => $cpu_temp,
    "cpu_load" => number_format($cpu_load, 1),
    "uptime" => $uptime,
    "ip" => $ip
];
