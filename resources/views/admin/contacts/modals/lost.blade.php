<div class="modal fade" id="lost{{ $contact->id_contact }}">
    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST" action="{{ route('admin.contacts.changeStatus', $contact->id_contact) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5>Marcar como perdido</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id_status" value="5">

                    <label>Motivo</label>
                    <textarea name="motivo" class="form-control" required></textarea>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger"><i class="fa fa-check"></i> Confirmar</button>
                </div>

            </form>

        </div>

    </div>
</div>