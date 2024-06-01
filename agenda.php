<?php include 'includes/header.php'; ?>
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Agenda
                <small>Panel de control</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Agenda</a></li>
            </ol>
        </section>
        <section class="content">
            <div class="box">
                <div class="box-body no-padding">
                    <div class="text-right" style="padding: 10px;">
                        <button type="button" class="btn btn-success" id="nuevo">
                            <i class="fa fa-plus"></i> Agregar Agenda
                        </button>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">Opciones</th>
                                <th style="width: 10px">#</th>
                                <th>Brigada</th>
                                <th>Lugares</th>
                                <th>Fecha</th>
                                <th>Observacion</th>
                            </tr>
                        </thead>
                        <tbody id="brigadaes">
                        </tbody>
                    </table>
                    <div class="modal fade" id="modal-default">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-green">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Nuevo Agenda</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="brigada" class="col-sm-2 control-label">Brigadas</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control" id="brigada">
                                                    </select>
                                                </div>
                                                <label for="lugares" class="col-sm-2 control-label">Lugares</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control select2" multiple="multiple" data-placeholder="Selecionar lugares" id="lugares" style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="fecha" class="col-sm-2 control-label">Fecha</label>
                                                <div class="col-sm-4">
                                                    <input type="date" class="form-control" id="fecha" placeholder="Fecha">
                                                </div>
                                                <label for="observacion" class="col-sm-2 control-label">Observacion</label>
                                                <div class="col-sm-4">
                                                    <input class="form-control" id="observacion" placeholder="Observacion">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button>
                                            <button type="button" class="btn btn-primary" id="guardar">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php include 'includes/footer.php'; ?>
<script>
    window.onload = function () {
        $('.select2').select2();
        $('#modal-default').on('shown.bs.modal', function () {
            // map.invalidateSize();
        });
        idbrigada = 0;
        $('#nuevo').click(function () {
            $('#fecha').val();
            $('#brigada').val('');
            $('#lugares').val('');
            $('#observacion').val('');
            idbrigada = 0;
            $('#modal-default').modal('show');
        });

        $('#guardar').click(function () {
            if ($('#fecha').val() == '' || $('#fecha').val() == null) {
                alert('La fecha es requerida');
                return false;
            }
            console.log($('#brigada').val());
            if ($('#brigada').val() == '' || $('#brigada').val() == null) {
                alert('La brigada es requerida');
                return false;
            }
            if ($('#lugares').val() == '' || $('#lugares').val() == null) {
                alert('El lugar es requerido');
                return false;
            }

            if (idbrigada === 0) {
                $.ajax({
                    url: 'api.php',
                    type: 'POST',
                    data: {
                        method: 'agendaPost',
                        brigada: $('#brigada').val(),
                        lugares: $('#lugares').val(),
                        fecha: $('#fecha').val(),
                        observacion: $('#observacion').val()
                    },
                    success: function (response) {
                        brigadaGet();
                        $('#modal-default').modal('hide');
                    }
                });

            }else{
                $.ajax({
                    url: 'api.php',
                    type: 'POST',
                    data: {
                        method: 'brigadaPut',
                        tipo: $('#tipo').val(),
                        descripcion: $('#descripcion').val(),
                        username: $('#username').val(),
                        password: $('#password').val(),
                        role: $('#role').val(),
                        id: idbrigada
                    },
                    success: function (response) {
                        brigadaGet();
                        $('#modal-default').modal('hide');
                    }
                });
            }
        });
        //que reconosco elimnar brigada
        window.brigadaDelete = function (id) {
            if (!confirm('¿Está seguro de eliminar el brigada?')) {
                return false;
            }
            $.ajax({
                url: 'api.php',
                type: 'POST',
                data: {
                    method: 'brigadaDelete',
                    id: id
                },
                success: function (response) {
                    brigadaGet();
                }
            });
        }
        window.brigadaEdit = function (id) {
            idbrigada = id;
            $.ajax({
                url: 'api.php',
                type: 'GET',
                data: {
                    method: 'brigadaGet',
                    id: id
                },
                success: function (response) {
                    var brigada = JSON.parse(response);
                    $('#tipo').val(brigada.tipo);
                    $('#descripcion').val(brigada.descripcion);
                    $('#username').val(brigada.username);
                    $('#password').val(brigada.password);
                    $('#role').val(brigada.role);
                    $('#modal-default').modal('show');
                }
            });
        }
        brigadaGet();
        entidadesGet();
        function brigadaGet() {
            $.ajax({
                url: 'api.php',
                type: 'GET',
                data: {
                    method: 'brigadasGet'
                },
                success: function (response) {
                    var brigadaes = JSON.parse(response);
                    var html = '';
                    for (var i = 0; i < brigadaes.length; i++) {
                        html += '<option value="' + brigadaes[i].id + '">' + brigadaes[i].descripcion + '</option>';
                    }
                    $('#brigada').html(html);
                }
            });
        }
        function entidadesGet() {
            $.ajax({
                url: 'api.php',
                type: 'GET',
                data: {
                    method: 'lugareGet'
                },
                success: function (response) {
                    var entidades = JSON.parse(response);
                    var html = '';
                    for (var i = 0; i < entidades.length; i++) {
                        html += '<option value="' + entidades[i].id + '">' + entidades[i].descripcion + '</option>';
                    }
                    $('#lugares').html(html);
                }
            });
        }
    }
</script>
