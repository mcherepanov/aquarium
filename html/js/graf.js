// functions for graf
window.onload = function () {
    getSchedule();
};

function getSchedule() {

    var jqxhr = $.get('get_schedule.php', '',
        function () {
            var schedule = JSON.parse((jqxhr.responseText));
            //console.log(jqxhr.responseText); // отладка
            var data = new Object();
            data.red = parse(0);
            data.blue = parse(1);
            data.magenta = parse(2);
            data.green = parse(3);
            data.orange = parse(4);
            data.yellow = parse(5);
            startChart(data);

            function parse(j) {
                var num = [];
                for (var i = 0; i < 71; i++) { // разбираем расписание по каналам
                    num[i] = schedule[i][j];
                }
                return num;
            }
        });
}

//------------ чарт ----------------
function startChart(data) {
    Highcharts.chart('chart', {
        // подсказка при наведении на точку
        tooltip: {
            formatter: function () {
                var hour = Math.floor(this.x / 3);
                var minute = (this.x - hour * 3) * 20;
                return 'Время = ' + hour + ' ч ' + minute + ' м, светимость =' + this.y;
            }
        },

        title: {
            text: 'Расписание работы морского аквариума'
        },

        subtitle: {
            text: 'Показано активное в данный момент расписание'
        },

        yAxis: {
            title: {
                text: 'Светимость каналов, в %%'
            }
        },
        xAxis: {
            type: 'datetime',
            labels: {
                formatter: function () {
                    var hour = Math.floor(this.value / 3);
                    var minute = (this.value - hour * 3) * 20;
                    return hour + ' ч ' + minute + ' м';
                }
            },
            title: {
                text: 'Время'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: 0
            }
        },

        series: [{
            name: 'Красный',
            color: 'red',
            data: data.red

        }, {
            name: 'Синий',
            color: 'blue',
            data: data.blue
        }, {
            name: 'Фиолетовый',
            color: 'magenta',
            data: data.magenta
        }, {
            name: 'Зеленый',
            color: 'green',
            data: data.green
        }, {
            name: 'Компрессор',
            color: 'gray',
            data: data.orange
        }, {
            name: 'Резерв',
            color: 'yellow',
            data: data.yellow
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 2000
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });
}
