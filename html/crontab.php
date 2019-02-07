<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 07.02.19
 * Time: 10:50
 файл, который будет запускаться через crontab
 выполнить в консоли crontab -e от root, и внести строку:
 * * * * * /usr/bin/php /var/www/html/crontab.php
 */

// внимание! скрипт написан хардкорно в расчете на 72 опорные точки в сутки через каждые 20 минут
// в случае изменения надо изменять все константы

// классы подключать только так
require_once '/var/www/html/inc/Db.php'; // база данных
require_once '/var/www/html/inc/System.php'; // команды и константы
$db = new Db();
$table = $db->getTable('schedules');
// вычисляем интервал
$hour = date('G'); // часы
$minute = date('i'); // минуты
if ($minute < 20) {
    $min = 0;
    $delta_min = $minute;
} elseif ($minute < 40) {
    $min = 1;
    $delta_min = $minute - 20;
} else {
    $min = 2;
    $delta_min = $minute - 40;
}

// учтем попадание в период 00:00
if ($hour + $min < 71) {
    $last_t = $table[$hour * 3 + $min];
    $next_t = $table[$hour * 3 + $min + 1];
} else {
    $last_t = $table[71];
    $next_t = $table[0];
}

$last = []; // массив с предыдущей точкой
foreach ($last_t as $key => $value) {
    if ($key != "id_" && $key != "time_") $last[] = $value;
}
$next = []; // массив со следующей точкой
foreach ($next_t as $key => $value) {
    if ($key != "id_" && $key != "time_") $next[] = $value;
}
// расчетные данные на настоящий момент
foreach ($last as $k => $l) {
    $current = (int)(round($l + ($next[$k] - $l) / 20 * $delta_min));
    System::setAquariumChannel($k,$current);
}