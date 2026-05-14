<div class="modal fade" id="status{{ $contact->id_contact }}">
    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST" action="{{ route('admin.contacts.changeStatus', $contact->id_contact) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5>Cambiar estado</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <select name="id_status" class="form-control">

                        @foreach($statuses as $status)
                        <option value="{{ $status->id_status }}">
                            {{ $status->nombre }}
                        </option>
                        @endforeach

                    </select>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Actualizar
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>