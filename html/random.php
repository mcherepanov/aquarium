<?php
// тестовый скрипт, записывает случайные значения в каналы
header('Pragma: no-cache');
// new classes
spl_autoload_register(function ($classname) {
    $path = 'inc' . DIRECTORY_SEPARATOR . $classname;
    if (file_exists($path . ".php")) require_once $path . ".php";
});
$db = new Db();

$db->newTableSchedules();
for ($i = 0; $i <= 72; $i++) {
    $data = array(rand(0, 99),rand(0, 99),rand(0, 99),rand(0, 99),rand(0, 99),rand(0, 99));
    $db->writeRowToTableSchedules($i, $data);
}

$table = $db->getTable('schedules');
?>
