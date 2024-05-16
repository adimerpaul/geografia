<?php include 'includes/header.php'; ?>
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Lugares
                <small>Panel de control</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Lugares</a></li>
            </ol>
        </section>
        <section class="content">
            <div class="box">
                <div class="box-body no-padding">
                    <div class="text-right" style="padding: 10px;">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                            <i class="fa fa-plus"></i> Agregar
                        </button>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">Opciones</th>
                                <th style="width: 10px">#</th>
                                <th>Descripcion</th>
                                <th>Prioridad</th>
                                <th>Tipo</th>
                                <th style="width: 40px">Ubicación</th>
                            </tr>
                        </thead>
                        <tbody id="lugares">

                        </tbody>
                    </table>
                    <div class="modal fade" id="modal-default">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Nuevo Lugar</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="descripcion" class="col-sm-2 control-label">Descripcion</label>

                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" id="descripcion" placeholder="Descripcion">
                                                </div>
                                                <label for="prioridad" class="col-sm-2 control-label">Prioridad</label>
                                                <div class="col-sm-4">
                                                    <select type="text" class="form-control" id="prioridad">
                                                        <option value="0">Destino</option>
                                                        <option value="1">Origen</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="tipo" class="col-sm-2 control-label">Tipo</label>
                                                <div class="col-sm-4">
                                                    <select type="text" class="form-control" id="tipo">
                                                        <option value="CINFA">CINFA</option>
                                                        <option value="HOSPITAL">HOSPITAL</option>
                                                        <option value="ALMACEN">ALMACEN</option>
                                                        <option value="EXTERNO">EXTERNO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="map" style="width: 100%; height: 400px;"></div>
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
            $('#descripcion').val('');
            $('#prioridad').val('0');

            map.invalidateSize();
        });
        var orurolat = -17.964888;
        var orurolng = -67.115827;
        var map = L.map('map').setView([orurolat, orurolng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);
        var marker = L.marker([orurolat, orurolng], {
            draggable: true
        }).addTo(map);
        map.on('click', function (e) {
            orurolat = e.latlng.lat;
            orurolng = e.latlng.lng;
            marker.setLatLng(e.latlng);
        });
        $('#guardar').click(function () {
            $.ajax({
                url: 'api.php',
                type: 'POST',
                data: {
                    method: 'lugarPost',
                    descripcion: $('#descripcion').val(),
                    prioridad: $('#prioridad').val(),
                    tipo: $('#tipo').val(),
                    lat: orurolat,
                    lng: orurolng
                },
                success: function (response) {
                    lugarGet();
                    $('#modal-default').modal('hide');
                }
            });
        });
        //que reconosco elimnar lugar
        window.lugarDelete = function (id) {
            if (!confirm('¿Está seguro de eliminar el lugar?')) {
                return false;
            }
            $.ajax({
                url: 'api.php',
                type: 'POST',
                data: {
                    method: 'lugarDelete',
                    id: id
                },
                success: function (response) {
                    lugarGet();
                }
            });
        }
        lugarGet();
        function lugarGet() {
            $.ajax({
                url: 'api.php',
                type: 'GET',
                data: {
                    method: 'lugareGet'
                },
                success: function (response) {
                    var lugares = JSON.parse(response);
                    var html = '';
                    for (var i = 0; i < lugares.length; i++) {
                        html += '<tr>';
                        html += '<td><button type="button" class="btn btn-primary btn-xs" onclick="lugarEdit(' + lugares[i].id + ')"><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-danger btn-xs" onclick="lugarDelete(' + lugares[i].id + ')"><i class="fa fa-trash"></i></button></td>';
                        html += '<td>' + lugares[i].id + '</td>';
                        html += '<td>' + lugares[i].descripcion + '</td>';
                        html += '<td>' + (lugares[i].prioridad == 0 ? 'Destino' : 'Origen') + '</td>';
                        html += '<td>' + lugares[i].tipo + '</td>';
                        html += '<td><a href="https://www.google.com/maps/search/?api=1&query=' + lugares[i].latitud + ',' + lugares[i].longitud + '" target="_blank"><i class="fa fa-map-marker"></i></a></td>';
                        html += '</tr>';
                    }
                    $('#lugares').html(html);
                }
            });
        }
    }
</script>
