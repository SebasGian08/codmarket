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
                    <input type="hidden" id="cerrarEsperado" value="0">

                    <div class="alert alert-light border" id="cerrarInfo"></div>

                    <div>
                        <label>Monto de cierre (conteo final)</label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" name="monto_cierre" id="cerrarMonto" class="form-control" step="0.01"
                                min="0" required>
                        </div>
                    </div>

                    <div class="alert alert-info border mt-3 mb-0" id="cerrarDiferencia">
                        Esperado: S/ 0.00
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

        function actualizarDiferencia() {
            var esperado = parseFloat($('#cerrarEsperado').val()) || 0;
            var contado = parseFloat($('#cerrarMonto').val()) || 0;
            var dif = contado - esperado;

            var html = 'Esperado: <b>S/ ' + esperado.toFixed(2) + '</b>';

            if (Math.abs(dif) > 0.001) {
                var tipo = dif > 0 ? 'Sobrante' : 'Faltante';
                var clase = dif > 0 ? 'text-success' : 'text-danger';
                html += ' &nbsp;|&nbsp; <span class="' + clase + '"><b>' + tipo + ': S/ ' +
                    Math.abs(dif).toFixed(2) + '</b></span>';
            } else {
                html += ' &nbsp;|&nbsp; <span class="text-success"><b>Caja cuadrada</b></span>';
            }

            $('#cerrarDiferencia').html(html);
        }

        $('#cerrarMonto').on('input', actualizarDiferencia);

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
