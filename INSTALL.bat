@echo off
REM GoalPost Installation Script for Windows
REM This script helps set up the GoalPost project

echo.
echo ================================
echo GoalPost - Installation Script
echo ================================
echo.

REM Check if PHP is installed
php -v >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP is not installed or not in PATH
    echo Please install PHP from https://www.php.net/downloads
    pause
    exit /b 1
)

echo [OK] PHP is installed
php -v | find /v "" | head -n 1
echo.

REM Check if MySQL is installed
mysql --version >nul 2>&1
if errorlevel 1 (
    echo [WARNING] MySQL client not found in PATH
    echo You can still use phpMyAdmin to import the database
    echo.
) else (
    echo [OK] MySQL is installed
    mysql --version
    echo.
)

REM Display setup instructions
echo.
echo ================================
echo Setup Instructions
echo ================================
echo.
echo Step 1: Create Database
echo -----------------------
echo Option A: Using MySQL Command Line
echo   mysql -u root -p < database\schema.sql
echo.
echo Option B: Using phpMyAdmin
echo   1. Open phpMyAdmin in your browser
echo   2. Click "Import" tab
echo   3. Select database\schema.sql file
echo   4. Click "Go"
echo.

echo Step 2: Update Database Config
echo --------------------------------
echo Edit database\config.php and update:
echo   - DB_HOST: localhost
echo   - DB_USER: your MySQL username
echo   - DB_PASS: your MySQL password
echo   - DB_NAME: goalpost_db
echo.

echo Step 3: Start the Server
echo -------------------------
echo Run this command in the GoalPost folder:
echo   php -S localhost:8000
echo.
echo Then open in your browser:
echo   http://localhost:8000
echo.

echo Step 4: Test Login
echo ------------------
echo Admin Account:
echo   Username: admin
echo   Password: admin123
echo.
echo User Account:
echo   Username: user
echo   Password: user123
echo.

echo.
echo ================================
echo Next Steps
echo ================================
echo.
echo 1. Make sure MySQL is running
echo 2. Create the database with schema.sql
echo 3. Update database/config.php with your credentials
echo 4. Run: php -S localhost:8000
echo 5. Open: http://localhost:8000
echo.

pause
