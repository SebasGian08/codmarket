<div class="modal fade" id="modalCreateContact" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Registrar Contacto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.contacts.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Tipo de documento <span class="text-danger">*</span></label>
                            <select name="id_documento_tipo" id="ct-documento-tipo" class="form-control">
                                <option value="">Seleccionar</option>
                                @foreach($documentosTipos ?? [] as $dt)
                                <option value="{{ $dt->id_documento_tipo }}" data-codigo="{{ $dt->codigo }}" data-longitud="{{ $dt->longitud }}">
                                    {{ $dt->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group" data-doc="persona" style="display:none;">
                            <label>Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="nombres" id="ct-nombres" class="form-control">
                        </div>

                        <div class="col-md-6 form-group" data-doc="persona" style="display:none;">
                            <label>Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos" class="form-control">
                        </div>

                        <div class="col-md-6 form-group" data-doc="empresa" style="display:none;">
                            <label>RUC <span class="text-danger">*</span></label>
                            <input type="text" name="documento_numero" id="ct-documento-numero"
                                class="form-control" maxlength="11" inputmode="numeric" placeholder="RUC">
                        </div>

                        <div class="col-md-6 form-group" data-doc="empresa" style="display:none;">
                            <label>Razón social <span class="text-danger">*</span></label>
                            <input type="text" name="razon_social" id="ct-razon-social" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Teléfono / WhatsApp</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Servicio <span class="text-danger">*</span></label>
                        <select name="service_id" class="form-control" required>
                            <option value="">Seleccionar</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id_service }}">
                                    {{ $service->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Origen</label>
                        <select name="source_id" class="form-control">
                            <option value="">Seleccionar</option>
                            @foreach($sources as $source)
                                <option value="{{ $source->id_source }}">
                                    {{ $source->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Mensaje</label>
                        <textarea name="mensaje" class="form-control editor" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Guardar
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

<script>
    (function () {
        const modal = document.getElementById('modalCreateContact');
        if (!modal) return;

        const docTipo = modal.querySelector('#ct-documento-tipo');
        const camposPersona = modal.querySelectorAll('[data-doc="persona"]');
        const camposEmpresa = modal.querySelectorAll('[data-doc="empresa"]');
        const docNumero = modal.querySelector('#ct-documento-numero');

        function aplicarTipoDocumento() {
            if (!docTipo) return;

            const opt = docTipo.options[docTipo.selectedIndex];
            const codigo = (opt?.dataset?.codigo || '').toUpperCase();
            const esEmpresa = codigo === 'RUC';
            const vacio = codigo === '';

            camposPersona.forEach(el => {
                const visible = !vacio && !esEmpresa;
                el.style.display = visible ? '' : 'none';
                el.querySelectorAll('input, select, textarea').forEach(i => i.required = visible);
            });

            camposEmpresa.forEach(el => {
                const visible = !vacio && esEmpresa;
                el.style.display = visible ? '' : 'none';
                el.querySelectorAll('input, select, textarea').forEach(i => i.required = visible);
            });

            if (docNumero) {
                const longitud = parseInt(opt?.dataset?.longitud || '0', 10);
                docNumero.maxLength = longitud > 0 ? longitud : 11;
                docNumero.placeholder = 'RUC';
            }
        }

        if (docTipo) docTipo.addEventListener('change', aplicarTipoDocumento);
        aplicarTipoDocumento();
    })();
</script>