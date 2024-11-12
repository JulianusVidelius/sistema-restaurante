<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "No se ha iniciado sesión."]);
    exit;
}

$userId = $_SESSION['user_id'];
$conn = openConnection();

// Obtener el nombre del usuario
$sql = "SELECT nombre FROM login WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($nombreUsuario);
$stmt->fetch();
$stmt->close();

// Obtener los pedidos del usuario
$sql = "SELECT id, productos FROM pedidos WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
}

$stmt->close();
closeConnection($conn);

// Devolver los datos en formato JSON
echo json_encode(["nombre_usuario" => $nombreUsuario, "pedidos" => $pedidos]);
?>
