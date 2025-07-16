@echo off
echo === Booking System - Development Setup ===
echo.

echo Checking prerequisites...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP is not installed or not in PATH
    pause
    exit /b 1
)

node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Node.js is not installed or not in PATH
    pause
    exit /b 1
)

echo ✅ Prerequisites check passed
echo.

echo 🚀 Starting Laravel backend on port 8100...
cd "backend\laravel"

REM Check if .env exists
if not exist ".env" (
    echo Creating .env file from .env.docker...
    copy .env.docker .env
)

REM Install dependencies if needed
if not exist "vendor" (
    echo Installing Composer dependencies...
    composer install
)

REM Generate app key if needed
findstr /C:"APP_KEY=base64:" .env >nul 2>&1
if %errorlevel% neq 0 (
    echo Generating application key...
    php artisan key:generate
)

REM Start Laravel server in background
echo Starting Laravel server...
start /min "Laravel Backend" php artisan serve --host=0.0.0.0 --port=8100

cd ..\..

echo.
echo 🚀 Starting Vue.js frontend on port 3000...
cd "frontend"

REM Install dependencies if needed
if not exist "node_modules" (
    echo Installing npm dependencies...
    npm install
)

REM Start Vue development server in background
echo Starting Vue.js development server...
start /min "Vue Frontend" npm run dev

cd ..

echo.
echo 🎉 Development servers started successfully!
echo.
echo 📱 Frontend: http://localhost:3000
echo 🔧 Backend API: http://localhost:8100
echo.
echo Features available:
echo ✅ QR Auto-refresh (every 2 minutes)
echo ✅ KHQR Payment Integration
echo ✅ Room Booking System
echo ✅ User Authentication
echo.
echo Both servers are running in separate windows.
echo Close the terminal windows to stop the servers.
echo.
pause
