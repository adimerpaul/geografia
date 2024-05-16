<?php
include 'config.php';

$method= $_REQUEST['method'];

if ($method == 'lugareGet') {
    $sql = "SELECT * FROM entidad";
    $result = $conexion->query($sql);
    $lugares = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $lugares[] = $row;
        }
    }
    echo json_encode($lugares);
}
if ($method == 'lugarPost') {
    $descripcion = $_POST['descripcion'];
    $prioridad = $_POST['prioridad'];
    $tipo = $_POST['tipo'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $sql = "INSERT INTO entidad (descripcion, prioridad, tipo, latitud, longitud) VALUES ('$descripcion', '$prioridad', '$tipo', '$lat', '$lng')";
    if ($conexion->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}
if ($method == 'lugarDelete') {
    $id = $_POST['id'];
    $sql = "DELETE FROM entidad WHERE id = $id";
    if ($conexion->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}
