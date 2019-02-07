<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Аквариум - таблица</title>
    <link rel="stylesheet" href="css/aquarium.css">
    <script src="js/jquery.js"></script>
    <script src="js/index.js"></script>
</head>
<body>
<?php
header('Pragma: no-cache');
spl_autoload_register(function ($classname) {
    $path = 'inc' . DIRECTORY_SEPARATOR . $classname;
    if (file_exists($path . ".php")) require_once $path . ".php";
});
$db = new Db();

if (isset($_POST)) { // нажата кнопка Запись
    // собираем промежуточный построчный массив из $_POST
    $array = [];
    foreach ($_POST as $key => $value) {
        // собираем с учетом разделителя, который применяли в поле name формы
        $array[explode('^', $key)[1]][explode('^', $key)[0]] = $value;
    }
    // построчно записываем массив в базу
    foreach ($array as $key => $row) {
        $data = [];
        foreach ($row as $value) $data[] = $value;
        $db->writeRowToTableSchedules($key + 1, $data);
    };
    // запуск того, что написали в базу сразу же
    include 'crontab.php';
    unset($_POST);
}

$table = $db->getTable('schedules');
?>
<header>
    <table class="table_dark">
        <?php
        $tr = '';
        // заголовок
        $columns = ["Время", "Красный", "Синий", "Фиолетовый", "Зеленый", "Компрессор", "Резерв"];
        foreach ($columns as $column) {
            $params = ['class' => 'input_red', 'disabled' => '', 'value' => $column, 'size' => '8'];
            $tr .= Html::shellTd(Html::echoInput('text', $params));
        }
        // место вывода текущего времени сервера через js
        echo Html::shellTr($tr);
        $tr = '';
        $params = ['id' => 'now', 'align' => 'center', 'class' => 'input_blue', 'disabled' => '', 'size' => '8'];
        $tr .= Html::shellTd(Html::echoInput('text', $params));
        // вывод текущих значений каналов
        for ($i = 0; $i < 6; $i++) {
            $params = ['id' => 'current_' . $i, 'align' => 'center', 'class' => 'input_blue', 'disabled' => '', 'size' => '8'];
            $tr .= Html::shellTd(Html::echoInput('text', $params));
        }
        echo Html::shellTr($tr); ?>
    </table>
</header>
<div id="table">
    <div>
        <section>
            <form id="main" method="post">
                <table id="main_table" class="table_dark">
                    <?php for ($i = 0; $i < 6; $i++) echo Html::shellTr(Html::shellTd(''));
                    foreach ($table as $key => $rows) {
                        $tr = '';
                        foreach ($rows as $k => $row) {
                            switch ($k) {
                                case 'id_':
                                    break;
                                case 'time_':
                                    $params = ['class' => 'input_red', 'disabled' => '', 'value' => $row, 'size' => '8'];
                                    $tr .= Html::shellTd(Html::echoInput('text', $params));
                                    break;
                                default:
                                    // внимание на сборку поля name - разделитель "^" потом используется выше, при разборе массива $_POST
                                    $params = ['class' => 'input', 'name' => $k . '^' . $key, 'value' => $row, 'size' => '1', 'min' => '0', 'max' => '99', 'step' => '1'];
                                    $tr .= Html::shellTd(Html::echoInput('number', $params));
                            }
                        }
                        echo Html::shellTr($tr);
                    }
                    for ($i = 0; $i < 4; $i++) echo Html::shellTr(Html::shellTd(''));
                    ?>
                </table>
            </form>
        </section>
    </div>
</div>
<footer>
    <table class="table_dark">
        <tr align="center">
            <th>
                <button style="color: darkred; background: yellowgreen;" type="submit" form="main">Записать</button>
            </th>
            <th>
                <a class="input_red" href="graf.html">Графики</a>
            </th>
            <th>
                <input type="button" value="Вкл. драйверы" form="main"
                       onclick="onDrivers();">
            </th>
            <th>
                <input type="button" value="Выкл. драйверы" form="main"
                       onclick="offDrivers();">
            </th>
            <th>
                <input type="button" style="color: red" value="Очистить"
                       form="main" onclick="clearTable();">
            </th>
            <th>
                <input type="button" style="color: red"
                       value="Перезагрузка" form="main"
                       onclick="reboot();">
            </th>

        </tr>
    </table>
</footer>
</body>
</html>
