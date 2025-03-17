<?php
include 'db.php';

if (isset($_GET['idEmpleado']) && isset($_GET['fechaSeleccionada'])) {
    $idEmpleado = $_GET['idEmpleado'];
    $fecha = $_GET['fechaSeleccionada'];
    $fechaFormateada = date("Y-m-d", strtotime($fecha));

    // Sumar los lavados del empleado en la fecha específica
    $sql = "SELECT SUM(precio_lavado) as total 
            FROM lavados 
            WHERE id_empleado = ? AND DATE(fecha) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $idEmpleado, $fechaFormateada);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fila = $result->fetch_assoc();
        $total = $fila['total'];
        $cuarentaPorciento = $total * 0.4;
        echo "40% del empleado $idEmpleado en $fecha: $" . number_format($cuarentaPorciento, 2);
    } else {
        echo "No hay lavados para este empleado en la fecha seleccionada.";
    }

    $stmt->close();
} else {
    echo "Datos incompletos.";
}

$conn->close();
?>