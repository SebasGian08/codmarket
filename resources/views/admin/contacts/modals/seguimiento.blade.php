<div class="modal fade" id="seguimiento{{ $contact->id_contact }}">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">

            <div class="modal-header">
                <h5 class="modal-title">Registrar Seguimiento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form method="POST" action="{{ route('admin.contacts.seguimiento.store', $contact->id_contact) }}">
                    @csrf

                    <div class="row">

                        <div class="col-md-4">
                            <label class="form-label">Tipo</label>
                            <select name="tipo_id" class="form-control" required>
                                @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}">
                                    {{ $tipo->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Comentario</label>
                            <textarea name="comentario" class="form-control" rows="2" required></textarea>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-success btn-sm mt-3">
                        <i class="fa fa-check"></i> Agregar seguimiento
                    </button>
                </form>

                <hr>

                <div style="max-height:350px; overflow-y:auto;">

                    @forelse($contact->seguimientos as $seg)

                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width:35px; height:35px;">
                                <i class="fa fa-comment"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 border rounded-3 p-3 bg-white shadow-sm mb-2">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold">
                                    {{ $seg->tipo->nombre ?? '-' }}
                                </span>
                                <small class="text-muted">
                                    {{ $seg->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>

                            <div>
                                {{ $seg->comentario }}
                            </div>

                            <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                <small class="text-muted">
                                    {{ $seg->user->nombres ?? 'Sistema' }}
                                </small>
                            </div>

                        </div>
                    </div>

                    @empty
                    <div class="text-center text-muted py-3">
                        Sin seguimientos aún
                    </div>
                    @endforelse

                </div>

            </div>

        </div>
    </div>
</div>