<?php
spl_autoload_register(function ($classname) {
    $path = 'inc' . DIRECTORY_SEPARATOR . $classname;
    if (file_exists($path . ".php")) require_once $path . ".php";
});
if (isset($_GET['command'])) {
    switch ($_GET['command']) {
        case 'on':
        case 'off':
            System::toggleAquariumDrivers($_GET['command']);
            break;
        case 'reboot':
            System::rebootSystem();
            break;
        case 'get_current':
            echo json_encode(System::getAquariumCurrentArray());
            break;
        case 'resettable':
            $db = new Db();
            $db->newTableSchedules();
            for ($i = 0; $i <= 72; $i++) {
                $data = array(0, 0, 0, 0, 0, 0);
                $db->writeRowToTableSchedules($i, $data);
            }
            include 'crontab.php';
            break;
        case 'get_time':
            echo System::getTime();
            break;
        default:
            // возможно, надо что-то вернуть типа echo json_encode(['error' => true]);
            break;
    }
}

