---
name: instalacion-inicial
description: >-
  Instalación inicial de la base de datos de este proyecto (POS/ventas Laravel + MySQL).
  Usar SOLO cuando: (1) el usuario pida la instalación inicial o migración inicial de la BD
  ("instalacion inicial", "migracion inicial", "crear la BD desde cero", "todas las tablas",
  "instalacion_inicial.sql", "migrate --path=...instalacion_inicial"), o (2) se vaya a CREAR
  UNA TABLA NUEVA (nueva migración con Schema::create / CREATE TABLE), para registrarla también
  en database/migrations/instalacion_inicial.sql. NO usar para tareas normales de desarrollo ni
  para migraciones que solo alteren columnas de tablas existentes; no ejecutar por defecto en
  cada conversación.
---

# Instalación Inicial de la Base de Datos

## Regla principal

**TODA tabla nueva debe registrarse en `database/migrations/instalacion_inicial.sql`.**

- Ese archivo es la fuente de verdad de la estructura inicial: contiene TODAS las tablas del
  sistema con `CREATE TABLE IF NOT EXISTS`, en el orden autorreferente que permite una
  instalación desde cero (FKs incluidas, con `SET FOREIGN_KEY_CHECKS = 0` al inicio/1 al final).
- Cuando se crea una tabla nueva, además de su migración incremental, ES OBLIGATORIO agregarla
  a `instalacion_inicial.sql`. Así una instalación nueva siempre queda completa.

## ¿Cuándo se usa?

NO se ejecuta siempre. Solo cuando:

1. El usuario invoca la instalación inicial de la BD (instalación desde cero, migración inicial,
   reimplementar el esquema completo).
2. Se va a **crear una tabla nueva** (migración con `Schema::create`): en ese caso hay que
   resolver la migración Y registrar la tabla en `instalacion_inicial.sql`.

## Archivos clave

| Archivo | Rol |
|---|---|
| `database/migrations/instalacion_inicial.sql` | Estructura completa del esquema inicial (69 tablas, `CREATE TABLE IF NOT EXISTS`). AQUÍ se agregan las tablas nuevas. |
| `database/migrations/2026_09_07_000000_instalacion_inicial.php` | Migración que ejecuta ese SQL. Se salta automáticamente si ya existe la tabla `roles` (guarda: `Schema::hasTable('roles')`). Su nombre de fecha debe quedar DESPUÉS de todas las demás migraciones. |
| `produccion.sql` | Script antiguo POS (idempotente). Ya NO es la fuente de verdad; la inicial lo es. |

Las otras migraciones de `database/migrations/` son incrementales (upgrades sobre una BD ya
instalada). No construyen el esquema completo desde cero porque referencian FKs a tablas
(`tipo_documento`, `proveedores`, `productos_variantes`, `permisos`) que ellas no crean; por
eso la migración inicial es el único camino para una instalación nueva.

## Instalación NUEVA (BD vacía)

Ejecutar SOLO esta migración (las demás suponen tablas ya existentes y fallarían):

```
php artisan migrate --path=database/migrations/2026_09_07_000000_instalacion_inicial.php --force
```

Alternativas:
- Importar el dump oficial completo (estructura + datos) `grupocod_dayannaconfecciones`.
- Después, cargar datos base/seed según corresponda (rol, permisos, empresa, configuraciones,
  productos, etc.).

## Instalación EXISTENTE

`php artisan migrate` normal: la migración inicial queda registrada como pendiente pero se
salta sola por el guard de `roles`, y las migraciones pendientes aplican sus cambios
incrementales.

## Agregar una tabla nueva (flujo obligatorio)

1. Crear la migración incremental normal con `Schema::create` (o equivalente).
2. **Agregar la MISMA tabla a `database/migrations/instalacion_inicial.sql`** con
   `CREATE TABLE IF NOT EXISTS` usando la misma definición, insertándola en una posición que
   respete las FK (las tablas referenciadas antes que las que referencian).
3. Opcional: validar que la cantidad de `CREATE TABLE IF NOT EXISTS` en el .sql coincida con el
   total esperado (69 + nuevas).

## Validación recomendada

- Sintaxis PHP de la migración: `php -l database/migrations/2026_09_07_000000_instalacion_inicial.php`
- Prueba real: crear BD temporal → ejecutar la migración con `--path` → contar tablas
  (`SHOW TABLES`) y verificar que no queden `INSERT` ni restos en el .sql.
- Chequear que el .sql no tenga `SET ...@OLD_*` de phpMyAdmin ni bloques `/*!...*/` que rompan
  el import, ni constraints duplicadas (inline + ALTER).

## Entorno

- Local: `C:\xampp\htdocs\infusionesgales` (BD local `datos_market`, MySQL root sin password).
- Producción: `pgr.grupocodware.com` (cPanel, usuario `grupocod`) — deploy con `git pull` +
  `php artisan migrate --force`.
- Stack: Laravel 10, Blade, Bootstrap, jQuery, Select2, DataTables, MySQL/MariaDB.