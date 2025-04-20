<?php
include "conexion.php";
header('Content-Type: application/json');

$datos = json_decode(file_get_contents("php://input"), true);

$condiciones = [];

if (!empty($datos["fecha_inicio"])) {
    $condiciones[] = "Fecha >= '" . $conn->real_escape_string($datos["fecha_inicio"]) . "'";
}
if (!empty($datos["fecha_fin"])) {
    $condiciones[] = "Fecha <= '" . $conn->real_escape_string($datos["fecha_fin"]) . "'";
}
if (!empty($datos["nacionalidad"])) {
    $condiciones[] = "Nacionalidad = " . intval($datos["nacionalidad"]);
}
// Repite para cada filtro...

$sql = "SELECT Sexo, Edad, PaisResidencia, Nacionalidad, Estudios, Grado, Lengua1, Lengua2, Frecuencia, Motivo, Transporte, Tiempo, TipoAcomp, TamGrupo, MenoresGrupo FROM visitas";

if (!empty($condiciones)) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}

$resultado = $conn->query($sql);

$datos_finales = [];
while ($fila = $resultado->fetch_assoc()) {
    $datos_finales[] = $fila;
}

echo json_encode(["resultados" => $datos_finales]);
