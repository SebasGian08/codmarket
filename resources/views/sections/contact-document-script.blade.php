<script>
    (function () {
        function initTipoDocumento() {
            const docTipo = document.getElementById('id_documento_tipo');
            const docForm = docTipo ? docTipo.closest('form') : null;

            if (!docTipo || !docForm || docTipo.dataset.bound === '1') return;
            docTipo.dataset.bound = '1';

            const camposPersona = docForm.querySelectorAll('[data-doc="persona"]');
            const camposEmpresa = docForm.querySelectorAll('[data-doc="empresa"]');
            const docNumero = docForm.querySelector('#documento_numero');
            const docContador = docForm.querySelector('[data-counter-for="documento_numero"]');

            function actualizarContador() {
                if (!docNumero || !docContador) return;
                const max = docNumero.maxLength > 0 ? docNumero.maxLength : 11;
                docContador.textContent = (docNumero.value || '').length + '/' + max;
            }

            function aplicar() {
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
                window.jQuery(docTipo).off('change.tipoDoc').on('change.tipoDoc', aplicar);
                window.jQuery(document).off('click.tipoDoc').on('click.tipoDoc', '.nice-select .option', function () {
                    setTimeout(aplicar, 0);
                });
            } else {
                docTipo.addEventListener('change', aplicar);
            }

            aplicar();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTipoDocumento);
        } else {
            initTipoDocumento();
        }
    })();
</script>
