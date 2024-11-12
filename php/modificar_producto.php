<?php
session_start();

// Verificar si la sesión del administrador está activa
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["error" => "No tienes permisos para realizar esta acción."]);
    exit();
}

header('Content-Type: application/json');

// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto si es necesario
$password = ""; // Cambia esto si es necesario
$dbname = "registros";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

// Obtener y validar datos de entrada
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
$precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
$descripcion = $conn->real_escape_string($_POST['descripcion'] ?? '');

// Verificar que los datos requeridos no están vacíos o inválidos
if (!$id || empty($nombre) || $precio === false || empty($descripcion)) {
    echo json_encode(["error" => "Datos de entrada inválidos o incompletos."]);
    $conn->close();
    exit();
}

// Preparar y ejecutar la consulta de actualización
$sql = "UPDATE productos SET nombre='$nombre', precio=$precio, descripcion='$descripcion' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "Producto actualizado correctamente"]);
} else {
    echo json_encode(["error" => "Error actualizando producto: " . $conn->error]);
}

// Cerrar conexión
$conn->close();
?>
