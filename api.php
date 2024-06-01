<?php
include 'config.php';

$method= $_REQUEST['method'];
if ($method == 'agendaGet') {
    $sql = "SELECT * FROM agenda";
    $result = $conexion->query($sql);
    $agenda = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $agenda[] = $row;
        }
    }
    echo json_encode($agenda);
}
if ($method == 'agendaPost') {
    $brigada = $_POST['brigada'];

    $fecha_agenda = $_POST['fecha'];
    $observacion = $_POST['observacion'];
    $fecha_registro = date('Y-m-d H:i:s');
    $sql = "INSERT INTO agenda (idbrigada,fecha_registro,fecha_agenda,observacion) VALUES ('$brigada','$fecha_registro','$fecha_agenda','$observacion')";
    $lugares = $_POST['lugares'];

    if ($conexion->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}
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
if ($method == 'lugarGet') {
    $id = $_REQUEST['id'];
    $sql = "SELECT * FROM entidad WHERE id = $id";
    $result = $conexion->query($sql);
    $lugar = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $lugar = $row;
        }
    }
    echo json_encode($lugar);
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
if ($method == 'lugarPut') {
    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];
    $prioridad = $_POST['prioridad'];
    $tipo = $_POST['tipo'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $sql = "UPDATE entidad SET descripcion = '$descripcion', prioridad = '$prioridad', tipo = '$tipo', latitud = '$lat', longitud = '$lng' WHERE id = $id";
    if ($conexion->query($sql) === TRUE) {
        echo "Record updated successfully";
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

if ($method == 'brigadasGet') {
    $sql = "SELECT * FROM brigada";
    $result = $conexion->query($sql);
    $brigadas = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $brigadas[] = $row;
        }
    }
    echo json_encode($brigadas);
}
if ($method == 'brigadaGet') {
    $id = $_REQUEST['id'];
    $sql = "SELECT * FROM brigada WHERE id = $id";
    $result = $conexion->query($sql);
    $brigada = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $brigada = $row;
        }
    }
    echo json_encode($brigada);
}
if ($method == 'brigadaPost') {
    $tipo = $_POST['tipo'];
    $descripcion = $_POST['descripcion'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $sql = "INSERT INTO brigada (tipo,descripcion,username,password,role) VALUES ('$tipo','$descripcion','$username','$password','$role')";
    if ($conexion->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}
if ($method == 'brigadaPut') {
    $id = $_POST['id'];
    $tipo = $_POST['tipo'];
    $descripcion = $_POST['descripcion'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $sql = "UPDATE brigada SET tipo = '$tipo', descripcion = '$descripcion', username = '$username', password = '$password', role = '$role' WHERE id = $id";
    if ($conexion->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}
if ($method == 'brigadaDelete') {
    $id = $_POST['id'];
    $sql = "DELETE FROM brigada WHERE id = $id";
    if ($conexion->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}
