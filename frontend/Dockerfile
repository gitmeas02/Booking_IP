# Frontend Dockerfile
FROM node:18-alpine

# Install bash
RUN apk add --no-cache bash

# Set working directory
WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci --only=production

# Copy source code
COPY . .

# Build the application
RUN npm run build

# Expose port
EXPOSE 3000 5000

# Start both the dev server and json-server
CMD ["sh", "-c", "npm run server & npm run dev -- --host 0.0.0.0 --port 3000"]
