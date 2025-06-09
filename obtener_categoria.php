<?php
include 'conexion.php';
header('Content-Type: application/json');

try {
    $sql = "SELECT idCategoria, nombre, descripcion FROM categoria";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }
    
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $categorias = array();
    while($fila = $resultado->fetch_assoc()) {
        $categorias[] = $fila;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $categorias
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