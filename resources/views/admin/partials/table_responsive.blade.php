{{-- ============================================================
    PARTIAL REUTILIZABLE: Tablas responsive (tarjetas en móvil)
    ------------------------------------------------------------
    Uso:
      1) @include('admin.partials.table_responsive')  al inicio del contenido
      2) Añadir la clase "table-cards" a la <table>
      3) Cada <td> debe llevar data-label="Nombre visible" (menos el de acciones)
      4) Marcar la celda de acciones con la clase "table-card-actions"
    En escritorio se muestra la tabla normal; en móvil/tableta
    (<=991px) cada fila se convierte en una tarjeta accesible.
    ============================================================ --}}

<style>
    /* Evita que la tabla se estire más ancha que su contenedor */
    .table-cards {
        max-width: 100%;
    }

    .table-cards td,
    .table-cards th {
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    @media (max-width: 991.98px) {

        /* Oculta el encabezado y convierte la fila en tarjeta */
        .table-cards thead { display: none; }

        .table-cards,
        .table-cards tbody,
        .table-cards tr,
        .table-cards td { display: block; width: 100%; }

        .table-cards tr {
            border: 1px solid rgba(0, 0, 0, .12);
            border-radius: .75rem;
            margin-bottom: .85rem;
            padding: .35rem .75rem;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        html[data-theme="dark"] .table-cards tr {
            background: var(--ka-surface);
            border-color: var(--ka-input-bg);
        }

        /* Cada celda: etiqueta a la izquierda, valor a la derecha */
        .table-cards td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .75rem;
            border: 0;
            padding: .5rem .25rem;
            min-height: 2.25rem;
        }

        .table-cards td + td { border-top: 1px dashed rgba(0, 0, 0, .08); }

        html[data-theme="dark"] .table-cards td + td { border-top-color: var(--ka-input-bg); }

        .table-cards td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            flex: 0 0 auto;
            margin-right: auto;
            padding-right: 1rem;
        }

        html[data-theme="dark"] .table-cards td::before { color: #9aa0ac; }

        /* Celdas de acciones: botones apilados a todo el ancho */
        .table-cards td.table-card-actions {
            display: block;
            padding: .35rem 0 .25rem;
        }

        .table-cards td.table-card-actions::before { content: none; }

        .table-cards td.table-card-actions .btn {
            width: 100%;
            margin: 0 0 .4rem;
            justify-content: center;
        }

        /* Controles de DataTables apilados y sin desborde */
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_length {
            float: none;
            display: block;
            text-align: left;
            margin-bottom: .5rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            max-width: 100%;
            width: auto;
        }

        .dataTables_wrapper .dataTables_length select { max-width: 100%; }

        .dataTables_wrapper { overflow-x: hidden; }
    }
</style>
