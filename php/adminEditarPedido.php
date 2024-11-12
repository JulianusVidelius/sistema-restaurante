<?php
require 'db_connection.php';

$id = intval($_GET['id']);
$productosJson = $_POST['productos'];
$productos = json_decode($productosJson, true);

if (!$productos) {
    echo "Formato de productos incorrecto.";
    exit;
}

$productosStr = implode(', ', $productos['productos']);

$conn = openConnection();

$sql = "UPDATE pedidos SET productos = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $productosStr, $id);

if ($stmt->execute()) {
    echo "Pedido actualizado correctamente";
} else {
    echo "Error actualizando el pedido: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>