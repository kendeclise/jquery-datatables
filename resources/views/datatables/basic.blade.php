<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Datatables</title>
    {{-- Css--}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/jquery.datatable/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <style>
        .row-danger{
            background-color: lightcoral !important;
        }

        .row-warning{
            background-color: var(--warning) !important;
        }

        .disabled-dt {
            pointer-events: none;
        }
    </style>
</head>
<body>
<div class="container mt-3">
    <h4 style="text-transform: uppercase">Backend (Modo Básico)</h4>
    <table id="tabla-principal" class="table table-striped table-bordered mt-3">
        <thead>
        <tr>
            <th class="text-center" style="width: 5%">#</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Descripción</th>
            <th class="text-center" style="width: 80px">Acción</th>
        </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td colspan="6">Cargando...</td>
            </tr>
        </tbody>
    </table>
</div>
{{-- Javascript --}}
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/plugins/jquery.datatable/jquery.dataTables.min.js') }}"></script>

<script>
    $.ajaxSetup({
        headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    let datatablePrincipal = null;

    $(document).ready(function() {
        /* Instancio la datatable */
        datatablePrincipal = $('#tabla-principal').DataTable({
            "ajax": {
                "url": '{{ route('datatables.categoriesBasicDatatableFormat') }}'
            },
            "destroy": true,
            "language": {
                "sProcessing": `<i class="fas fa-circle-notch fa-spin fa-2x"></i>`,
                "sLengthMenu": "Mostrar &nbsp;_MENU_ &nbsp;registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "No se cuenta con ninguna categoría",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Filtrar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "searchPlaceholder": "",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "&raquo",
                    "sPrevious": "&laquo"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "columns": [
                {'data': 'index'},
                {'data': 'nombre'},
                {'data': 'descripcion'},
                {'data' : 'accion'},
            ],
            "order": [], /* Orden inicial por defecto */
            "columnDefs": [/* sección donde puedo rederizar o alterar el formato de presentación de las columnas, desactivar búsquedas, el orden, etc.*/
                {
                    "orderable": true,
                    "targets": '_all'/*_all indica para todas las columnas, en este caso le quito el orden a todas las columnas, si deseo enviar una o dos en concreto sería un array [0, 3] de la siguiente forma*/
                },
                {
                    "searchable": false,/* Restrinjo algunas columnas para que no puedan ser buscadas por el input*/
                    "targets": [0, 2, 3]/* En este caso la búsqueda solo sería por el nombre*/
                }
            ],
            "pageLength": 10,
            "bLengthChange": true,//Quita el cambio de pageLength vía la vista
            "bFilter": true, //Esconde el input de búsqueda (por defecto sale en la vista)
            "bInfo": true, //Esconde la información del total de rows y la página actual
            "bPaginate": true,
            "drawCallback": function (oSettings) {//Callback que se ejecuta luego de pintarse la data en la tabla
                //Escondo la paginación si solo hay 1 página:
                /*if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
                    $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                } else {
                    $(oSettings.nTableWrapper).find('.dataTables_paginate').show();
                }*/
            }
        });

    });

    function eliminarCategoria(id, name){
        let resp = confirm(`¿Deseas eliminar la categoria ${name}?`);
        let url = '{{ route('category.destroy', ['id' => ':id']) }}';
        url = url.replace(':id', id);
        if(resp){
            $.ajax({
                url     : url,
                method  : 'DELETE',
                success : function(response){
                    if(response.success){
                        datatablePrincipal.ajax.reload(null, false);//Primer parámetro es un callback, el segundo si pongo false, significa que recarga y sigues en la misma página actual, sino te la posiciona en la página 1
                        alert(`Se eliminó correctamente la categoría ${name}`);
                    }else{
                        alert(`Ocurrió un error interno (${response.error})`);
                    }
                }
            });
        }
    }
</script>
</body>
</html>
