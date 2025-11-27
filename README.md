# Backend_IP
# Frontend_IP

## Setup

1. Start the services: `docker compose up -d --build` (in backend directory)
2. Run migrations: `docker exec app_hotel php artisan migrate`
3. (Optional) Seed the database: `docker exec app_hotel php artisan db:seed`

## Accessing Services

- **Laravel App**: http://localhost:8102
- **MinIO Console**: http://localhost:9012 (username: admin, password: password123)
- **MySQL**: localhost:3377

## Clearing Cache

If you need to manually clear Laravel caches, run the script:

```bash
./backend/clear-cache.sh
```

Note: Config cache is automatically cleared on container startup, so .env changes are picked up without manual clearing.
