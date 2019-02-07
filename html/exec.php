<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 01.02.19
 * Time: 15:22
 */

spl_autoload_register(function ($classname) {
    $path = 'inc' . DIRECTORY_SEPARATOR . $classname;
    if (file_exists($path . ".php")) require_once $path . ".php";
});
require_once '/var/www/html/inc/Db.php';
$db = new Db();

//$reverse = 100; // если ШИМ реверсирован в драйверах
$reverse = 0; // если ШИМ не реверсирован в драйверах
$hour = date('G'); // часы

$minute = date('i'); // минуты
echo 'Текущее время: ' . $hour . ":" . $minute . "<br>";
//echo ' Через класс ' . System::getTime() . "<br>";
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

$table = $db->getTable('schedules');

if ($hour + $min < 71) {
    $last_t = $table[$hour * 3 + $min];
    $next_t = $table[$hour * 3 + $min + 1];
} else {
    $last_t = $table[71];
    $next_t = $table[0];
}

$last = [];
foreach ($last_t as $key => $value) if ($key != "id_" && $key != "time_") $last[] = $value;
$next = [];
foreach ($next_t as $key => $value) if ($key != "id_" && $key != "time_") $next[] = $value;
$current = [];
echo "Расчетные данные:<br>";
foreach ($last as $k => $l) {
    $current[$k] = (int)(round($l + ($next[$k] - $l) / 20 * $delta_min));
    echo 'Канал ' . $k . ' Предыдущее: ' . $l . ' Следующее: ' . $next[$k] . ' Текущее значение: ' . $current[$k] . ', минут с последней точки: ' . $delta_min;
    //echo $l + ($l - $next[$k]) / 20 * $delta_min;
    //echo $current[$k];
    echo '<br>';

}

var_dump($last);
echo '<br>';
var_dump($next);
echo '<br>';
var_dump($current);
echo '<br>';
echo "Фактические данные:<br>";
for ($i = 0; $i < 6; $i++) {
    echo "Канал $i " . (`sudo /home/setupini/9685.sh get $i`) . '<br>';
}
if (isset($current[0])) (`sudo /home/setupini/9685.sh pwm 0 $reverse-$current[0] 2>&1`);
if (isset($current[1])) (`sudo /home/setupini/9685.sh pwm 1 $reverse-$current[1] 2>&1`);
if (isset($current[2])) (`sudo /home/setupini/9685.sh pwm 2 $reverse-$current[2] 2>&1`);
if (isset($current[3])) (`sudo /home/setupini/9685.sh pwm 3 $reverse-$current[3] 2>&1`);
if (isset($current[4])) (`sudo /home/setupini/9685.sh pwm 4 $reverse-$current[4] 2>&1`);
if (isset($current[5])) (`sudo /home/setupini/9685.sh pwm 5 $reverse-$current[5] 2>&1`);