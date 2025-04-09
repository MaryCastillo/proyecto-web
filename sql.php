<?php
include "conexion.php";
header("Content-Type: application/json");

// Recibir los filtros como JSON
$data = json_decode(file_get_contents("php://input"), true);

// Armar el WHERE dinámico
$where = [];

if (!empty($data["fecha_inicio"])) {
    $where[] = "v.Fecha >= '" . $conn->real_escape_string($data["fecha_inicio"]) . "'";
}
if (!empty($data["fecha_fin"])) {
    $where[] = "v.Fecha <= '" . $conn->real_escape_string($data["fecha_fin"]) . "'";
}
if (!empty($data["nacionalidad"])) {
    $where[] = "v.IDNacionalidad = " . intval($data["nacionalidad"]);
}
if (!empty($data["pais"])) {
    $where[] = "v.IDPaisResidencia = " . intval($data["pais"]);
}
if (!empty($data["lenguaje"])) {
    $where[] = "v.IDLenguaje = " . intval($data["lenguaje"]);
}
if (!empty($data["frec_visita"])) {
    $where[] = "v.IDFrecVisita = " . intval($data["frec_visita"]);
}
if (!empty($data["escolaridad"])) {
    $where[] = "v.IDEscolaridad = " . intval($data["escolaridad"]);
}
if (!empty($data["motivos"])) {
    $where[] = "v.IDMedio = " . intval($data["motivos"]);
}

$condiciones = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

// Total de visitas
$sqlTotal = "SELECT COUNT(*) as total FROM visita v $condiciones";
$total = $conn->query($sqlTotal)->fetch_assoc()["total"];

// Nacionales (México = ID 117, cambia si es distinto en tu BD)
$sqlNac = "SELECT COUNT(*) as total 
           FROM visita v 
           JOIN pais p ON v.IDNacionalidad = p.ID 
           $condiciones " . (count($where) > 0 ? " AND " : "WHERE ") . " p.Nombre = 'México'";
$nacionales = $conn->query($sqlNac)->fetch_assoc()["total"] ?? 0;

// Extranjeros = total - nacionales
$extranjeros = $total - $nacionales;

// Lengua más hablada
$sqlLengua = "SELECT l.Nombre, COUNT(*) as total
              FROM visita v
              JOIN lenguaje l ON v.IDLenguaje = l.ID
              $condiciones
              GROUP BY l.Nombre
              ORDER BY total DESC
              LIMIT 1";
$resLengua = $conn->query($sqlLengua)->fetch_assoc();
$lengua = $resLengua["Nombre"] ?? "Sin datos";

// Motivo más frecuente
$sqlMotivo = "SELECT c.Medio, COUNT(*) as total
              FROM visita v
              JOIN comunicacion c ON v.IDMedio = c.ID
              $condiciones
              GROUP BY c.Medio
              ORDER BY total DESC
              LIMIT 1";
$resMotivo = $conn->query($sqlMotivo)->fetch_assoc();
$motivo = $resMotivo["Medio"] ?? "Sin datos";

// Devolver el JSON
echo json_encode([
    "total" => intval($total),
    "nacionales" => intval($nacionales),
    "extranjeros" => intval($extranjeros),
    "lengua" => $lengua,
    "motivo" => $motivo
]);

//TABLA DE RESULTADOS
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




