# Reglas del Proyecto - Integración Figma y Sistema de Diseño

## Regla principal

**EL DISEÑO ACTUAL DE ESTE PROYECTO ES LA FUENTE PRINCIPAL DE DISEÑO.**

Figma debe utilizarse únicamente como referencia para nuevas pantallas, componentes o mejoras. NO reemplazar ni cambiar arbitrariamente el diseño, estilos o estructura visual existente.

## 1. Analizar primero el proyecto

Antes de modificar código, revisar:

- Layouts existentes (`resources/views/layouts/appweb.blade.php`, `admin/layouts/app.blade.php`)
- Master layouts
- Blade views (`resources/views`)
- Partials (`resources/views/partials`, `admin/partials`)
- Secciones (`resources/views/sections`)
- Componentes
- CSS
- Bootstrap
- JavaScript/jQuery
- Variables y estilos globales
- Colores utilizados actualmente
- Tipografías
- Botones
- Inputs
- Selects
- Modales
- Tablas
- Cards
- Alertas
- Menús
- Sidebar
- Navbar
- Breadcrumbs
- Espaciados
- Iconos
- Responsive design

Identificar qué componentes ya existen y deben reutilizarse.

## 2. Regla de diseño

El diseño actual del proyecto tiene prioridad.

Cuando exista una diferencia entre Figma y el diseño actual:

1. Mantener el estilo visual actual.
2. Mantener los componentes existentes.
3. Mantener la estructura general del sistema.
4. Adaptar el diseño de Figma al estilo existente.
5. Solo crear un nuevo componente cuando realmente sea necesario.

NO convertir el proyecto en el diseño de Figma. Convertir el diseño de Figma en una pantalla que parezca que siempre perteneció al sistema.

## 3. Figma

Cuando se proporcione una URL de Figma:

- Analizar el frame correspondiente.
- Identificar estructura y contenido.
- Analizar distribución.
- Analizar tamaños y espaciados.
- Identificar componentes.
- Identificar iconos e imágenes.
- Identificar estados de botones, inputs y otros elementos.
- Utilizar Figma como referencia funcional y visual.

Adaptar todo al sistema de diseño existente.

## 4. Reutilización

Antes de crear HTML/CSS nuevo, buscar si ya existe:

- Un botón equivalente.
- Un modal equivalente.
- Un formulario equivalente.
- Una tabla equivalente.
- Un card equivalente.
- Un componente de búsqueda.
- Un componente Select2.
- Un DataTable.
- Un breadcrumb.
- Un navbar.
- Un sidebar.
- Un sistema de alertas.

Si existe, reutilizarlo. NO duplicar componentes.

## 5. Stack actual

El proyecto utiliza principalmente:

- Laravel
- PHP
- Blade
- Bootstrap
- JavaScript
- jQuery
- Select2
- DataTables
- MySQL

Respetar las tecnologías existentes. NO introducir React, Vue, Tailwind u otro framework frontend salvo solicitud explícita.

## 6. Implementación

Al implementar una pantalla:

1. Analizar primero el proyecto.
2. Identificar el layout que corresponde.
3. Identificar componentes reutilizables.
4. Analizar el diseño de Figma.
5. Adaptar Figma al diseño actual.
6. Implementar la vista.
7. Reutilizar CSS existente.
8. Crear CSS nuevo únicamente cuando sea necesario.
9. **El CSS va en `admin/assets/css/style.css`, NO inline en Blade.**
10. Mantener responsive.
11. No modificar funcionalidades existentes sin autorización.

## 7. Fórmula

DISEÑO ACTUAL DEL PROYECTO + DISEÑO/REQUERIMIENTO DE FIGMA = NUEVA PANTALLA CONSISTENTE CON EL SISTEMA.

La nueva pantalla debe parecer parte natural de la aplicación actual.

## 8. Antes de modificar

Antes de realizar cambios importantes, mostrar:

- Archivos que se van a modificar.
- Componentes existentes que se van a reutilizar.
- Componentes nuevos que se necesitan crear.
- Cambios de CSS que se necesitan realizar.
- Conflictos detectados entre Figma y el diseño actual.

No realizar cambios destructivos.

## 9. Organización del CSS en Admin

**El CSS de las vistas admin NO va inline en los archivos Blade.**

### Regla principal
Todo CSS específico de una vista admin se escribe en:
```
admin/assets/css/style.css
```

Este archivo ya está cargado en el layout admin (después de `admin-custom.css`).

### Estructura del archivo
```css
/* =============================================
   ESTILOS PERSONALIZADOS - ADMIN
   ============================================= */

/* ============ NOMBRE_DEL_MODULO ============ */
/* estilos del módulo aquí */

/* ============ FIN NOMBRE_DEL_MODULO ============ */
```

### Convenciones
1. **Separar por módulo** con comentarios: `/* ============ VENTAS ============ */`
2. **Prefijo de clase** por módulo: `venta-*`, `cliente-*`, `producto-*`, `caja-*`, etc.
3. **No usar `<style>`** en archivos Blade admin. Solo `<script>` si es necesario.
4. **Soporte dark mode** incluir reglas `html[data-theme="dark"]` cuando aplique.
5. **Orden de carga** en `head.blade.php`:
   - `bootstrap.min.css` → `plugins.css` → `fonts.css` → `demo.css` → `kaiadmin.min.css` → `dark-mode.css` → `admin-custom.css` → **`style.css`** (el último, mayor especificidad)

### Ejemplo al agregar un módulo nuevo
Si se crea una vista para `productos`:
1. Agregar estilos en `admin/assets/css/style.css` dentro de `/* ============ PRODUCTOS ============ */`
2. NO agregar `<style>` en el archivo Blade
3. Usar prefijo `producto-*` para las clases CSS

### Archivos CSS del admin
| Archivo | Uso |
|---|---|
| `admin-custom.css` | Overrides globales del admin (modales, tablas, responsive) |
| `style.css` | Estilos de vistas admin organizados por módulo |
| `dark-mode.css` | Variables y reglas para modo oscuro |
| `kaiadmin.min.css` | Tema principal del template (no modificar) |

## 10. Flujo de trabajo con Figma

Cuando se entregue una URL de Figma:

> "Analiza este diseño de Figma y crea esta pantalla dentro de mi proyecto Laravel.
> Primero analiza el diseño actual del proyecto y reutiliza sus layouts, componentes, colores, botones, formularios, tablas y estilos.
> Adapta el diseño de Figma al sistema visual existente.
> No cambies el diseño global del proyecto.
> No introduzcas nuevos frameworks.
> Mantén Blade + Bootstrap + jQuery.
> La pantalla debe sentirse como una nueva pantalla nativa de mi sistema, no como una página independiente de Figma."

## Objetivo final

Figma = referencia del nuevo requerimiento.
Proyecto actual = fuente de verdad del sistema de diseño.
OpenCode = encargado de analizar ambos y realizar la integración.
