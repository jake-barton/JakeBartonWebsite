#!/bin/bash

# Cache-busting launcher for Pi Kappa Phi T-Shirt Website
# This script starts the PHP server and opens the browser with cache-busting parameters

echo "🚀 Starting Pi Kappa Phi T-Shirt Shop..."

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP first."
    exit 1
fi

# Check if port 8080 is already in use
if lsof -Pi :8080 -sTCP:LISTEN -t >/dev/null 2>&1 ; then
    echo "⚠️  Port 8080 is already in use. Killing existing process..."
    kill -9 $(lsof -ti:8080) 2>/dev/null
    sleep 1
fi

# Start PHP server in background
echo "📡 Starting PHP development server on port 8080..."
php -S localhost:8080 > /dev/null 2>&1 &
PHP_PID=$!

# Wait for server to start
sleep 2

# Check if server started successfully
if ! kill -0 $PHP_PID 2>/dev/null; then
    echo "❌ Failed to start PHP server"
    exit 1
fi

# Generate cache-busting timestamp
TIMESTAMP=$(date +%s)

# Build URL with cache-busting parameter
URL="http://localhost:8080/index.php?v=$TIMESTAMP&nocache=true"

echo "✨ Server started successfully!"
echo "🌐 Opening website with cache-busting: $URL"

# Open in default browser (works on macOS)
open "$URL"

echo ""
echo "📝 Server is running (PID: $PHP_PID)"
echo "🔗 Main Site: http://localhost:8080/index.php?v=$TIMESTAMP"
echo "🔗 Admin Panel: http://localhost:8080/admin.php?v=$TIMESTAMP"
echo ""
echo "Press Ctrl+C to stop the server"

# Keep script running and handle Ctrl+C
trap "echo '\n🛑 Stopping server...'; kill $PHP_PID 2>/dev/null; exit 0" INT TERM

# Wait for PHP process
wait $PHP_PID
