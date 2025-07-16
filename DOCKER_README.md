# Docker Setup Guide for Booking System

## QR Auto-Refresh Feature ✅
The QR code payment system now includes **auto-refresh functionality**:
- QR codes automatically refresh every **2 minutes**
- Visual indicator shows "🔄 Auto-refreshes every 2 minutes"
- Prevents expired QR codes and improves user experience
- Automatic payment status checking continues in the background

## Prerequisites

1. **Docker Desktop** installed and running
2. **Git** for version control
3. **Port availability**: 3000 (Frontend), 8100 (Backend), 3306 (MySQL), 6379 (Redis)

## Quick Start

### Option 1: Using Batch Scripts (Windows)
```bash
# Start the application
./start-docker.bat

# Stop the application
./stop-docker.bat
```

### Option 2: Manual Docker Compose
```bash
# Build and start all services
docker-compose up --build -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f
```

## Services Overview

| Service | Port | Description |
|---------|------|-------------|
| Frontend | 3000 | Vue.js application with QR auto-refresh |
| Backend | 8100 | Laravel API with KHQR payment integration |
| MySQL | 3306 | Database server |
| Redis | 6379 | Caching and sessions |
| Nginx | 80 | Reverse proxy (internal) |

## Configuration

### 1. Backend Environment
Copy `.env.docker` to `.env` in the `backend/laravel` directory:
```bash
cp backend/laravel/.env.docker backend/laravel/.env
```

### 2. KHQR Payment Settings
Update these values in your `.env` file:
```env
KHQR_BASE_URL=https://api-bakong.nbc.gov.kh
KHQR_MERCHANT_ID=khun_meas@aclb
KHQR_ACCOUNT_ID=khun_meas@aclb
KHQR_CURRENCY=KHR
```

### 3. Database Configuration
The Docker setup automatically creates:
- Database: `booking_system`
- User: `booking_user`
- Password: `booking_password`

## Key Features

### 🔄 QR Auto-Refresh
- Automatically refreshes QR codes every 2 minutes
- Prevents payment failures due to expired codes
- Visual countdown indicator
- Seamless user experience

### 🔒 Security
- Nginx reverse proxy with security headers
- CORS configuration for frontend-backend communication
- Environment-based configuration
- SSL/TLS ready

### 📊 Performance
- Redis caching for sessions and data
- Gzip compression enabled
- Static asset optimization
- Database connection pooling

## Development Commands

### Backend (Laravel)
```bash
# Enter backend container
docker-compose exec backend bash

# Run migrations
php artisan migrate

# Clear cache
php artisan cache:clear

# Generate app key
php artisan key:generate
```

### Frontend (Vue.js)
```bash
# Enter frontend container
docker-compose exec frontend sh

# Install dependencies
npm install

# Run development server
npm run dev
```

### Database
```bash
# Enter MySQL container
docker-compose exec mysql mysql -u booking_user -p booking_system

# Backup database
docker-compose exec mysql mysqldump -u booking_user -p booking_system > backup.sql
```

## Troubleshooting

### Common Issues

1. **Port conflicts**
   ```bash
   # Check port usage
   netstat -an | findstr :3000
   netstat -an | findstr :8100
   ```

2. **Database connection issues**
   ```bash
   # Check MySQL logs
   docker-compose logs mysql
   
   # Restart MySQL service
   docker-compose restart mysql
   ```

3. **Frontend not loading**
   ```bash
   # Check frontend logs
   docker-compose logs frontend
   
   # Rebuild frontend
   docker-compose up --build frontend
   ```

4. **QR code not generating**
   - Check KHQR API configuration in `.env`
   - Verify network connectivity
   - Check backend logs for API errors

### Health Checks

```bash
# Check all services status
docker-compose ps

# Test frontend
curl http://localhost:3000

# Test backend API
curl http://localhost:8100/api/health

# Check database connection
docker-compose exec backend php artisan tinker
```

## Production Deployment

### Environment Variables
Update `.env` with production values:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_PASSWORD=secure_password_here
```

### SSL Configuration
Add SSL certificates to nginx configuration:
```nginx
server {
    listen 443 ssl;
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/private.key;
    # ... rest of configuration
}
```

## Monitoring

### Logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f backend
docker-compose logs -f frontend
```

### Performance
```bash
# Container stats
docker stats

# Service health
docker-compose ps
```

## Backup & Restore

### Database Backup
```bash
# Create backup
docker-compose exec mysql mysqldump -u booking_user -p booking_system > backup_$(date +%Y%m%d).sql

# Restore backup
docker-compose exec -T mysql mysql -u booking_user -p booking_system < backup.sql
```

### File Backup
```bash
# Backup uploads
docker cp booking_ip_backend_1:/var/www/html/storage/app/public ./backups/uploads

# Restore uploads
docker cp ./backups/uploads booking_ip_backend_1:/var/www/html/storage/app/public
```

## Support

For issues or questions:
1. Check the logs first: `docker-compose logs -f`
2. Verify environment configuration
3. Test individual services
4. Check network connectivity

## Features Implemented ✅

- [x] QR Auto-refresh (2-minute intervals)
- [x] Docker containerization
- [x] Multi-service orchestration
- [x] Redis caching
- [x] Nginx reverse proxy
- [x] Environment configuration
- [x] Health checks
- [x] Security headers
- [x] Static asset optimization
- [x] Database migrations
- [x] Backup scripts
- [x] Monitoring setup
