// functions for index.php
setInterval(function () {
    let jqxhr = $.get('drivers.php', 'command=get_current',
        function () {
            // значения каналов для вывода в закрепленные области
            let current = JSON.parse((jqxhr.responseText));
            // console.log(current); // отладка
            for (var i = 0; i < current.length; i++) {
                var elem = document.getElementById('current_' + i);
                elem.value = current[i];
            }
            // время для вывода в окно
            /* время клиента - можно применять, но может отличаться от времени на сервере
            let h = new Date();
            // форматируем время с ведущими нулями и секундами
            let time = ('00' + h.getHours()).slice(-2) + ":" + ('00' + h.getMinutes()).slice(-2) + ":" + ('00' + h.getSeconds()).slice(-2);*/
            var output_time = document.getElementById('now');
            let get_time = $.get('drivers.php', 'command=get_time',
                function () {
                    output_time.value = (get_time.responseText);
                });



        });
}, 1000);

// включение драйверов
function onDrivers() {
    $.get('drivers.php', 'command=on');
}

// выключение драйверов
function offDrivers() {
    $.get('drivers.php', 'command=off');
}

// перезапуск устройства
function reboot() {
    let ok = confirm("Уверены? Устройство будет перезагружено.");
    if (ok) {
        $.get('drivers.php', 'command=reboot');
    }
}

// очистка таблицы в базе
function clearTable() {
    let ok = confirm("Уверены? Таблица будет стерта.");
    if (ok) {
        $.get('drivers.php', 'command=resettable');
        setTimeout(
            function () {
                window.location = "index.php"
            }, 1000
        );
    }
}