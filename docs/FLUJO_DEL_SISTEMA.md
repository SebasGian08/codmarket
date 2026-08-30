# Flujo Actual del Sistema — Documentación y Diseño

> Proyecto: **Infusiones Gales** · Laravel + Blade + jQuery + Bootstrap + MySQL (`datos_market`)
> Alcance: Documentación del flujo real de los módulos operativos (**venta, caja, inventario, ingresos, transferencias, gastos, economía**) y el **diseño objetivo** para cuadrar la parte contable.

---

## 1. Resumen ejecutivo

El sistema tiene una **base de catálogo + punto de venta (POS)** muy completa, pero su capa **económica (dinero) está incompleta**:

- **SÍ está resuelto**: catálogo de productos/variantes/atributos, stock por tienda, el POS, el cierre de venta (cobros multi-método), ingresos/transferencias de **stock**, gastos (registro documental).
- **NO está resuelto**: no existe un libro de movimientos de dinero. El dinero que entra (cobros) y sale (gastos) **no actualiza ningún saldo**. La caja y las cuentas bancarias se registran pero **no cuadran**.
  - `cuentas_bancarias.saldo_actual` es **estático** (nunca se modifica por código).
  - La caja solo guarda `monto_apertura` y `monto_cierre` **manuales**; no hay arqueo ni diferencia automática.
  - No existe "ingreso económico" por otros conceptos ni transferencias de dinero (las "transferencias" son de stock).
  - El método de pago elegido en el POS **se pierde** al registrar la venta.

**Conclusión**: hay que separar conceptualmente **STOCK** (resuelto y sólido) de **DINERO** (diseñado pero no implementado). El diseño de la sección 4 propone la capa contable.

---

## 2. Diagrama general del flujo (AS-IS)

```
                    ┌──────────────────────────────────────────────┐
                    │                 TIENDA(s)                    │
                    │  (tiendas + cajas abiertas + vendedores)     │
                    └──────────────────────────────────────────────┘
                                        │
        ┌───────────────────────────────┼───────────────────────────────┐
        │                               │                               │
   ┌────▼─────┐                  ┌──────▼──────┐                ┌───────▼────────┐
   │ CATÁLOGO │                  │  INVENTARIO │                │   VENTAS (POS) │
   │ producto │                  │   (STOCK)   │                │                │
   │ +variante│                  │             │                │ 1. Asignar caja│
   │ +atributo│                  │             │                │ 2. Carrito     │
   │ +imagen  │                  │             │                │ 3. Registrar   │
   └────┬─────┘                  └──────┬──────┘                │   (stock sale) │
        │                              │                        └──────┬─────────┘
        │                              │                               │
   ┌────▼──────────────────────────────▼──────────────────────────────▼─────────┐
   │                      STOCK (única vía: InventarioService.aplicar)          │
   │  [inventarios por tienda]  +  [productos_variantes.stock global]           │
   │  +  [movimientos: ingreso | venta | transferencia_salida |                 │
   │       transferencia_entrada | ajuste]                                       │
   └────────────────────────────────────────────────────────────────────────────┘
                          ▲                    ▲
        INGRESOS(compra)  │   TRANSFERENCIA    │   VENTA (guarda → stock sale)
        /ANULACIÓN        │   (stock entre      │   /ANULACIÓN (reponer)
        (stock entra)     │    tiendas)         │   /AJUSTE (cierre editar)
                          │                    │
   ┌───────────────────────┴───┐   ┌───────────┴───────────────────────┐
   │ GASTOS (dinero sale)      │   │ CIERRE DE VENTA (dinero entra)   │
   │ registra en `gastos`      │   │ crea `venta_pagos`               │
   │ (NO descuenta nada)       │   │ efectivo→sin cuenta / otros→cuenta│
   └───────────────────────────┘   └──────────────────────────────────┘
```

---

## 3. Flujo de ventas y caja (AS-IS)

### 3.1 Punto de Venta (POS)

1. **Requisito**: caja abierta (`cajas.estado=1`). Sin caja no se puede vender.
2. **`guardar()`** (`VentaController.php:96`) crea la venta:
   - `estado=1` (registrada), `estado_cobro='pendiente'`.
   - Hereda `id_tienda` y `id_vendedor` de la caja.
   - **`id_metodo_pago => null`** (⚠️ el método elegido en el POS se ignora).
   - Descuenta stock por cada item vía `InventarioService::aplicar(...,'venta',...)`.
3. **El cobro NO ocurre aquí**. La venta nace sin pagos.

### 3.2 Cerrar Venta (el cobro real)

