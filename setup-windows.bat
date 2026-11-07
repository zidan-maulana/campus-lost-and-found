@echo off
REM ===================================================================
REM  Setup lingkungan Campus Lost & Found (FindUMB) - Windows
REM  Klik 2x file ini, atau jalankan dari CMD.
REM  Akan minta izin Administrator otomatis.
REM ===================================================================

echo.
echo ===============================================
echo   SETUP LINGKUNGAN FINDUMB
echo ===============================================
echo.

REM Cek hak administrator
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo [!] Meminta hak Administrator...
    powershell -NoProfile -Command "Start-Process -FilePath '%~f0' -Verb RunAs"
    exit /b
)

powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0setup-windows.ps1"

echo.
echo ===============================================
echo   SELESAI - tekan tombol apa saja untuk keluar
echo ===============================================
pause >nul
