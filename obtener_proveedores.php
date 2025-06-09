<?php
include 'conexion.php';
header('Content-Type: application/json');

try {
    $sql = "SELECT idProveedor, razon_social, ruc, direccion, telefono FROM proveedor";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }
    
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $proveedores = array();
    while($fila = $resultado->fetch_assoc()) {
        $proveedores[] = $fila;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $proveedores
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