1. `cerrarVenta()` lista las ventas `estado=1` y `estado_cobro='pendiente'`.
2. `obtenerCierre($id)` devuelve la venta + métodos de pago activos + cuentas bancarias.
3. El modal arma pagos **localmente en JS** (método, cuenta/efectivo, monto).
4. `procesarCierre($request,$id)` (`VentaController.php:449`):
   - Borra los pagos previos y los recrea desde el request.
   - **Regla**: efectivo → `id_cuenta_bancaria=null` (a la caja); otros métodos → cuenta obligatoria.
   - Valida `Σ pagos == total` (cuadratura).
   - Marca `estado_cobro='cerrado'`, `fecha_cierre`, `usuario_cierre`, y `id_metodo_pago` = primer pago.

### 3.3 Caja

- **Abrir**: `abrir()` valida tienda+vendedor+monto_apertura. No permite 2 cajas con el mismo vendedor+tienda abiertas.
- **Cerrar**: `cerrar()` solo pide `monto_cierre` (conteo manual). **No cuadra** contra apertura+ventas-gastos.
- **Ventas del turno** (`CajaController.php:22`): suma `estado=1` SIN filtrar `estado_cobro='cerrado'` → incluye ventas pendientes y pagadas por banco (sobreestima el efectivo esperado).

### 3.4 Anulación de venta

`anular()` marca `estado=0`, repone stock con `ajuste` (+cantidad), pero **no valida el cobro y no revierte `venta_pagos`** → puede dejar "cobros fantasma" con venta anulada.

---

## 4. Flujo de inventario y stock (AS-IS)

**Punto único de escritura**: `InventarioService::aplicar($idVariante, $idTienda, $tipo, $cantidad, $idReferencia, $idUsuario, $obs)`.

Actualiza **3 cosas por cada llamado**:
1. `inventarios[id_variante][tienda].cantidad` (stock por tienda).
2. `productos_variantes.stock` (stock global denormalizado).
3. Inserta en `movimientos` la bitácora con signo.

### Tipos de movimiento (tabla `movimientos_tipo`)
| ID | codigo | signo | Efecto | Origen |
|---|---|---|---|---|
| 1 | `ingreso` | + | Entra stock | compra / stock inicial / carga masiva |
| 2 | `venta` | − | Sale stock | POS |
| 3 | `transferencia_salida` | − | Sale de tienda origen | transferencia enviar |
| 4 | `transferencia_entrada` | + | Entra a tienda destino | transferencia recibir |
| 5 | `ajuste` | (signo propio) | Ajuste / reversiones | anulaciones, ediciones, cargas |

### Ingresos (stock) — `IngresoController`
- `store()`: tipo `compra` (exige proveedor) o `ajuste`. Crea encabezado + detalles y aplica `ingreso`/`ajuste` a tienda.
- `anular()`: revierte con `ajuste` de **cantidad negativa** (devuelve a la tienda).

### Transferencias (stock) — `TransferenciaController`
- Ciclo: `pendiente → en_transito → recibida` | `anulada`.
- `enviar`: descuenta origen (`transferencia_salida`). `recibir`: suma destino (`transferencia_entrada`).
- `anular`: solo en pendiente/en_tránsito; si está en tránsito repone al origen con `ajuste`.
- **Una transferencia recibida NO se puede anular** (no hay reversa).

### Carga masiva de inventario — `CargaInventarioController`
- Solo **suma** stock (tipo `ingreso`) desde plantilla `sku|producto|cantidad`.

---

## 5. Flujo económico (AS-IS) — el vacío

| Concepto | ¿Dónde se registra? | ¿Actualiza algo? |
|---|---|---|
| Dinero que ENTRA (ventas) | `venta_pagos` | Solo cambia `estado_cobro`. **NO** actualiza caja ni cuenta |
| Dinero que SALE (gastos) | `gastos` | **NADA** (no descuenta caja ni cuenta) |
| Ingresos económicos (otros conceptos) | **NO EXISTE** | — |
| Transferencias de dinero | **NO EXISTE** (las transferencias son de stock) | — |
| Saldo bancario | `cuentas_bancarias.saldo_actual` | **Estático** (nunca se modifica) |
| Caja (efectivo) | `cajas.monto_apertura` / `monto_cierre` | Manual, sin arqueo |

### Gastos — `GastoController`
- `store()`: valida tipo_gasto, tienda, caja (opcional), cuenta (opcional), monto, moneda fija `PEN`.
- **No descuenta** ni caja ni cuenta bancaria: es un registro documental.
- `anular()`: solo cambia `estado=0` (no hay nada que revertir).

