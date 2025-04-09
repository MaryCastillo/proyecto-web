<?php
include "conexion.php";
header("Content-Type: application/json");

// Recibir los filtros una sola vez
$data = json_decode(file_get_contents("php://input"), true);

// Armar el WHERE dinámico (para ambos usos)
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

// --- Resumen ---
$total = $conn->query("SELECT COUNT(*) as total FROM visita v $condiciones")->fetch_assoc()["total"] ?? 0;

/*$sqlNac = "SELECT COUNT(*) as total 
           FROM visita v 
           JOIN pais p ON v.IDNacionalidad = p.ID 
           $condiciones " . (count($where) > 0 ? " AND " : "WHERE ") . " p.Nombre = 'México'";
$nacionales = $conn->query($sqlNac)->fetch_assoc()["total"] ?? 0;
*/
$condNac = $where;
$condNac[] = "p.Nombre = 'México'";
$condStr = "WHERE " . implode(" AND ", $condNac);

$sqlNac = "SELECT COUNT(*) as total 
           FROM visita v 
           JOIN pais p ON v.IDNacionalidad = p.ID 
           $condStr";
$extranjeros = $total - $nacionales;

$sqlLengua = "SELECT l.Nombre, COUNT(*) as total
              FROM visita v
              JOIN lenguaje l ON v.IDLenguaje = l.ID
              $condiciones
              GROUP BY l.Nombre
              ORDER BY total DESC
              LIMIT 1";
$resLengua = $conn->query($sqlLengua)->fetch_assoc();
$lengua = $resLengua["Nombre"] ?? "Sin datos";

$sqlMotivo = "SELECT c.Medio, COUNT(*) as total
              FROM visita v
              JOIN comunicacion c ON v.IDMedio = c.ID
              $condiciones
              GROUP BY c.Medio
              ORDER BY total DESC
              LIMIT 1";
$resMotivo = $conn->query($sqlMotivo)->fetch_assoc();
$motivo = $resMotivo["Medio"] ?? "Sin datos";

// --- Resultados de tabla ---
$sqlTabla = "
    SELECT 
        s.Nombre AS Sexo,
        v.Edad,
        p.Nombre AS PaisResidencia,
        pn.Gentilicio AS Nacionalidad,
        e.Nivel AS Estudios,
        e.Grado,
        l1.Nombre AS Lengua1,
        l2.Nombre AS Lengua2,
        f.Rango AS Frecuencia,
        c.Medio AS Motivo,
        v.Transporte,
        v.Tiempo,
        v.TipoAcomp,
        v.TamGrupo,
        v.MenoresGrupo
    FROM visita v
    LEFT JOIN sexo s ON v.IDSexo = s.ID
    LEFT JOIN pais p ON v.IDPaisResidencia = p.ID
    LEFT JOIN pais pn ON v.IDNacionalidad = pn.ID
    LEFT JOIN escolaridad e ON v.IDEscolaridad = e.ID
    LEFT JOIN lenguaje l1 ON v.IDLenguaje = l1.ID
    LEFT JOIN lenguaje l2 ON v.IDLenguaje2 = l2.ID
    LEFT JOIN frec_visita f ON v.IDFrecVisita = f.ID
    LEFT JOIN comunicacion c ON v.IDMedio = c.ID
    $condiciones
";

$resultado = $conn->query($sqlTabla);
$datos_finales = [];
while ($fila = $resultado->fetch_assoc()) {
    $datos_finales[] = $fila;
}

// --- Enviar todo en un solo JSON ---
    echo json_encode([
        "total" => $total,
        "nacionales" => $nacionales,
        "extranjeros" => $extranjeros,
        "lengua" => $lengua,
        "motivo" => $motivo,
        "resultados" => $resultados
    ]);
?>
