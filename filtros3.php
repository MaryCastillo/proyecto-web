<?php include "./conexion.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/styles2.css" />
    <?php include "assets/components/header.php"; ?>
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
                            $nacionalidad = $conn->query(
                                "SELECT ID, Gentilicio FROM pais"
                            );
                            while ($row = $nacionalidad->fetch_assoc()) {
                                echo "<option value='{$row["ID"]}'>{$row["Gentilicio"]}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="filtro-item">
                        <label>Frecuencia:</label>
                        <select name="frec_visita">
                            <option value="">Opciones</option>
                            <?php
                            $frec_visita = $conn->query(
                                "SELECT ID, Rango FROM frec_visita"
                            );
                            while ($row = $frec_visita->fetch_assoc()) {
                                echo "<option value='{$row["ID"]}'>{$row["Rango"]}</option>";
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
                                echo "<option value='{$row["ID"]}'>{$row["Nombre"]}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="filtro-item">
                        <label>Estudios:</label>
                        <select name="escolaridad">
                            <option value="">Opciones</option>
                            <?php
                            $escolaridad = $conn->query(
                                "SELECT ID, Grado FROM escolaridad"
                            );
                            while ($row = $escolaridad->fetch_assoc()) {
                                echo "<option value='{$row["ID"]}'>{$row["Grado"]}</option>";
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
                            $motivos = $conn->query(
                                "SELECT ID, Medio FROM comunicacion"
                            );
                            while ($row = $motivos->fetch_assoc()) {
                                echo "<option value='{$row["ID"]}'>{$row["Medio"]}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="filtro-item">
                        <label>Lenguas:</label>
                        <select name="lenguaje">
                            <option value="">Opciones</option>
                            <?php
                            $lenguaje = $conn->query(
                                "SELECT ID, Nombre FROM lenguaje"
                            );
                            while ($row = $lenguaje->fetch_assoc()) {
                                echo "<option value='{$row["ID"]}'>{$row["Nombre"]}</option>";
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
        
                <!--LISTADO DE FILTROS-->
             <div class="resumen-filtros">
                    <h3>Filtros Activos:</h3>
                    <ul id="lista-filtros"></ul>
            </div>
            <!--CINTA RESUMEN-->
            <div id="cinta-resumen" class="cinta-resumen oculto">
                <div class="resumen-item">
                    <h2 id="res-visitas-total">--</h2>
                    <p>Visitas Totales</p>
                </div>
                <div class="resumen-item">
                    <h2 id="res-nacionales">--</h2>
                    <p>Visitas Nacionales</p>
                </div>
                <div class="resumen-item">
                    <h2 id="res-extranjeros">--</h2>
                    <p>Visitas Extranjeros</p>
                </div>
                <div class="resumen-item">
                    <h2 id="res-lengua">--</h2>
                    <p>Lengua más hablada</p>
                </div>
                <div class="resumen-item">
                    <h2 id="res-motivo">--</h2>
                    <p>Motivo</p>
                </div>
            </div>

        <script>
        //SCRIPT PARA FILTROS SELECCIONADOS
        document.addEventListener("DOMContentLoaded", () => {
            const filtros = document.querySelectorAll("input, select");
            const listaFiltros = document.getElementById("lista-filtros");
            const formulario = document.querySelector("form");

            const nombresCampos = {
                fecha_inicio: "Fecha Inicio",
                fecha_fin: "Fecha Fin",
                nacionalidad: "Nacionalidad",
                pais: "País de Residencia",
                frec_visita: "Frecuencia",
                escolaridad: "Estudios",
                motivos: "Motivo",
                lenguaje: "Lengua"
            };

            filtros.forEach(filtro => {
                filtro.addEventListener("change", () => {
                    actualizarResumen();
                });
            });

            formulario.addEventListener("reset", () => {
                // Espera un poco a que se limpie el formulario visualmente
                setTimeout(() => {
                    listaFiltros.innerHTML = "";
                }, 50);
            });

            function actualizarResumen() {
                listaFiltros.innerHTML = "";

                filtros.forEach(filtro => {
                    let valor = filtro.value;
                    if (valor && nombresCampos[filtro.name || filtro.id]) {
                        const texto = filtro.options
                            ? filtro.options[filtro.selectedIndex].text
                            : valor;

                        const li = document.createElement("li");
                        li.textContent = `${nombresCampos[filtro.name || filtro.id]}: ${texto}`;
                        listaFiltros.appendChild(li);
                    }
                });
            }
        });


        //SCRIPT PARA OBTENER Y MOSTRAR RESUMEN
        document.addEventListener("DOMContentLoaded", () => {
            const filtros = document.querySelectorAll("input, select");
            const btnBuscar = document.querySelector(".btn-buscar");
            const cintaResumen = document.getElementById("cinta-resumen");

            // Elementos resumen
            const elTotal = document.getElementById("res-visitas-total");
            const elNac = document.getElementById("res-nacionales");
            const elExt = document.getElementById("res-extranjeros");
            const elLengua = document.getElementById("res-lengua");
            const elMotivo = document.getElementById("res-motivo");

            btnBuscar.addEventListener("click", (e) => {
                e.preventDefault();

                let filtrosSeleccionados = {};
                filtros.forEach(filtro => {
                    let key = filtro.name || filtro.id;
                    if (filtro.value) {
                        filtrosSeleccionados[key] = filtro.value;
                    }
                });

                fetch("sql.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(filtrosSeleccionados)
                })
                .then(res => res.json())
                .then(data => {
                    elTotal.textContent = data.total ?? "--";
                    elNac.textContent = data.nacionales ?? "--";
                    elExt.textContent = data.extranjeros ?? "--";
                    elLengua.textContent = data.lengua ?? "--";
                    elMotivo.textContent = data.motivo ?? "--";

                    cintaResumen.classList.remove("oculto");
                })
                .catch(err => {
                    console.error("Error al obtener resumen:", err);
                    cintaResumen.classList.add("oculto");
                });
            });
        });
        </script>

    </body>
</html>
