<?php
    //Datos de conexión
  $host = 'localhost';
  $usuario='root';
  $contrasena='';
  $baseDeDatos='panteras1';

  //Crear conexion
  $conn=new mysqli($host,$usuario,$contrasena,$baseDeDatos);

  //verificar conexión
  if ($conn->connect_error){
      die("Conexion fallida: " .$conn->connect_error);
  }

  /*echo "CONECTADO!!!";

    // Configurar charset
    $conn->set_charset("utf8mb4");
    */
    
?>
