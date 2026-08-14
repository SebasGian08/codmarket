<div class="modal fade" id="modalCerrar">
    <div class="modal-dialog">

        <form action="" method="POST" id="formCerrarCaja">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Cerrar Caja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="cerrarId">

                    <div class="alert alert-light border" id="cerrarInfo"></div>

                    <div>
                        <label>Monto de cierre (conteo final)</label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" name="monto_cierre" id="cerrarMonto" class="form-control" step="0.01"
                                min="0" required>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-lock"></i> Cerrar caja
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#formCerrarCaja').on('submit', function() {
            var id = $('#cerrarId').val();

            if (!id) {
                return false;
            }

            $(this).attr('action', '{{ url("admin/cajas") }}/' + id + '/cerrar');
        });
    });
</script>
@endpush
