#!/bin/bash

echo "=== Booking System - Development Setup ==="
echo ""

# Check if we're in the right directory
if [ ! -f "docker-compose.yml" ]; then
    echo "Error: Please run this script from the project root directory"
    exit 1
fi

echo "Starting development servers..."

# Function to check if a command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check prerequisites
echo "Checking prerequisites..."
if ! command_exists php; then
    echo "❌ PHP is not installed or not in PATH"
    exit 1
fi

if ! command_exists npm; then
    echo "❌ Node.js/npm is not installed or not in PATH"
    exit 1
fi

echo "✅ Prerequisites check passed"

# Start backend
echo ""
echo "🚀 Starting Laravel backend on port 8100..."
cd "backend/laravel"

# Check if .env exists
if [ ! -f ".env" ]; then
    echo "Creating .env file from .env.docker..."
    cp .env.docker .env
fi

# Install dependencies if needed
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install
fi

# Generate app key if needed
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate
fi

# Start Laravel server in background
echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8100 &
BACKEND_PID=$!

cd ../..

# Start frontend
echo ""
echo "🚀 Starting Vue.js frontend on port 3000..."
cd "frontend"

# Install dependencies if needed
if [ ! -d "node_modules" ]; then
    echo "Installing npm dependencies..."
    npm install
fi

# Start Vue development server in background
echo "Starting Vue.js development server..."
npm run dev &
FRONTEND_PID=$!

cd ..

echo ""
echo "🎉 Development servers started successfully!"
echo ""
echo "📱 Frontend: http://localhost:3000"
echo "🔧 Backend API: http://localhost:8100"
echo ""
echo "Features available:"
echo "✅ QR Auto-refresh (every 2 minutes)"
echo "✅ KHQR Payment Integration"
echo "✅ Room Booking System"
echo "✅ User Authentication"
echo ""
echo "To stop the servers, press Ctrl+C"
echo ""

# Function to cleanup on exit
cleanup() {
    echo ""
    echo "Stopping development servers..."
    kill $BACKEND_PID 2>/dev/null
    kill $FRONTEND_PID 2>/dev/null
    echo "✅ Development servers stopped"
    exit 0
}

# Set trap to cleanup on script exit
trap cleanup INT TERM

# Wait for user to stop
echo "Press Ctrl+C to stop all servers..."
wait
