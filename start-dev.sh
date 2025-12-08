#!/bin/bash

# Exit on error
set -e

echo "🚀 Starting UENF Theme Development Environment..."

# Install dependencies if node_modules doesn't exist
if [ ! -d "node_modules" ]; then
    echo "📦 Installing Node dependencies..."
    npm install
fi

# Build assets
echo "🔨 Building assets..."
npm run build

# Start Docker containers
echo "🐳 Starting Docker containers..."
docker compose up -d

echo "✅ Environment is ready!"
echo "🌍 Access WordPress at: http://localhost:8000"
echo "📝 To watch for changes, run: npm run watch"
