@echo off
echo Starting Docker Compose for Booking Application...
echo.

echo Checking if Docker is running...
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo Docker is not running or not installed. Please start Docker Desktop.
    pause
    exit /b 1
)

echo Building and starting containers...
docker-compose up --build -d

echo.
echo Waiting for services to be ready...
timeout /t 10 /nobreak

echo.
echo Checking service status...
docker-compose ps

echo.
echo Application URLs:
echo Frontend: http://localhost:3000
echo Backend API: http://localhost:8100
echo.

echo Checking if services are healthy...
curl -s http://localhost:3000 >nul 2>&1
if %errorlevel% equ 0 (
    echo ✓ Frontend is accessible
) else (
    echo ✗ Frontend is not accessible yet
)

curl -s http://localhost:8100 >nul 2>&1
if %errorlevel% equ 0 (
    echo ✓ Backend is accessible
) else (
    echo ✗ Backend is not accessible yet
)

echo.
echo Docker containers are starting up...
echo You can monitor the logs with: docker-compose logs -f
echo To stop the application: docker-compose down
echo.
pause
