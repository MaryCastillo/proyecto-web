<?php include("conexion.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/styles2.css" />
    <?php include 'assets/components/header.php'; ?>
    <title>Filtros - Reporte de Visitas a Museos</title>
</head>
    <body>
        <div class="titulo-principal">
            <h1>Visitas a Museos</h1>
        </div>
        
        <form method="post" action="">
            <div class="filtros-container">
                <div class="filtros-group">
                    <div class="filtro-item">
                    <label for="fecha_inicio">Fecha Inicio:</label>
                    <input type="date" id="fecha_inicio">
                    </div>
                    
                    <div class="filtro-item">
                        <label>Nacionalidad:</label>
                        <select name="nacionalidad">
                            <option value="">Opciones</option>
                            <?php
                            $nacionalidad = $conn->query("SELECT ID, Gentilicio FROM pais");
                            while ($row = $nacionalidad->fetch_assoc()) {
                                echo "<option value='{$row['ID']}'>{$row['Gentilicio']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="filtro-item">
                        <label>Frecuencia:</label>
                        <select name="frec_visita">
                            <option value="">Opciones</option>
                            <?php
                            $frec_visita = $conn->query("SELECT ID, Rango FROM frec_visita");
                            while ($row = $frec_visita->fetch_assoc()) {
                                echo "<option value='{$row['ID']}'>{$row['Rango']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="filtros-group">
                    <div class="filtro-item">
                    <label for="fecha_fin">Fecha Fin:</label>
                    <input type="date" id="fecha_fin">
                        
                    </div>
                    
                    <div class="filtro-item">
                        <label>Residencia:</label>
                        <select name="pais">
                            <option value="">Opciones</option>
                            <?php
                            $pais = $conn->query("SELECT ID, Nombre FROM pais");
                            while ($row = $pais->fetch_assoc()) {
                                echo "<option value='{$row['ID']}'>{$row['Nombre']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="filtro-item">
                        <label>Estudios:</label>
                        <select name="escolaridad">
                            <option value="">Opciones</option>
                            <?php
                            $escolaridad = $conn->query("SELECT ID, Grado FROM escolaridad");
                            while ($row = $escolaridad->fetch_assoc()) {
                                echo "<option value='{$row['ID']}'>{$row['Grado']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="filtros-group">
                    <div class="filtro-item">
                        <label>Motivos:</label>
                        <select name="motivos">
                            <option value="">Opciones</option>
                            <?php
                            $motivos = $conn->query("SELECT ID, Medio FROM comunicacion");
                            while ($row = $motivos->fetch_assoc()) {
                                echo "<option value='{$row['ID']}'>{$row['Medio']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="filtro-item">
                        <label>Lenguas:</label>
                        <select name="lenguaje">
                            <option value="">Opciones</option>
                            <?php
                            $lenguaje = $conn->query("SELECT ID, Nombre FROM lenguaje");
                            while ($row = $lenguaje->fetch_assoc()) {
                                echo "<option value='{$row['ID']}'>{$row['Nombre']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="botones">
                <button type="reset" class="btn-limpiar">Limpiar</button>
                <button type="submit" class="btn-buscar">Buscar</button>
            </div>
        </form>
        
        
        

    </body>
</html>