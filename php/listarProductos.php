<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registros";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM productos WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
        echo json_encode($producto);
    } else {
        echo json_encode(['error' => 'Producto no encontrado']);
    }
} else {
    $sql = "SELECT id, nombre FROM productos";
    $result = $conn->query($sql);

    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    echo json_encode(['productos' => $productos]);
}

$conn->close();
?>