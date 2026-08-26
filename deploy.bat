@echo off
echo.
echo ===========================================
echo   DEPLOY AUTOMATICO - Infusiones Gales
echo ===========================================
echo.

cd /d C:\xampp\htdocs\infusionesgales

echo [1/6] Verificando cambios en el repositorio...
git status --short
echo.

echo [2/6] Descargando ultimos cambios...
git pull
if %ERRORLEVEL% neq 0 (
    echo ERROR: Fallo el git pull
    pause
    exit /b 1
)

echo.
echo [3/6] Verificando composer.json...
git diff HEAD@{1} --name-only 2>nul | findstr "composer.json" >nul
if %ERRORLEVEL%==0 (
    echo     composer.json cambio - ejecutando composer install...
    composer install --no-dev --optimize-autoloader
) else (
    echo     Sin cambios en composer.json - omitido
)

echo.
echo [4/6] Limpiando cache...
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

echo.
echo [5/6] Ejecutando migraciones pendientes...
php artisan migrate --force

echo.
echo [6/6] Optimizando aplicacion...
php artisan optimize

echo.
echo ===========================================
echo   DEPLOY COMPLETADO EXITOSAMENTE
echo ===========================================
echo.
pause
