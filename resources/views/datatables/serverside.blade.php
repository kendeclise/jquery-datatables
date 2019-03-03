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
        <h4 style="text-transform: uppercase">Backend (Modo Server-side)</h4>
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <select id="category-filter" class="form-control">
                    <option value="">-SELECCIONE UNA CATEGORÍA-</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ mb_strtoupper($category->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <table id="tabla-principal" class="table table-striped table-bordered mt-3">
            <thead>
                <tr>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Precio de Compra</th>
                    <th class="text-center">Precio de Venta</th>
                    <th class="text-center">Categoría</th>
                    <th class="text-center">Stock</th>
                    <th class="text-center" style="width: 80px">Acción</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr>
                    <td colspan="6"></td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                </tr>
                <tr>
                    <td colspan="6">CARGANDO ...</td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">
                        <div class="row">
                            <div class="col-6 text-left">
                                <span class="font-weight-bold">Total de filas:</span><span class="font-weight-bold text-danger ml-3" id="datatable-rows">--</span>
                            </div>
                            <div class="col-6 text-right">
                                <span class="font-weight-bold">Datos actualizados el:</span><span class="font-weight-bold text-danger ml-3" id="datatable-server-date">--/--/-- --:-- --</span>
                            </div>
                        </div>
                    </th>
                </tr>
            </tfoot>
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
                    "url": '{{ route('datatables.productsDatatableServerSideFormat') }}',
                    "type": "get",
                    'beforeSend': function (request) {// acciones usadas antes del envío de datos
                        $('#tabla-principal').css('opacity', '0.5');
                        $('#datatable-rows').text("--");
                        $('#datatable-server-date').text("--/--/-- --:-- --");
                        $("#tabla-principal tbody").addClass("disabled-dt");
                        $(".dataTables_paginate ").addClass("disabled-dt");
                    },
                    dataSrc: function (data) {//Data adicional recibida en la respuesta
                        // EJemplo:
                        let fechaServidor = data.fechaServidor;
                        $('#datatable-rows').text(data.recordsFiltered);
                        $('#datatable-server-date').text(fechaServidor);
                        return data.data;//Siempre tiene que finalizar con return data.data
                    },
                    data: function (params) {//parámetros de envío que irán al backend
                        params.categoryId = $('#category-filter').val();
                    }
                },
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "language": {
                    "sProcessing": `<i class="fas fa-circle-notch fa-spin fa-2x"></i>`,
                    "sLengthMenu": "Mostrar &nbsp;_MENU_ &nbsp;registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "No se cuenta con ningún producto",
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
                    {'data': 'nombre'},
                    {'data': 'precio_compra'},
                    {'data': 'precio_venta'},
                    {'data': 'categoria'},
                    {'data': 'stock'},
                    {'data' : 'accion'},
                ],
                "order": [], /* Orden inicial por defecto */
                "columnDefs": [/* sección donde puedo rederizar o alterar el formato de presentación de las columnas, desactivar búsquedas, el orden, etc.*/
                    /*{
                        "orderable": false,
                        "targets": '_all'/!*_all indica para todas las columnas, en este caso le quito el orden a todas las columnas, si deseo enviar una o dos en concreto sería un array [0, 3] de la siguiente forma*!/
                    },*/
                    {
                        "orderable": false,
                        "targets": [1, 2, 3, 5]
                    },
                ],
                "pageLength": 10,
                "bLengthChange": false,//Quita el cambio de pageLength vía la vista
                "bFilter": false, //Esconde el input de búsqueda (por defecto sale en la vista)
                "bInfo": false, //Esconde la información del total de rows y la página actual
                "bPaginate": true,
                "drawCallback": function (oSettings) {//Callback que se ejecuta luego de pintarse la data en la tabla
                    //Escondo la paginación si solo hay 1 página:
                    if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
                        $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                    } else {
                        $(oSettings.nTableWrapper).find('.dataTables_paginate').show();
                    }

                    $('#tabla-principal').css('opacity', '1');
                    $("#tabla-principal tbody").removeClass("disabled-dt");
                    $(".dataTables_paginate ").removeClass("disabled-dt");
                },
                "createdRow": function (row, data, index) {//Cada vez que se crea un row, puedes alterarla
                    // Ejemplo: (le añado la clase row-danger a todos los productos con stock 0), a los que son mayores a 0 pero menores a 20 les agrego el row-warning
                    if (data.stock <= 0) {
                        $(row).addClass('row-danger');
                    }else if (data.stock <= 20) {
                        $(row).addClass('row-warning');
                    }
                }
            });

            /* Evento cambio de category-filter */
            $('#category-filter').change(function(){
                datatablePrincipal.ajax.reload(null, true);
            });

        });

        function eliminarProducto(id, name){
            let resp = confirm(`¿Deseas eliminar el producto ${name}?`);
            let url = '{{ route('products.destroy', ['id' => ':id']) }}';
            url = url.replace(':id', id);
            if(resp){
                $.ajax({
                    url     : url,
                    method  : 'DELETE',
                    success : function(response){
                        if(response.success){
                            datatablePrincipal.ajax.reload(null, false);//Primer parámetro es un callback, el segundo si pongo false, significa que recarga y sigues en la misma página actual, sino te la posiciona en la página 1
                            alert(`Se eliminó correctamente el producto ${name}`);
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
