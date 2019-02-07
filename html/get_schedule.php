<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 01.02.19
 * Time: 16:16
 * отдаем в json всю таблицу для графика
 */

header('Pragma: no-cache');
spl_autoload_register(function ($classname) {
    $path = 'inc' . DIRECTORY_SEPARATOR . $classname;
    if (file_exists($path . ".php")) require_once $path . ".php";
});
$db = new Db();
$table = $db->getTable('schedules');
$schedule = [];
$j = 0;
foreach ($table as $row) {
    $i = 0;
    foreach ($row as $key => $value) {
        if ($key != 'id_' && $key != 'time_') {
           $schedule[$j][$i] = $value;
           $i++;
        }
    }
    $j++;
}
echo json_encode($schedule);