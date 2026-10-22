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
                            <label>Tipo de documento <small class="text-danger">*</small></label>
                            <select name="id_documento_tipo" id="ct-documento-tipo" class="form-control">
                                <option value="">Seleccionar</option>
                                @foreach($documentosTipos ?? [] as $dt)
                                <option value="{{ $dt->id_documento_tipo }}" data-codigo="{{ $dt->codigo ?: $dt->nombre }}" data-longitud="{{ $dt->longitud }}">
                                    {{ $dt->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group" data-doc="persona" style="display:none;">
                            <label>Nombres <small class="text-danger">*</small></label>
                            <input type="text" name="nombres" id="ct-nombres" class="form-control">
                        </div>

                        <div class="col-md-6 form-group" data-doc="persona" style="display:none;">
                            <label>Apellidos <small class="text-danger">*</small></label>
                            <input type="text" name="apellidos" class="form-control">
                        </div>

                        <div class="col-md-6 form-group" data-doc="empresa" style="display:none;">
                            <label>RUC <small class="text-danger">*</small></label>
                            <div class="contacto-counter-wrap">
                                <input type="text" name="documento_numero" id="ct-documento-numero"
                                    class="form-control has-counter" maxlength="11" inputmode="numeric" placeholder="RUC">
                                <span class="input-counter" data-counter-for="ct-documento-numero">0/11</span>
                            </div>
                        </div>

                        <div class="col-md-6 form-group" data-doc="empresa" style="display:none;">
                            <label>Razón social <small class="text-danger">*</small></label>
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
                        <label>Servicio</label>
                        <select name="service_id" class="form-control">
                            <option value="">Sin servicio</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id_service }}">
                                    {{ $service->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Marca de interés</label>
                        <select name="id_marca" class="form-control">
                            <option value="">Sin marca</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id_marca }}">{{ $marca->nombre }}</option>
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
        function initTipoDocumento() {
        const modal = document.getElementById('modalCreateContact');
        if (!modal) return;

        const docTipo = modal.querySelector('#ct-documento-tipo');
        if (!docTipo) return;

        const camposPersona = modal.querySelectorAll('[data-doc="persona"]');
        const camposEmpresa = modal.querySelectorAll('[data-doc="empresa"]');
        const docNumero = modal.querySelector('#ct-documento-numero');
        const docContador = modal.querySelector('[data-counter-for="ct-documento-numero"]');

        function actualizarContador() {
            if (!docNumero || !docContador) return;
            const max = docNumero.maxLength > 0 ? docNumero.maxLength : 11;
            docContador.textContent = (docNumero.value || '').length + '/' + max;
        }

        function aplicarTipoDocumento() {
            if (!docTipo) return;

            const opt = docTipo.options[docTipo.selectedIndex];
            const valor = opt ? (opt.value || '') : '';
            const vacio = valor === '';
            const etiqueta = ((opt && (opt.getAttribute('data-codigo') || opt.textContent)) || '').trim().toUpperCase();
            const esEmpresa = etiqueta.indexOf('RUC') !== -1;

            camposPersona.forEach(function (el) {
                const visible = !vacio && !esEmpresa;
                el.style.display = visible ? '' : 'none';
                el.querySelectorAll('input, select, textarea').forEach(function (i) { i.required = visible; });
            });

            camposEmpresa.forEach(function (el) {
                const visible = !vacio && esEmpresa;
                el.style.display = visible ? '' : 'none';
                el.querySelectorAll('input, select, textarea').forEach(function (i) { i.required = visible; });
            });

            if (docNumero) {
                const longitud = parseInt((opt && opt.getAttribute('data-longitud')) || '0', 10);
                docNumero.maxLength = longitud > 0 ? longitud : 11;
                docNumero.placeholder = esEmpresa ? 'RUC' : 'Número de documento';
            }

            actualizarContador();
        }

        if (docNumero) {
            docNumero.addEventListener('input', actualizarContador);
        }

        if (window.jQuery) {
            window.jQuery(docTipo).off('change.tipoDoc').on('change.tipoDoc', aplicarTipoDocumento);
        } else {
            docTipo.addEventListener('change', aplicarTipoDocumento);
        }

        aplicarTipoDocumento();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTipoDocumento);
        } else {
            initTipoDocumento();
        }
    })();
</script>