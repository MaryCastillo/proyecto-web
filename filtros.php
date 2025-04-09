<?php include("conexion.php"); ?>
<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="assets/css/styles.css" />
        <?php include 'assets/components/header.php'; ?>
    <title>Filtros - Reporte de Visitas a Museos</title>
    </head>
    <body>
        <label>Motivos:</label>
            <select name="motivos">
            <option value="">-- Todas --</option>
            <?php
            $escolaridad = $conn->query("SELECT ID, Medio FROM comunicacion");
            while ($row = $comunicacion->fetch_assoc()) {
                echo "<option value='{$row['ID']}'>{$row['Medio']}</option>";
            }
            ?>
            </select><br>
        <label>Nacionalidad:</label>
            <select name="nacionalidad">
            <option value="">-- Todas --</option>
            <?php
            $nacionalidad = $conn->query("SELECT ID, Gentilicio FROM pais");
            while ($row = $nacionalidad->fetch_assoc()) {
                echo "<option value='{$row['ID']}'>{$row['Gentilicio']}</option>";
            }
            ?>
            </select><br>
        <label>País de residencia:</label>
            <select name="pais">
            <option value="">-- Todas --</option>
            <?php
            $pais = $conn->query("SELECT ID, Nombre FROM pais");
            while ($row = $pais->fetch_assoc()) {
                echo "<option value='{$row['ID']}'>{$row['Nombre']}</option>";
            }
            ?>
            </select><br>
        <label>Lengua Hablada:</label>
            <select name="lenguaje">
            <option value="">-- Todas --</option>
            <?php
            $lenguaje = $conn->query("SELECT ID, Nombre FROM lenguaje");
            while ($row = $lenguaje->fetch_assoc()) {
                echo "<option value='{$row['ID']}'>{$row['Nombre']}</option>";
            }
            ?>
            </select><br>
        <label>Frecuencia de visita:</label>
            <select name="frec_visita">
            <option value="">-- Todas --</option>
            <?php
            $frec_visita = $conn->query("SELECT ID, Rango FROM frec_visita");
            while ($row = $frec_visita->fetch_assoc()) {
                echo "<option value='{$row['ID']}'>{$row['Rango']}</option>";
            }
            ?>
            </select><br>
        <label>Escolaridad:</label>
            <select name="escolaridad">
            <option value="">-- Todas --</option>
            <?php
            $escolaridad = $conn->query("SELECT ID, Grado FROM escolaridad");
            while ($row = $escolaridad->fetch_assoc()) {
                echo "<option value='{$row['ID']}'>{$row['Grado']}</option>";
            }
            ?>
            </select><br>
    </body>
</html>