<?php
include 'conexion.php';
header('Content-Type: application/json');

try {
    $sql = "SELECT idFabricante, nombre, pais_origen FROM fabricante";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }
    
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $fabricantes = array();
    while($fila = $resultado->fetch_assoc()) {
        $fabricantes[] = $fila;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $fabricantes
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conexion)) $conexion->close();
}
?>