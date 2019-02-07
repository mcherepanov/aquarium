#!/bin/bash

/usr/sbin/i2cset -y 0 0x40 0x00 0x00
/bin/sleep 1
/usr/sbin/i2cset -y 0 0x40 0x00 0xa0
/bin/sleep 10
/usr/bin/php /var/www/html/crontab.php
/bin/sleep 5
/usr/local/bin/gpio mode 7 out && /usr/local/bin/gpio write 7 1
exit 0
