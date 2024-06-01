<?php include 'includes/header.php'; ?>
<style>
    #refreshButton {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 10px;
        z-index: 400;
    }
</style>
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Rutas
                <small>Panel de control</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Rutas</a></li>
            </ol>
        </section>
        <section class="content">
            <div class="box">
                <div class="box-body no-padding">
                    <div>
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" id="brigadas">
                                    <option value="">Seleccione una brigada</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" id="fecha">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-info" id="search">
                                    <i class="fa fa-search"></i> &nbsp;
                                    Buscar
                                </button>
                                </button>
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" id="observacion" placeholder="Observación" readonly>
                            </div>
                        </div>
                    </div>
                    <div id='map' style="height: calc(100vh - 230px);">
                        <div class="overlay">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                    </div>
<!--                    <button id="refreshButton">Refresh Button</button>-->
                </div>
            </div>
        </section>
    </div>
<?php include 'includes/footer.php'; ?>
<script>
window.onload = function () {
    $('#fecha').val(moment().format('YYYY-MM-DD'));
    const map = L.map('map');

    const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    map.setView([-17.9600, -67.1200], 13);



    brigadasGet();
    function brigadasGet() {
        $.ajax({
            url: 'api.php',
            method: 'GET',
            data: {
                method: 'brigadasGet'
            },
            success: function (response) {
                const brigadas = JSON.parse(response);
                brigadas.forEach(function (brigada) {
                    $('#brigadas').append(`<option value="${brigada.id}">${brigada.descripcion}</option>`);
                });
            }
        });
    }
    var routingControl; // Variable global para almacenar el control de la ruta

    $('#search').click(function () {
        const brigada = $('#brigadas').val();
        const fecha = $('#fecha').val();

        // Verificación de entradas
        if (brigada === '') {
            alert('Seleccione una brigada');
            return;
        }
        if (fecha === '') {
            alert('Seleccione una fecha');
            return;
        }

        // Solicitud AJAX
        $.ajax({
            url: 'api.php',
            method: 'GET',
            data: {
                method: 'agendaSearch',
                id: brigada,
                fecha: fecha
            },
            success: function (response) {
                const result = JSON.parse(response);

                // Eliminar el control de la ruta anterior si existe
                if (routingControl) {
                    map.removeControl(routingControl);
                }

                // Eliminar todos los marcadores y polilíneas del mapa
                map.eachLayer(function (layer) {
                    if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                        map.removeLayer(layer);
                    }
                });

                // Manejo de respuesta vacía
                if (result.length === 0) {
                    alert('No se encontraron rutas');
                    return;
                }

                $('#observacion').val(result.observacion);
                console.log(result);

                const waypoints = [];
                result.rutas.forEach(function (ruta) {
                    const latLng = L.latLng(parseFloat(ruta.latitud), parseFloat(ruta.longitud));
                    waypoints.push(latLng);
                });

                // Crear y agregar el control de la ruta
                routingControl = L.Routing.control({
                    waypoints: waypoints,
                    draggableWaypoints: false,
                }).addTo(map);

                result.rutas.forEach(function (ruta) {
                    const latLng = L.latLng(parseFloat(ruta.latitud), parseFloat(ruta.longitud));

                    // como colocar al frnte
                    const marker = L.marker(latLng).addTo(map);
                    marker.bindPopup(ruta.descripcion);
                    marker.on('mouseover', function () {
                        marker.openPopup();
                    });
                    marker.on('click', function () {
                        const googleMapsUrl = `https://www.google.com/maps?q=${latLng.lat},${latLng.lng}`;
                        console.log(`Opening URL: ${googleMapsUrl}`); // Para depurar
                        window.open(googleMapsUrl, '_blank');
                    });
                });

                // Agregar control de errores para la ruta
                L.Routing.errorControl(routingControl).addTo(map);
            }
        });
    });

    // function onLocationFound(e) {
    //     const radius = e.accuracy / 2;
    //
    //     const locationMarker = L.marker(e.latlng).addTo(map)
    //         .bindPopup(`You are within ${radius} meters from this point`).openPopup();
    //
    //     const locationCircle = L.circle(e.latlng, radius).addTo(map);
    // }
    //
    // function onLocationError(e) {
    //     alert(e.message);
    // }
    //
    // map.on('locationfound', onLocationFound);
    // map.on('locationerror', onLocationError);

    // map.locate({setView: true, maxZoom: 16});
}
</script>
