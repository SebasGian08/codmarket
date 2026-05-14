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

                    <div class="form-group">
                        <label>Nombres <span class="text-danger">*</span></label>
                        <input type="text" name="nombres" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Apellidos</label>
                        <input type="text" name="apellidos" class="form-control">
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
                        <textarea name="mensaje" class="form-control" rows="3"></textarea>
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