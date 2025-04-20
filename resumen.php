<?php include("conexion.php"); 
$condicion= count($where)>0?"WHERE" .implode(" AND ", $where):"";
$sql ="
    SELECT
    COUNT(*) AS visitasTotales,
        SUM(CASE WHEN p2.Nombre='México' THEN 1 ELSE 0 END) AS visitasNacionales,
        SUM(CASE WHEN p2.Nombre !='México' THEN 1 ELSE 0 END) AS visitasExtranjeros,

        (SELECT l1.Nombre FROM visitas
        INNER JOIN lenguaje l1 ON visitas.primera_leng = l1.ID
        $condicion
        GROUP BY l1.Nombre
        ORDER BY COUNT(*) DESC LIMIT 1
        ) AS lenguaMasHablada,
        
        (
        SELECT m.Motivo FROM visitas
        INNER JOIN motivos m ON visitas.motivo=m.ID
        $condicion
        GROUP BY m.Motivo
        ORDER BY COUNT(*) DESC LIMIT 1
        ) AS motivoMasFrecuente
    FROM visitas
    INNER JOIN pais p1 ON visitas.residencia=P1.ID
    INNER JOIN pais p2 ON visitas.nacionalidad=P2.ID
    INNER JOIN escolaridad e ON visitas.escolaridad= e.ID
    INNER JOIN lenguaje l1 ON visitas.primera_leng= l1.ID
    INNER JOIN frec_visita f ON visitas.frecuencia_visita= f.ID
    INNER JOIN motivos m ON visitas.motivo = m.ID
    $condicion
";

    $resultado=$conexion->query($sql);
    $resume=$resultado ? $resultado->fetch_assoc() : null;

    if (empty($input)) {
        $resumen = [
            "visitasTotales"=> 4000,
            "visitasNacionales"=> 160,
            "visitasExtranjeros"=> 3840,
            "lenguaMasHablada" => "Islandés",
            "motivosMasFrecuente"=> "Aprender",
        ];
    }

    echo json_encode($resumen);
?>