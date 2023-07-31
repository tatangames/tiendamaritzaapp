@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
</style>

<div id="divcontenedor" style="display: none">
    <section class="content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <h1>Todos los Permisos</h1>
            </div>
            <br>
            <button type="button" onclick="modalAgregar()" class="btn btn-success btn-sm">
                <i class="fas fa-pencil-alt"></i>
                Agregar Permiso
            </button>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Lista</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="tablaDatatable"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="modalAgregar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo Permiso</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-nuevo">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>Esta acción agregara el "Permiso", pero se debera modificar el sistema para su utilización.</p>

                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" maxlength="200" class="form-control" id="nombre-nuevo" placeholder="Nombre">
                                    </div>

                                    <div class="form-group">
                                        <label>Descripción</label>
                                        <input type="text" maxlength="200" class="form-control" id="descripcion-nuevo" placeholder="Descripción">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" onclick="agregarPermiso()">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBorrar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Borrar Permiso Global</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-borrar">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>Esta acción eliminara el Permiso en "Todos los Roles."</p>

                                    <div class="form-group">
                                        <input type="hidden" id="idborrar">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger" onclick="borrar()">Borrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}" type="text/javascript"></script>

    <!-- incluir tabla -->
    <script type="text/javascript">
        $(document).ready(function(){
            var ruta = "{{ url('/admin/roles/permisos-todos/tabla') }}";
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function agregarPermiso(){
            var nombre = document.getElementById('nombre-nuevo').value;
            var descripcion = document.getElementById('descripcion-nuevo').value;

            if(nombre === ''){
                toastr.error('Nombre es requerido')
                return;
            }

            if(nombre.length > 200){
                toastr.error('Máximo 200 caracteres para Nombre')
                return;
            }

            if(descripcion === ''){
                toastr.error('Descripción es requerido')
                return;
            }

            if(descripcion.length > 200){
                toastr.error('Máximo 200 caracteres para Descripción')
                return;
            }

            var formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('descripcion', descripcion);

            axios.post(url+'/permisos/extra-nuevo', formData, {
            })
                .then((response) => {
                    closeLoading()
                    $('#modalAgregar').modal('hide');

                    if(response.data.success === 1){
                        alertaMensaje('warning', 'Nombre Repetido', 'Cambiar el nombre del Permiso');
                    }
                    else if(response.data.success === 2){
                        toastr.success('Permiso agregado');
                        recargar();
                    }
                    else{
                        toastr.error('Error al agregar');
                    }
                })
                .catch((error) => {
                    closeLoading()
                    toastr.error('Error al agregar');
                });
        }



        // se recibe el ID del permiso a eliminar
        function modalBorrar(id){
            $('#idborrar').val(id);
            $('#modalBorrar').modal('show');
        }

        function borrar(){
            openLoading()
            // se envia el ID del permiso
            var idpermiso = document.getElementById('idborrar').value;

            var formData = new FormData();
            formData.append('idpermiso', idpermiso);

            axios.post(url+'/permisos/extra-borrar', formData, {
            })
                .then((response) => {
                    closeLoading()
                    $('#modalBorrar').modal('hide');

                    if(response.data.success === 1){
                        toastr.success('Permiso globalmente eliminado');
                        recargar();
                    }else{
                        toastr.error('Error al eliminar');
                    }
                })
                .catch((error) => {
                    closeLoading()
                    toastr.error('Error al eliminar');
                });
        }

        function recargar(){
            var ruta = "{{ url('/admin/roles/permisos-todos/tabla') }}";
            $('#tablaDatatable').load(ruta);
        }

    </script>



@stop
