<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 31.01.19
 * Time: 10:28
 */

class Db
{
    //TODO все значения переменных, передаваемые в запрос, не забывать заключать в кавычки: WHERE $params[3] = '$params[4]'
    function __construct()
    {
        //parent::__construct;
        $this->host = 'localhost';
        $this->database = 'akvarium';
        $this->user = 'setupini';
        $this->password = 'cable7flair';
        $this->opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $this->error_answer = array(['error' => true]);
        $this->ok_answer = array(['status' => 'ok']);
        $this->connect = $this->getConnect();
    }

    public function getTable($table, $permission = true) // выдает ассоциативный массив не в json
    {
        $stmt = $this->execQuery("SELECT * FROM " . $table);
        $return = [];
        while ($row = $stmt->fetch()) $return[] = $row;
        return $return;
    }

    public function writeTableSchedules($array)
    {
        foreach ($array as $id => $data) {
            $this->writeRowToTableSchedules($id, $data);
        }
    }

    public function writeRowToTableSchedules($id, $data)
    {
        $query = "UPDATE `schedules` 
        SET `channel_0_` = '$data[0]', `channel_1_` = '$data[1]', `channel_2_` = '$data[2]', `channel_3_` = '$data[3]', `channel_4_` = '$data[4]', `channel_5_` = '$data[5]' 
        WHERE `id_` = '$id'";
        $this->execQuery($query);

    }

    public function newTableSchedules()
    {
        $this->dropTableSchedules();
        $this->createTableSchedules();
        $this->fillTableSchedules(71);
    }

    // внутренние функции класса
    // =========================
    private function getConnect() // устанавливает соединение с базой
    {
        try {
            $db = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->database, $this->user, $this->password, $this->opt);
        } catch (PDOException $e) {
            echo "error connect";
        }
        return $db;
    }

    // =-=-=-=-=-=-= блок создания новой таблицы =-=-=-=-=-
    private function dropTableSchedules()
    {
        $query = "DROP TABLE IF EXISTS `schedules`";
        $this->execQuery($query);
    }

    private function createTableSchedules()
    {
        $query = "CREATE TABLE `schedules` (
        `id_` int(3) NOT NULL AUTO_INCREMENT,
  `time_` time NOT NULL,
  `channel_0_` int(10) NOT NULL COMMENT 'красный',
  `channel_1_` int(10) NOT NULL COMMENT 'синий',
  `channel_2_` int(10) NOT NULL COMMENT 'фиолетовый',
  `channel_3_` int(10) NOT NULL COMMENT 'зеленый',
  `channel_4_` int(10) NOT NULL COMMENT 'компрессор',
  `channel_5_` int(10) NOT NULL COMMENT 'резерв',
  PRIMARY KEY (`id_`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->execQuery($query);

    }

    private function fillTableSchedules($num)
    {
        $hour = 0;
        $minute = 0;
        for ($i = 0; $i <= $num; $i++) {
            $time = $hour . ":" . $minute . ":00";
            $query = "INSERT INTO `schedules` (`time_`, `channel_0_`, `channel_1_`, `channel_2_`, `channel_3_`, `channel_4_`, `channel_5_`) VALUES ('$time', 0, 0, 0, 0, 0, 0)";
            $this->execQuery($query);
            $minute = $minute + 20;
            if ($minute == 60) {
                $hour++;
                $minute = 0;
            }
        }
    }

    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=


    protected function execQuery($query) // выполняет собственно запрос
    {
        $stmt = $this->connect->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}