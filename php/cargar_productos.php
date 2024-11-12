<?php
include 'db_connection.php';

$conn = openConnection();

$categoriaId = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : 0;

if ($categoriaId && $categoriaId != 0) {
    $sql = "SELECT * FROM productos WHERE categoria_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $categoriaId);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM productos";
    $result = $conn->query($sql);
}

$productos = array();
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode($productos);

closeConnection($conn);
?>