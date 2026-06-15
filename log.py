#!/usr/bin/env python3

import socket
import threading
import argparse
import time
import sys

# =========================
# ARGUMENTS
# =========================
parser = argparse.ArgumentParser(description="Simple TCP/UDP Service Stress Tester")

parser.add_argument("-protocol", required=True, choices=["tcp", "udp"],
                    help="Protocol: tcp or udp")

parser.add_argument("-host", required=True,
                    help="Target host/IP")

parser.add_argument("-port", required=True, type=int,
                    help="Target port")

parser.add_argument("-threads", type=int, default=100,
                    help="Number of worker threads (default: 100)")

parser.add_argument("-duration", type=int, default=30,
                    help="Test duration in seconds (default: 30)")

parser.add_argument("-size", type=int, default=512,
                    help="Payload size in bytes (default: 512)")

args = parser.parse_args()

PROTOCOL = args.protocol.lower()
HOST = args.host
PORT = args.port
THREADS = args.threads
DURATION = args.duration
PAYLOAD = b"A" * args.size

# =========================
# GLOBAL STATS
# =========================
running = True
sent = 0
success = 0
failed = 0
lock = threading.Lock()


# =========================
# TCP WORKER
# =========================
def tcp_worker():
    global sent, success, failed

    while running:
        try:
            sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            sock.settimeout(3)

            sock.connect((HOST, PORT))
            sock.sendall(PAYLOAD)

            try:
                sock.recv(1024)
            except:
                pass

            sock.close()

            with lock:
                sent += 1
                success += 1

        except:
            with lock:
                sent += 1
                failed += 1


# =========================
# UDP WORKER
# =========================
def udp_worker():
    global sent, success, failed

    while running:
        try:
            sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)

            sock.sendto(PAYLOAD, (HOST, PORT))
            sock.close()

            with lock:
                sent += 1
                success += 1

        except:
            with lock:
                sent += 1
                failed += 1


# =========================
# MONITOR
# =========================
def monitor():
    global running

    start = time.time()
    last_sent = 0

    while time.time() - start < DURATION:
        time.sleep(1)

        with lock:
            current_sent = sent
            rate = current_sent - last_sent
            last_sent = current_sent

            print(
                f"[STATS] success={success} "
                f"failed={failed} "
                f"total={sent} "
                f"rate={rate}/sec"
            )

    running = False


# =========================
# MAIN
# =========================
def main():
    print("======================================")
    print(" Simple Network Stress Tester")
    print("======================================")
    print(f" Protocol : {PROTOCOL.upper()}")
    print(f" Host     : {HOST}")
    print(f" Port     : {PORT}")
    print(f" Threads  : {THREADS}")
    print(f" Duration : {DURATION}s")
    print("======================================")

    worker = tcp_worker if PROTOCOL == "tcp" else udp_worker

    threads = []

    for _ in range(THREADS):
        t = threading.Thread(target=worker)
        t.daemon = True
        t.start()
        threads.append(t)

    monitor()

    for t in threads:
        t.join(timeout=1)

    print("\nFinished")
    print(f"Success : {success}")
    print(f"Failed  : {failed}")
    print(f"Total   : {sent}")


if __name__ == "__main__":
    try:
        main()
    except KeyboardInterrupt:
        print("\nInterrupted")
        sys.exit(0)
