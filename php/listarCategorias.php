<?php
include 'db_connection.php';

$conn = openConnection();
$sql = "SELECT id, nombre FROM categorias";
$result = $conn->query($sql);

$categorias = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
} elseif ($result === false) {
    // Manejo de error en caso de fallo en la consulta
    echo json_encode(['error' => 'Error al obtener categorías: ' . $conn->error]);
    closeConnection($conn);
    exit;
}

header('Content-Type: application/json');
echo json_encode(['categorias' => $categorias]);

closeConnection($conn);
?>
