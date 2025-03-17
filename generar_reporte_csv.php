<?php
include 'db.php';

if (isset($_GET['fechaSeleccionada'])) {
    $fecha = $_GET['fechaSeleccionada'];
    $fechaFormateada = date("Y-m-d", strtotime($fecha));

    // Cabeceras para descargar CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="Reporte_Del_Dia_' . $fecha . '.csv"');

    $output = fopen('php://output', 'w');

    // ==== CABECERA ESTILIZADA ====
    fputcsv($output, ["REPORTE DETALLADO - OPTIMUS EXPRESS"]);
    fputcsv($output, ["Fecha:", $fecha]);
    fputcsv($output, []);
    fputcsv($output, []);  // Línea vacía después del título

    // ==== EMPLEADOS ACTIVOS ====
    fputcsv($output, ["EMPLEADOS ACTIVOS"]);
    fputcsv($output, []);  // Línea vacía después del título
    fputcsv($output, ["ID", "Nombre", "Total Lavados", "Total Ganado", "40% (Empleado)", "60% (Empresa)"]);

    // Consulta para empleados activos
    $sqlEmpleados = "SELECT e.id, e.nombre, COUNT(l.id) as lavados, SUM(l.precio_lavado) as ganado 
                     FROM empleados e 
                     JOIN lavados l ON e.id = l.id_empleado 
                     WHERE DATE(l.fecha) = ? 
                     GROUP BY e.id";
    $stmt = $conn->prepare($sqlEmpleados);
    
    if ($stmt === false) {
        die("Error en la consulta: " . $conn->error); // Manejo de errores
    }

    $stmt->bind_param("s", $fechaFormateada);
    $stmt->execute();
    $result = $stmt->get_result();

    $totalGeneral40 = 0;
    $totalGeneral60 = 0;

    if ($result->num_rows > 0) {
        while ($empleado = $result->fetch_assoc()) {
            $ganado = $empleado['ganado'];
            $porcentaje40 = $ganado * 0.4;
            $porcentaje60 = $ganado * 0.6;

            $totalGeneral40 += $porcentaje40;
            $totalGeneral60 += $porcentaje60;

            fputcsv($output, [
                $empleado['id'],
                $empleado['nombre'],
                $empleado['lavados'],
                "$ " . number_format($ganado, 2),
                "$ " . number_format($porcentaje40, 2),
                "$ " . number_format($porcentaje60, 2)
            ]);
        }
    } else {
        fputcsv($output, ["No hay empleados activos para esta fecha."]);
    }
   
    fputcsv($output, []);  // Línea vacía después del título
    // ==== RESUMEN FINANCIERO ====
    fputcsv($output, []);
    fputcsv($output, ["RESUMEN FINANCIERO"]);
    fputcsv($output, []);  // Línea vacía después del título
    fputcsv($output, ["Total 40% (Empleados)", "", "", "", "$ " . number_format($totalGeneral40, 2)]);
    fputcsv($output, ["Total 60% (Empresa)", "", "", "", "$ " . number_format($totalGeneral60, 2)]);
    fputcsv($output, ["TOTAL GENERAL", "", "", "", "$ " . number_format($totalGeneral40 + $totalGeneral60, 2)]);


    
    fputcsv($output, []);  // Línea vacía después del título
    // ==== LAVADOS REGISTRADOS ====
    fputcsv($output, []);
    fputcsv($output, ["DETALLE DE LAVADOS"]);
    fputcsv($output, []);  // Línea vacía después del título
    fputcsv($output, ["ID", "Placa", "Precio", "Fecha", "Empleado"]);

    // Consulta para lavados
    $sqlLavados = "SELECT l.id, l.placa_vehiculo, l.precio_lavado, l.fecha, e.nombre as empleado 
                   FROM lavados l 
                   JOIN empleados e ON l.id_empleado = e.id 
                   WHERE DATE(l.fecha) = ?";
    $stmt = $conn->prepare($sqlLavados);
    
    if ($stmt === false) {
        die("Error en la consulta: " . $conn->error); // Manejo de errores
    }

    $stmt->bind_param("s", $fechaFormateada);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($lavado = $result->fetch_assoc()) {
            fputcsv($output, [
                $lavado['id'],
                $lavado['placa_vehiculo'],
                "$ " . number_format($lavado['precio_lavado'], 2),
                $lavado['fecha'],
                $lavado['empleado']
            ]);
        }
    } else {
        fputcsv($output, ["No hay lavados registrados para esta fecha."]);
    }

    fclose($output);
    exit;
} else {
    die("Fecha no proporcionada.");
}



?>
