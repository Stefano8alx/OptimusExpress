<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombreEmpleado'])) {
    $nombre = trim($_POST['nombreEmpleado']);

    if (empty($nombre)) {
        echo "❌ El nombre del empleado no puede estar vacío.";
        exit;
    }

    // Prevenir inyección SQL
    $stmt = $conn->prepare("INSERT INTO empleados (nombre) VALUES (?)");
    $stmt->bind_param("s", $nombre);

    if ($stmt->execute()) {
        echo "✅ Empleado registrado correctamente.";
    } else {
        echo "❌ Error al registrar empleado: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>