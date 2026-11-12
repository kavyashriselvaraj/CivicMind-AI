@echo off
title CivicMind X - Server Launcher
echo ====================================================
echo Starting CivicMind X Local Development Server...
echo ====================================================
echo.
echo Server running at: http://127.0.0.1:8000
echo.
"C:\xampp\php\php.exe" -S 127.0.0.1:8000 -t public public/router.php
pause
