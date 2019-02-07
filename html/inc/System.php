<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 07.02.19
 * Time: 9:24
 */

class System
{
    // константы для конкретного случая
    const NUM_CHANNELS = 6; // количество используемых каналов в реализации
    const DRIVER_PIN = '7'; // место, куда подключено реле драйверов
    const SCRIPT_9685 = '/var/www/html/9685.sh'; // место, где находится скрипт управления ШИМ-контроллером
    const DIVIDER = 40.96; // делитель для приведения к 100% того, что находится в регистрах управления драйвером ШИМ: 4096 = 100%

    public static function getTime()
    {
        $hour = date('G'); // часы
        $minute = date('i'); // минуты
        $second = date('s'); // секунды
        return $hour . ":" . $minute . ":" . $second;
    }

    public static function toggleAquariumDrivers($command = false)
    {
        switch ($command) {
            case 'on':
                exec("/usr/local/bin/gpio write " . self::DRIVER_PIN . " 1");
                break;
            case 'off':
                exec("/usr/local/bin/gpio write " . self::DRIVER_PIN . " 0");
                break;
            default:
                break;
        }
    }

    public static function rebootSystem()
    {
        self::toggleAquariumDrivers('off');
        exec("sudo /sbin/reboot");
    }

    public static function setAquariumChannel($channel, $value)
    {
        $command = 'sudo ' . self::SCRIPT_9685 . ' pwm ' . $channel . ' ' . $value . ' 2>&1';
        (`$command`);
    }

    public static function getAquariumCurrentArray(){
        $current_array = [];
        for ($i = 0; $i < self::NUM_CHANNELS; $i++) {
            $current_array[$i]= self::getAquariumChannel($i);
        }
        return $current_array;
    }

    private static function getAquariumChannel($channel)
    {
        $command = 'sudo ' . self::SCRIPT_9685 . ' get ' . $channel;
        return (int)((`$command`) / self::DIVIDER);
    }
}