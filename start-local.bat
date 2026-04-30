@echo off
chcp 65001 >nul
REM =============================================================================
REM RAC-TOGO — Script de lancement local Windows (sans Docker)
REM =============================================================================
REM Usage: start-local.bat [option]
REM Options:
REM   --install        Installer les dépendances
REM   --backend-only   Lancer uniquement le backend
REM   --frontend-only  Lancer uniquement le frontend
REM   --stop           Arrêter tous les services
REM =============================================================================

setlocal EnableDelayedExpansion

set "PROJECT_DIR=%~dp0"
set "BACKEND_DIR=%PROJECT_DIR%rac-togo\backend"
set "FRONTEND_DIR=%PROJECT_DIR%rac-togo\frontend"
set "PID_DIR=%PROJECT_DIR%.rac-pids"
set "LOG_DIR=%PROJECT_DIR%.rac-logs"

mkdir "%PID_DIR%" 2>nul
mkdir "%LOG_DIR%" 2>nul

:: =============================================================================
:: Vérifications
:: =============================================================================

echo.
echo ╔══════════════════════════════════════════════════════════════╗
echo ║           RAC-TOGO — Lancement Local (Sans Docker)           ║
echo ╚══════════════════════════════════════════════════════════════╝
echo.

php -v >nul 2>&1
if errorlevel 1 (
    echo [ERREUR] PHP n'est pas installé ou n'est pas dans le PATH
    echo Télécharge-le sur: https://windows.php.net/download/
    exit /b 1
)

composer -V >nul 2>&1
if errorlevel 1 (
    echo [ERREUR] Composer n'est pas installé
    echo Télécharge-le sur: https://getcomposer.org/download/
    exit /b 1
)

node -v >nul 2>&1
if errorlevel 1 (
    echo [ERREUR] Node.js n'est pas installé
    echo Télécharge-le sur: https://nodejs.org/
    exit /b 1
)

echo [OK] Tous les prérequis sont satisfaits
echo.

:: =============================================================================
:: Gestion des arguments
:: =============================================================================

set "BACKEND_ONLY=false"
set "FRONTEND_ONLY=false"
set "INSTALL=false"
set "STOP=false"

:parse_args
if "%~1"=="" goto :done_args
if "%~1"=="--install" set "INSTALL=true"
if "%~1"=="--backend-only" set "BACKEND_ONLY=true"
if "%~1"=="--frontend-only" set "FRONTEND_ONLY=true"
if "%~1"=="--stop" set "STOP=true"
if "%~1"=="--help" goto :show_help
shift
goto :parse_args

:show_help
echo Usage: start-local.bat [option]
echo.
echo Options:
echo   --install        Installer les dépendances
echo   --backend-only   Lancer uniquement le backend
echo   --frontend-only  Lancer uniquement le frontend
echo   --stop           Arrêter tous les services
echo.
exit /b 0

:done_args

:: =============================================================================
:: Arrêt
:: =============================================================================

if "%STOP%"=="true" (
    echo [INFO] Arrêt des services...
    taskkill /F /FI "WINDOWTITLE eq RAC-TOGO Backend*" >nul 2>&1
    taskkill /F /FI "WINDOWTITLE eq RAC-TOGO Frontend*" >nul 2>&1
    taskkill /F /IM php.exe >nul 2>&1
    taskkill /F /IM node.exe >nul 2>&1
    echo [OK] Services arrêtés
    exit /b 0
)

:: =============================================================================
:: Installation
:: =============================================================================

if "%INSTALL%"=="true" (
    echo [INFO] Installation du backend...
    cd /d "%BACKEND_DIR%"
    if not exist ".env" (
        if exist ".env.example" (
            copy .env.example .env
            echo [OK] .env créé depuis .env.example
        )
    )
    composer install --no-interaction
    php artisan key:generate
    php artisan storage:link 2>nul

    echo [INFO] Installation du frontend...
    cd /d "%FRONTEND_DIR%"
    npm install

    echo.
    echo [OK] Installation terminée !
    echo Lance maintenant: start-local.bat
    exit /b 0
)

:: =============================================================================
:: Vérifier .env
:: =============================================================================

if not exist "%BACKEND_DIR%\.env" (
    echo [ERREUR] Fichier .env manquant dans le backend
    echo Crée-le avec: copy .env.example .env
    exit /b 1
)

:: =============================================================================
:: Lancer les services
:: =============================================================================

if "%FRONTEND_ONLY%"=="false" (
    echo [INFO] Démarrage du backend...
    cd /d "%BACKEND_DIR%"
    start "RAC-TOGO Backend" cmd /k "php artisan serve --host=0.0.0.0 --port=8000"
    echo [OK] Backend lancé sur http://localhost:8000
    timeout /t 3 /nobreak >nul
)

if "%BACKEND_ONLY%"=="false" (
    echo [INFO] Démarrage du frontend...
    cd /d "%FRONTEND_DIR%"
    start "RAC-TOGO Frontend" cmd /k "npm run dev"
    echo [OK] Frontend lancé sur http://localhost:5173
)

echo.
echo ══════════════════════════════════════════════════════════════
echo   🚀 RAC-TOGO est en ligne !
echo ══════════════════════════════════════════════════════════════
echo.
echo   Frontend:  http://localhost:5173
echo   Backend:   http://localhost:8000
echo   API:       http://localhost:8000/api
echo.
echo   Pour arrêter: start-local.bat --stop
echo.
