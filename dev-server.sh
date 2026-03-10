#!/bin/bash

# ICSSF Conference Web - Development Server Starter
# Runs both Vite and Laravel servers in the same terminal

echo "🚀 Starting ICSSF Conference Web Development Servers"
echo "===================================================="
echo ""
echo "📍 Public Landing Page: http://localhost:8000"
echo "📍 Dashboard: http://localhost:8000/dashboard"
echo "📍 Vite Dev Server: http://localhost:5173"
echo ""
echo "Press Ctrl+C to stop all servers"
echo ""

# Function to kill all background processes on exit
cleanup() {
    echo ""
    echo "🛑 Stopping servers..."
    kill $VITE_PID $LARAVEL_PID 2>/dev/null || true
    exit 0
}

trap cleanup SIGINT SIGTERM

# Start Vite dev server in background
echo "Starting Vite dev server..."
npm run dev > /tmp/vite.log 2>&1 &
VITE_PID=$!
echo "Vite PID: $VITE_PID"

# Wait a moment for Vite to start
sleep 3

# Start Laravel dev server in background
echo "Starting Laravel dev server..."
php artisan serve --host=127.0.0.1 --port=8000 > /tmp/laravel.log 2>&1 &
LARAVEL_PID=$!
echo "Laravel PID: $LARAVEL_PID"

echo ""
echo "✅ Both servers started!"
echo ""
echo "View logs:"
echo "  Vite:   tail -f /tmp/vite.log"
echo "  Laravel: tail -f /tmp/laravel.log"
echo ""

# Keep script running
wait
