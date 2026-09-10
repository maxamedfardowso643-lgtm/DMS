@echo off
REM Starts the DCATMS system: makes sure XAMPP MySQL is running, then
REM launches the Laravel dev server and opens the app in your browser.

echo Checking MySQL (XAMPP)...
tasklist /FI "IMAGENAME eq mysqld.exe" | find /I "mysqld.exe" >nul
if errorlevel 1 (
    echo Starting MySQL...
    start "" "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini"
    timeout /t 3 >nul
) else (
    echo MySQL is already running.
)

echo Starting the DCATMS server...
cd /d "%~dp0"
start "DCATMS Server" cmd /k php artisan serve

timeout /t 2 >nul
start http://127.0.0.1:8000

echo Done. The system is running at http://127.0.0.1:8000
echo (Leave the "DCATMS Server" window open while you use the system.)
pause