---

## 6. Bugs e inconsistencias detectadas (resumen accionable)

### Críticos (afectan dinero/datos)
1. **El método de pago del POS no se guarda** — `id_metodo_pago => null` en `guardar()` (`VentaController.php:133`). El "recibido/vuelto" y el método elegido se descartan.
2. **El efectivo no se acredita a la caja** — "efectivo = caja" es solo un comentario; nada suma a `cajas`.
3. **`saldo_actual` nunca se actualiza** — ventas y gastos no tocan la cuenta bancaria.
4. **Anular venta cobrada deja pagos fantasma** — `anular()` no valida `estado_cobro` ni borra `venta_pagos`.
5. **El cierre de caja no cuadra** — `monto_cierre` libre, sin diferencia/arqueo.

### Importantes
6. **"Ventas del turno" infladas** — suma `estado=1` sin filtrar cobrado, e incluye pagos bancarios.
7. **Truncamiento de cantidades decimales a `(int)`** — en ventas, ingresos, transferencias, carga (`VentaController.php:142`, `IngresoController.php:88`, etc.).
8. **Sin locks de concurrencia** — posible sobreventa y números duplicados (`generarNumeroDocumento` no atómico).
9. **Importación de stock inconsistente** — `ProductoController::importar` y `ProductoVarianteController::importar` escriben `stock` directo sin pasar por `InventarioService` (genera desfase global vs por-tienda).
10. **Dashboard sin métricas de ventas** y con gráficos rotos (canvas comentados pero JS activo).

### Menores
11. `agregarPago()` exige cuenta obligatoria (inconsistente con el cierre, y es endpoint muerto).
12. Sin CRUD de cuentas bancarias ni tipos de gasto (no hay menú ni seeder de tipos).
13. `InventarioService::signo()` sin uso; ternario idéntico en anulación de ingreso; `max(0,...)` enmascara desfases.

---

## 7. DISEÑO OBJETIVO (TO-BE) — Capa de dinero y cuadratura

### 7.1 Separar STOCK de DINERO (principio rector)

| Capa | Estado | Diseño |
|---|---|---|
| **STOCK** (`inventarios`, `movimientos` de stock) | ✅ Sólido | Mantener `InventarioService` |
| **DINERO** (caja, cuentas, gastos, cobros) | ❌ Vacío | Crear capa nueva |

### 7.2 Tablas nuevas propuestas

```
cajas                (cambios menores: ya existe)
cuentas_bancarias    (ya existe; saldo_actual ahora se actualizará)
gastos               (ya existe; ahora descuenta)
venta_pagos          (ya existe; ahora concilia caja/cuenta)

NUEVO: movimientos_dinero          → libro contable único (bitácora de dinero)
NUEVO: cajas_movimientos (u opción: columna en cajas)  → arqueo de caja
NUEVO: ingresos_economicos         → dinero que entra por otros conceptos
NUEVO: transferencias_dinero       → mover saldo entre caja/cuenta (opcional)
```

### 7.3 Esquema de `movimientos_dinero` (libro contable)

```sql
id_movimiento_dinero  PK
id_caja               FK nullable   -- si afecta a una caja
id_cuenta_bancaria    FK nullable   -- si afecta a una cuenta
tipo                  enum('venta','gasto','ingreso_eco','transferencia')
id_referencia         int nullable  -- venta_pagos.id, gastos.id, etc.
monto                 decimal(12,2)
moneda                varchar(3) default 'PEN'
signo                 enum('+','-')  -- flujo de dinero
fecha                 datetime
id_usuario            FK
observacion           text
```

**Regla**: todo movimiento de dinero escribe en `movimientos_dinero` + actualiza **exactamente un** saldo (caja o cuenta).

### 7.4 Reglas de conciliación (TO-BE)

1. **Efectivo → caja** (`cajas`):
   - Al cobrar una venta en efectivo → `cajas` acumula (columna nueva, ej. `total_efectivo` o vía `movimientos_dinero`).
   - El **arqueo** = `monto_apertura + Σ efectivo cobrado − gastos de caja ± transferencias`.
   - `monto_cierre` se compara contra el arqueo → se calcula **sobrante/faltante** automáticamente.

2. **Tarjeta/Transferencia/Otro → cuenta** (`cuentas_bancarias`):
   - Al cobrar → `saldo_actual += monto`.
   - Al pagar un gasto con cuenta → `saldo_actual -= monto`.

3. **Cuadratura de cierre de venta**: ya validada (Σ pagos == total). Se mantiene.

