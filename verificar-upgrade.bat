@echo off
echo.
echo ===========================================
echo   VERIFICACION POST-UPGRADE LARAVEL 10
echo ===========================================
echo.

cd /d C:\xampp\htdocs\infusionesgales

echo [1/8] Verificando PHP...
php -v
echo.

echo [2/8] Verificando Composer...
composer --version
echo.

echo [3/8] Verificando Laravel...
php artisan --version
echo.

echo [4/8] Verificando rutas...
php artisan route:list --columns=method,uri 2>&1 | find "admin" >nul
if %ERRORLEVEL%==0 (
    echo     Rutas OK
) else (
    echo     ERROR: Las rutas fallaron
)
echo.

echo [5/8] Verificando vistas compiladas...
php artisan view:clear
php artisan view:cache
if %ERRORLEVEL%==0 (
    echo     Vistas OK
) else (
    echo     ERROR: Las vistas fallaron
)
echo.

echo [6/8] Verificando migraciones...
php artisan migrate:status
echo.

echo [7/8] Verificando config...
php artisan config:cache
if %ERRORLEVEL%==0 (
    echo     Config OK
) else (
    echo     ERROR: La config falló
)
echo.

echo [8/8] Verificando autoloader...
composer dump-autoload
if %ERRORLEVEL%==0 (
    echo     Autoloader OK
) else (
    echo     ERROR: Autoloader falló
)
echo.

echo ===========================================
echo   VERIFICACION COMPLETADA
echo ===========================================
echo.
pause
