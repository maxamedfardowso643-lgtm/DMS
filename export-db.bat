@echo off
REM Regenerates database\dcatms.sql from the live "dcatms" MySQL database (XAMPP).
REM Run this any time you want to update the backup file with the latest data.

"C:\xampp\mysql\bin\mysqldump.exe" -u root --routines --triggers dcatms > "%~dp0database\dcatms.sql"

echo Done. database\dcatms.sql updated.
pause