4. **Anulación**:
   - Anular venta: si estaba cobrada, **revertir `venta_pagos`** y su efecto en caja/cuenta.
   - Anular gasto: revertir el descuento en caja/cuenta.

5. **Método de pago en el POS**: persistir `id_metodo_pago` y `monto_recibido` en la venta al `guardar()`, para que el cierre se abra pre-cargado (y no se pierda la selección).

### 7.5 Flujo de cobro TO-BE (un solo panel de pagos)

```
REGISTRAR VENTA (guardar)
  · crea venta estado_cobro='pendiente'
  · guarda id_metodo_pago + monto_recibido (efectivo)

CIERRE DE VENTA (procesarCierre)
  · recibe pagos []  (método, cuenta?, monto)
  · valida regla: efectivo→caja / otros→cuenta
  · valida Σ == total
  · por cada pago:   INSERT venta_pagos
                     INSERT movimientos_dinero (tipo=venta)
                     UPDATE caja | UPDATE cuenta
  · estado_cobro='cerrado'

CIERRE DE CAJA (nuevo)
  · muestra arqueo = apertura + efectivo cobrado − gastos caja
  · ingresa monto_cierre → calcula sobrante/faltante
  · cierra la caja
```

### 7.6 Diagrama TO-BE

```
                    ┌───────────────────────────────┐
                    │          DINERO               │
                    │ (movimientos_dinero = libro)  │
                    └───────────┬───────────────────┘
            ┌───────────────────┼────────────────────┐
            │                   │                    │
   ┌────────▼────────┐  ┌───────▼───────┐  ┌─────────▼──────────┐
   │  CAJA (efectivo)│  │ CUENTA BANCARIA│  │ GASTOS / INGRESOS  │
   │  apertura+ventas│  │ saldo_actual   │  │  ECONÓMICOS        │
   │  − gastos       │  │  +=cobros      │  │  (documental)      │
   │  = arqueo       │  │  −=gastos      │  │  que SÍ descuentan │
   │  sobrante/faltante│  │               │  │  en caja/cuenta   │
   └─────────────────┘  └───────────────┘  └─────────────────────┘
```

### 7.7 Prioridad de implementación sugerida

| Fase | Tarea | Riesgo resuelto |
|---|---|---|
| **1** | Persistir método de pago y monto_recibido del POS en `guardar()` | Bug 1 (se pierde el método) |
| **2** | Crear `movimientos_dinero` + actualizar saldos de cuenta al cobrar (tarjeta/transferencia) | Bug 3 (saldo estático) |
| **3** | Acumular efectivo a la caja + gastos de caja; arqueo con sobrante/faltante en cierre | Bug 2, 5 (caja no cuadra) |
| **4** | Anulación coherente: revertir pagos y el efecto de caja/cuenta | Bug 4 (pagos fantasma) |
| **5** | Filtrar "Ventas del turno" solo cobradas en efectivo | Bug 6 (inflado) |
| **6** | Unificar imports de stock por `InventarioService` + locks de concurrencia | Bugs 8, 9 |
| **7** | Dashboard con métricas de ventas/caja + arreglar gráficos | Bug 10 |

---

## 8. Referencia de archivos clave

| Módulo | Controlador | Vista | Modelo |
|---|---|---|---|
| Ventas | `app/Http/Controllers/Admin/VentaController.php` | `resources/views/admin/ventas/*` | `Venta`, `VentaDetalle`, `VentaPago` |
| Caja | `.../Admin/CajaController.php` | `.../cajas/*` | `Caja` |
| Ingresos (stock) | `.../Admin/IngresoController.php` | `.../ingresos/*` | `Ingreso`, `IngresoDetalle` |
| Transferencias | `.../Admin/TransferenciaController.php` | `.../transferencias/*` | `Transferencia`, `TransferenciaDetalle` |
| Gastos | `.../Admin/GastoController.php` | `.../gastos/*` | `Gasto`, `TipoGasto` |
| Inventario | `.../Admin/InventarioController.php`, `CargaInventarioController.php` | `.../inventarios/*` | `Inventario`, `Movimiento` |
| Productos | `.../Admin/Producto*.php`, `CargaProductosController.php` | `.../productos/*` | `Producto`, `ProductoVariante`, etc. |
| Dashboard | `.../Admin/DashboardController.php` | `admin/dashboard.blade.php` | — |
| Servicio stock | `app/Services/InventarioService.php` | — | — |
| Rutas | `routes/web.php` | — | — |

---
*Documento generado a partir del análisis del código fuente (no modificó ningún archivo de la aplicación).*
