#!/bin/bash
#set -x
touch /tmp/control

case $1 in
        start)
        i2cset -y 0 0x40 0x00 0x00
        sleep 1
        i2cset -y 0 0x40 0x00 0xa0
        ;;
        freq)
        FREQ=$((25000000/($2+1)/4096))
        i2cset -y 0 0x40 0x00 0xb0
        i2cset -y 0 0x40 0xfe $FREQ
        i2cset -y 0 0x40 0x00 0xa0
        ;;
        pwm)
        PIN=$2
	# VALUE=$3 # not invert pwm
	VALUE=$((100-$3)) # invert PWM
        i2cset -y 0 0x40 $(($PIN*4+6)) 0x00
        i2cset -y 0 0x40 $(($PIN*4+7)) 0x00
        i2cset -y 0 0x40 $(($PIN*4+8)) $(($VALUE*40%256))
        i2cset -y 0 0x40 $(($PIN*4+9)) $(($VALUE*40/256))
        ;;
	get)
	PIN=$2
	VALUE=$((256*$((16#`i2cget -y 0 0x40 $(($PIN*4+9)) | cut -c 3-4`))+$((16#`i2cget -y 0 0x40 $(($PIN*4+8)) | cut -c 3-4`))))
	VALUE=$((4000-$VALUE)) # invert PWM
	echo "$VALUE"
	;;
        *)
        exit 1
        ;;
esac
exit 0
