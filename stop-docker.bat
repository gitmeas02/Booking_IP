@echo off
echo Stopping Docker Compose services...
echo.

docker-compose down

echo.
echo Removing unused Docker images and containers...
docker system prune -f

echo.
echo All services have been stopped and cleaned up.
echo.
pause
