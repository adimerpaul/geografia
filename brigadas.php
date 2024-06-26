<?php include 'includes/header.php'; ?>
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Brigadas
                <small>Panel de control</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Brigadas</a></li>
            </ol>
        </section>
        <section class="content">
            <div class="box">
                <div class="box-body no-padding">
                    <div class="text-right" style="padding: 10px;">
                        <button type="button" class="btn btn-success" id="nuevo">
                            <i class="fa fa-plus"></i> Agregar
                        </button>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">Opciones</th>
                                <th style="width: 10px">#</th>
                                <th>Tipo</th>
                                <th>Descripcion</th>
                                <th>Username</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody id="brigadaes">
                        </tbody>
                    </table>
                    <div class="modal fade" id="modal-default">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Nuevo Brigada</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="descripcion" class="col-sm-2 control-label">Descripcion</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" id="descripcion" placeholder="Descripcion">
                                                </div>
                                                <label for="tipo" class="col-sm-2 control-label">Tipo</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control" id="tipo">
                                                        <option value="MANTENIMIENTO">MANTENIMIENTO</option>
                                                        <option value="SUPERVISOR">SUPERVISOR</option>
                                                        <option value="CONSULTOR MOVIL">CONSULTOR MOVIL</option>
                                                        <option value="VACUNAS">VACUNAS</option>
                                                        <option value="ADMIN">ADMIN</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="username" class="col-sm-2 control-label">Username</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" id="username" placeholder="Username">
                                                </div>
                                                <label for="password" class="col-sm-2 control-label">Password</label>
                                                <div class="col-sm-4">
                                                    <input type="password" class="form-control" id="password" placeholder="Password">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="role" class="col-sm-2 control-label">Role</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control" id="role">
                                                        <option value="ADMIN">ADMIN</option>
                                                        <option value="BRIGADA">BRIGADA</option>
                                                    </select>
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
        $('#modal-default').on('shown.bs.modal', function () {
            // map.invalidateSize();
        });
        idbrigada = 0;
        $('#nuevo').click(function () {
            $('#tipo').val('MANTENIMIENTO');
            $('#descripcion').val('');
            $('#username').val('');
            $('#password').val('');
            $('#role').val('BRIGADA');
            idbrigada = 0;
            $('#modal-default').modal('show');
        });

        $('#guardar').click(function () {
            console.log(idbrigada);
            if (idbrigada === 0) {
                $.ajax({
                    url: 'api.php',
                    type: 'POST',
                    data: {
                        method: 'brigadaPost',
                        tipo: $('#tipo').val(),
                        descripcion: $('#descripcion').val(),
                        username: $('#username').val(),
                        password: $('#password').val(),
                        role: $('#role').val()
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

                        if (brigadaes[i].tipo == 'MANTENIMIENTO') {
                            tipo= '<span class="badge bg-red">MANTENIMIENTO</span>';
                        } else if (brigadaes[i].tipo == 'SUPERVISOR') {
                            tipo= '<span class="badge bg-green">SUPERVISOR</span>';
                        } else if (brigadaes[i].tipo == 'CONSULTOR MOVIL') {
                            tipo= '<span class="badge bg-blue">CONSULTOR MOVIL</span>';
                        } else if (brigadaes[i].tipo == 'VACUNAS') {
                            tipo= '<span class="badge bg-yellow">VACUNAS</span>';
                        } else if (brigadaes[i].tipo == 'ADMIN') {
                            tipo= '<span class="badge bg-purple">ADMIN</span>';
                        }

                        //role
                        if (brigadaes[i].role == 'ADMIN') {
                            role= '<span class="badge bg-red">ADMIN</span>';
                        } else if (brigadaes[i].role == 'BRIGADA') {
                            role= '<span class="badge bg-green">BRIGADA</span>';
                        }

                        html += '<tr>';
                        html += '<td><button type="button" class="btn btn-primary btn-xs" onclick="brigadaEdit(' + brigadaes[i].id + ')"><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-danger btn-xs" onclick="brigadaDelete(' + brigadaes[i].id + ')"><i class="fa fa-trash"></i></button></td>';
                        html += '<td>' + brigadaes[i].id + '</td>';
                        html += '<td>' + tipo + '</td>';
                        html += '<td>' + brigadaes[i].descripcion + '</td>';
                        html += '<td>' + brigadaes[i].username + '</td>';
                        html += '<td>' + role + '</td>';
                        html += '</tr>';
                    }
                    $('#brigadaes').html(html);
                }
            });
        }
    }
</script>
