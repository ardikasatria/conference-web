#!/bin/bash

# ICSSF Conference Web - Quick Setup Script
# This script automates the development setup process

set -e  # Exit on any error

echo "🚀 ICSSF Conference Web - Development Setup"
echo "==========================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check prerequisites
echo -e "${BLUE}📋 Checking prerequisites...${NC}"

if ! command -v php &> /dev/null; then
    echo "❌ PHP not found. Please install PHP 8.1+"
    exit 1
fi
echo -e "${GREEN}✓ PHP $(php -v | head -n 1 | cut -d' ' -f2)${NC}"

if ! command -v composer &> /dev/null; then
    echo "❌ Composer not found. Please install Composer"
    exit 1
fi
echo -e "${GREEN}✓ Composer$(NC}"

if ! command -v node &> /dev/null; then
    echo "❌ Node.js not found. Please install Node.js 18+"
    exit 1
fi
echo -e "${GREEN}✓ Node.js $(node -v)${NC}"

if ! command -v npm &> /dev/null; then
    echo "❌ npm not found. Please install npm"
    exit 1
fi
echo -e "${GREEN}✓ npm $(npm -v)${NC}"

echo ""
echo -e "${BLUE}📦 Installing PHP dependencies...${NC}"
composer install

echo ""
echo -e "${BLUE}📦 Installing JavaScript dependencies...${NC}"
npm install

echo ""
echo -e "${BLUE}🔑 Generating application key...${NC}"
php artisan key:generate

echo ""
echo -e "${YELLOW}⚠️  IMPORTANT: Database Setup${NC}"
echo "1. Create database in MySQL:"
echo "   mysql -u root -p"
echo "   mysql> CREATE DATABASE icssf_conference CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo ""
echo "2. Update .env file with database credentials (if needed)"
echo ""
read -p "Press enter after database is created and .env is configured..."

echo ""
echo -e "${BLUE}🗄️  Running migrations...${NC}"
php artisan migrate

echo ""
echo -e "${BLUE}🌱 Seeding database...${NC}"
php artisan db:seed --class=RoleSeeder

echo ""
echo -e "${GREEN}✅ Setup Complete!${NC}"
echo ""
echo -e "${YELLOW}Next Steps:${NC}"
echo ""
echo "1. Open Terminal 1 - Start Vite dev server (for React hot reload):"
echo "   ${BLUE}npm run dev${NC}"
echo ""
echo "2. Open Terminal 2 - Start Laravel dev server:"
echo "   ${BLUE}php artisan serve${NC}"
echo ""
echo "3. Open in browser: ${BLUE}http://localhost:8000${NC}"
echo ""
echo -e "${GREEN}Happy Coding! 🎉${NC}"
