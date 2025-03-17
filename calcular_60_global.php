<?php
include 'db.php';

if (isset($_GET['fechaSeleccionada'])) {
    $fecha = $_GET['fechaSeleccionada'];
    $fechaFormateada = date("Y-m-d", strtotime($fecha));

    // Sumar todos los lavados del día
    $sql = "SELECT SUM(precio_lavado) as total 
            FROM lavados 
            WHERE DATE(fecha) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $fechaFormateada);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fila = $result->fetch_assoc();
        $total = $fila['total'];
        $sesentaPorciento = $total * 0.6;
        echo "60% del total de lavados en $fecha: $" . number_format($sesentaPorciento, 2);
    } else {
        echo "No hay lavados registrados para esta fecha.";
    }

    $stmt->close();
} else {
    echo "Fecha no proporcionada.";
}

$conn->close();
?>