<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 01.02.19
 * Time: 15:59
 */

$file_schedule = '/home/setupini/schedule';
$count_channels = 6;

$schedule = [];
for ($i = 0; $i < 72; $i++) {
    for ($j = 1; $j <= 6; $j++) {
        $schedule[$i][$j] = 0;
    }
}
file_put_contents($file_schedule, serialize($schedule));
