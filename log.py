#!/usr/bin/env python3

import socket
import threading
import argparse
import time
import random
import urllib.request
import ssl
import sys

CUSTOM_HEADERS = {}

# =====================
# CLI
# =====================
parser = argparse.ArgumentParser(description=" Unified Test Engine")

parser.add_argument("-mode", required=True,
                    choices=["http", "https", "tcp", "udp"])

parser.add_argument("-host", required=True)

parser.add_argument("-path", default="/",
                    help="Target path for HTTP/HTTPS (default: /)")

parser.add_argument("-header-file",
                    help="Load custom headers from file")

parser.add_argument("-port", type=int)

parser.add_argument("-threads", type=int, default=100)

parser.add_argument("-duration", type=int, default=30)

parser.add_argument("-size", type=int, default=512)

args = parser.parse_args()

MODE = args.mode
HOST = args.host
PATH = args.path
HEADER_FILE = args.header_file
PORT = args.port
THREADS = args.threads
DURATION = args.duration
PAYLOAD = b"A" * args.size

running = True
success = 0
failed = 0
total = 0
latencies = []

lock = threading.Lock()

# =====================
# STANDARD CLIENT PROFILES
# =====================
USER_AGENTS = [
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)",
    "Mozilla/5.0 (X11; Linux x86_64)",
    "Mozilla/5.0 (Android 14; Mobile)",
    "Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)"
]

def load_custom_headers():

    global CUSTOM_HEADERS

    if not HEADER_FILE:
        return

    try:

        with open(HEADER_FILE, "r") as f:

            for line in f:

                line = line.strip()

                if ":" in line:

                    key, value = line.split(":", 1)

                    CUSTOM_HEADERS[key.strip()] = value.strip()

        print("[+] Loaded custom headers:", len(CUSTOM_HEADERS))

    except Exception as e:

        print("[!] Failed loading header file:", e)

def get_headers():

    headers = {
        "User-Agent": random.choice(USER_AGENTS),
        "Accept": "text/html,application/xhtml+xml",
        "Accept-Language": "en-US,en;q=0.9",
        "Connection": "keep-alive"
    }

    # merge file headers
    headers.update(CUSTOM_HEADERS)

    return headers

# =====================
# HTTP / HTTPS
# =====================

def http_worker():

    global success, failed, total

    proto = "https" if MODE == "https" else "http"

    while running:

        try:
            # random query (cache busting)
            rand_id = random.randint(100000, 999999)

            # normalize path
            clean_path = PATH if PATH.startswith("/") else "/" + PATH

            # build URL
            url = f"{proto}://{HOST}{clean_path}?{rand_id}"

            req = urllib.request.Request(
                url,
                headers=get_headers()
            )

            ctx = ssl._create_unverified_context()

            start = time.time()

            with urllib.request.urlopen(
                req,
                timeout=5,
                context=ctx
            ) as res:

                code = res.getcode()

            latency = time.time() - start

            with lock:
                total += 1

                if 200 <= code < 400:
                    success += 1
                else:
                    failed += 1

                latencies.append(latency)

        except:

            with lock:
                total += 1
                failed += 1

        # tiny random delay
        time.sleep(random.uniform(0.02, 0.08))



# =====================
# TCP
# =====================
def tcp_worker():

    global success, failed, total

    while running:

        try:
            s = socket.socket(socket.AF_INET,
                              socket.SOCK_STREAM)

            s.settimeout(3)

            start = time.time()

            s.connect((HOST, PORT))

            s.sendall(PAYLOAD)

            s.close()

            latency = time.time() - start

            with lock:
                total += 1
                success += 1
                latencies.append(latency)

        except:

            with lock:
                total += 1
                failed += 1


# =====================
# UDP
# =====================
def udp_worker():

    global success, failed, total

    while running:

        try:
            s = socket.socket(socket.AF_INET,
                              socket.SOCK_DGRAM)

            s.sendto(PAYLOAD,
                     (HOST, PORT))

            s.close()

            with lock:
                total += 1
                success += 1

        except:

            with lock:
                total += 1
                failed += 1


# =====================
# STATS
# =====================
def monitor():

    global running

    start = time.time()
    prev = 0

    while time.time() - start < DURATION:

        time.sleep(1)

        with lock:

            rate = total - prev
            prev = total

            avg = 0

            if latencies:
                avg = sum(latencies[-100:]) / min(len(latencies), 100)

            print(
                f"[STATS] "
                f"ok={success} "
                f"fail={failed} "
                f"total={total} "
                f"avg={avg:.3f}s "
                f"rate={rate}/sec"
            )

    running = False


# =====================
# MAIN
# =====================
def main():

    load_custom_headers()

    print("===================================")
    print(" Unified Test Engine")
    print("===================================")

    print("Mode     :", MODE)
    print("Host     :", HOST)

    if MODE in ["http", "https"]:
        print("Path     :", PATH)

    if PORT:
        print("Port     :", PORT)

    print("Threads  :", THREADS)
    print("Duration :", DURATION)

    print("===================================")

    if MODE in ["http", "https"]:
        worker = http_worker

    elif MODE == "tcp":

        if not PORT:
            print("TCP mode requires -port")
            sys.exit(1)

        worker = tcp_worker

    elif MODE == "udp":

        if not PORT:
            print("UDP mode requires -port")
            sys.exit(1)

        worker = udp_worker

    threads = []

    for _ in range(THREADS):

        t = threading.Thread(target=worker)
        t.daemon = True
        t.start()

        threads.append(t)

    monitor()

    print("\nFinished")
    print("Success :", success)
    print("Failed  :", failed)
    print("Total   :", total)


if __name__ == "__main__":
    main()
