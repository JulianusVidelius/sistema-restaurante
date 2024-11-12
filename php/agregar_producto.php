<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria']; // Nueva variable para la categoría

    $imagen_nombre = $_FILES['imagen']['name'];
    $imagen_temp = $_FILES['imagen']['tmp_name'];
    $imagen_ruta = '../img/' . basename($imagen_nombre);

    if (move_uploaded_file($imagen_temp, $imagen_ruta)) {
        $conn = openConnection();

        $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria, imagen) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssdss", $nombre, $descripcion, $precio, $categoria, $imagen_nombre);

            if ($stmt->execute()) {
                echo "Nuevo producto agregado correctamente.";
            } else {
                echo "Error al agregar el producto: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error al preparar la declaración: " . $conn->error;
        }

        closeConnection($conn);
    } else {
        echo "Error al cargar la imagen.";
    }
}
?>