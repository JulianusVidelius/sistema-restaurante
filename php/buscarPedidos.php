<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['error' => 'No se ha iniciado sesión como admin.']);
    exit;
}

if (!isset($_GET['query']) || empty(trim($_GET['query']))) {
    echo json_encode(['error' => 'Por favor, ingrese un nombre de usuario o ID válido.']);
    exit;
}

$query = trim($_GET['query']);
$conn = openConnection();

if (is_numeric($query)) {
    $stmt = $conn->prepare("SELECT id, usuario FROM login WHERE id = ?");
    $stmt->bind_param("i", $query);
} else {
    $stmt = $conn->prepare("SELECT id, usuario FROM login WHERE usuario LIKE ?");
    $likeQuery = "%" . $query . "%";
    $stmt->bind_param("s", $likeQuery);
}

$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    echo json_encode(['error' => 'No se encontró un usuario con ese nombre o ID.']);
    exit;
}

$userId = $usuario['id'];
$userName = $usuario['usuario'];

$stmt = $conn->prepare("SELECT id, productos FROM pedidos WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $pedidos[] = ['id' => $row['id'], 'productos' => $row['productos'], 'usuario' => $userName];
}

echo json_encode(['pedidos' => $pedidos]);

$stmt->close();
closeConnection($conn);
?>