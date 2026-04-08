# Thrift & Found - Deployment Guide

## Prerequisites

1. **Web Hosting** with:
   - PHP 7.4 or higher
   - MySQL 5.7 or higher
   - SSL Certificate (for HTTPS)
   - cPanel or FTP access

2. **Domain Name** (e.g., thriftandfound.com)

## Step 1: Prepare Your Files

1. Compress the entire `thrift-shop` folder into a ZIP file
2. Name it `thrift-shop.zip`

## Step 2: Set Up Database

1. Log into your hosting cPanel
2. Find **MySQL Databases**
3. Create a new database: `thrift_shop`
4. Create a database user with password
5. Add user to database with ALL PRIVILEGES
6. Open phpMyAdmin
7. Import `backend/database.sql`

## Step 3: Update Configuration Files

### Update `backend/db-config.php`:
```php
define('DB_HOST', 'localhost'); // Usually localhost
define('DB_NAME', 'thrift_shop'); // Your database name
define('DB_USER', 'your_db_user'); // Your database username
define('DB_PASS', 'your_db_password'); // Your database password