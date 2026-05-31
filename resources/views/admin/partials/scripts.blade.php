<script src="{{ asset('admin/assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/core/bootstrap.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/plugin/chart.js/chart.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/plugin/chart-circle/circles.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/plugin/datatables/datatables.min.js') }}"></script>

<!-- <script src="{{ asset('admin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>-->

<script src="{{ asset('admin/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugin/jsvectormap/world.js') }}"></script>

<script src="{{ asset('admin/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/kaiadmin.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/setting-demo2.js') }}"></script>
<script src="{{ asset('admin/assets/js/demo.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('admin/assets/js/tinymce/tinymce.min.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/902qb0ly22vsnct9auwo61awn2teqgicbkw5zkdyn378tclh/tinymce/6/tinymce.min.js">
</script>

<script>
/* tinymce.init({
    selector: '#editor',
    height: 400,
    plugins: 'code image link media table lists',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | code | image media table',
    menubar: false
}); */
tinymce.init({
    selector: '#editor',
    width: "100%",
    height: 600,    
    statubar: true,
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
});
</script>
<script>
$(document).ready(function() {
    $('#basic-datatables').DataTable({
        "pageLength": 10,
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    });

});

$(document).on('click', '.btn-delete, .btn-delete-img', function(e) {
    e.preventDefault();

    let form = $(this).closest('form');
    let url = $(this).data('url');

    Swal.fire({
        title: '¿Estás seguro?',
        text: "Este registro se eliminará permanentemente",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {
            if (url) {

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {

                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            timer: 1200,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 800);
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo eliminar', 'error');
                    }
                });

            } else {
                form.submit();
            }

        }

    });
});
</script>

<!-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
 -->
@push('scripts')

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        title: '¡Correcto!',
        text: @json(session('success')),
        icon: 'success',
        timer: 2500,
        showConfirmButton: false
    });
});
</script>
@endif

@if(session('delete'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        title: 'Eliminado',
        text: @json(session('delete')),
        icon: 'success',
        timer: 2500,
        showConfirmButton: false
    });
});
</script>
@endif

@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function() {

    let errores = @json($errors -> all());

    Swal.fire({
        title: 'Error de validación',
        html: `
        <div style="text-align:center;">
            ${errores.map(e => `<div>${e}</div>`).join('')}
        </div>
    `,
        icon: 'error',
        confirmButtonText: 'Entendido'
    });

});
</script>
@endif


@endpush
@stack('scripts')