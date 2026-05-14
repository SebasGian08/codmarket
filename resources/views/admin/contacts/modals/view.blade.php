<div class="modal fade" id="view{{ $contact->id_contact }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">

            <div class="modal-header">
                <h5 class="modal-title">Detalle del seguimiento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body p-4">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="text-muted small">Nombre completo</label>
                        <div class="fw-semibold text-dark">
                            {{ $contact->nombres }} {{ $contact->apellidos }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">Correo electrónico</label>
                        <div class="fw-semibold text-dark">
                            {{ $contact->email }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">Teléfono</label>
                        <div class="fw-semibold text-dark">
                            {{ $contact->telefono }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small">Servicio</label>
                        <div class="fw-semibold text-dark">
                            {{ $contact->service->nombre ?? '-' }}
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="text-muted small">Mensaje</label>
                        <div class="border rounded p-3 bg-light text-dark">
                            {{ $contact->mensaje }}
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>