<?php
session_start();
require_once 'config.php';
// Verifica si se enviaron datos de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si se proporcionaron el nombre de usuario y la contraseña
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Obtiene los datos del formulario
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM brigada WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['username'] = $username;
            header("location: welcome.php");
        } else {
            header("location: index.php");
        }
        // Aquí deberías agregar la lógica para verificar el usuario y la contraseña en tu base de datos
        // Por ejemplo, puedes hacer una consulta SQL para buscar un usuario con el nombre de usuario dado y verificar su contraseña

        // Si el usuario y la contraseña son válidos, puedes establecer una variable de sesión o redireccionar a otra página
        // Por ejemplo:
        // $_SESSION['username'] = $username;
        // header("location: welcome.php");

        // Por ahora, simplemente imprime los datos para verificar
        echo "Usuario: $username, Contraseña: $password";
    } else {
        // Si no se proporcionaron el nombre de usuario y la contraseña, muestra un mensaje de error
        echo "Por favor, ingresa el nombre de usuario y la contraseña.";
    }
}
?>
