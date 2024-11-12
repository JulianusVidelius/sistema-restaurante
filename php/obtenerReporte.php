<?php
require 'db_connection.php';

$conn = openConnection();

// Contar pedidos totales
$sqlTotal = "SELECT COUNT(*) as total FROM pedidos";
$resultTotal = $conn->query($sqlTotal);
$totalPedidos = $resultTotal->fetch_assoc()['total'];

// Contar pedidos pendientes
$sqlPendientes = "SELECT COUNT(*) as pendientes FROM pedidos WHERE estado = 'pendiente'";
$resultPendientes = $conn->query($sqlPendientes);
$pendientes = $resultPendientes->fetch_assoc()['pendientes'];

// Contar pedidos entregados
$sqlEntregados = "SELECT COUNT(*) as entregados FROM pedidos WHERE estado = 'entregado'";
$resultEntregados = $conn->query($sqlEntregados);
$entregados = $resultEntregados->fetch_assoc()['entregados'];

$conn->close();

// Enviar respuesta en formato JSON
echo json_encode([
    "total" => $totalPedidos,
    "pendientes" => $pendientes,
    "entregados" => $entregados
]);
?>