<?php

return array( 
 
  'conexion' => 'db_principal', 
   
  'modulo' => '34', 
  'seccion' => 'Personas', 
  'prefijo_ruta' => 'personas', 
  'prefijo_ruta_modulo' => 'actividad', 
 
  'modelo_persona' => 'App\Modulos\Personas\Persona', 
  'modelo_documento' => 'App\Modulos\Personas\Documento', 
  'modelo_pais' => 'App\Modulos\Personas\Pais', 
  'modelo_ciudad' => 'App\Modulos\Personas\Ciudad',  
  'modelo_departamento' => 'Idrd\Usuarios\Repo\Departamento', 
  'modelo_genero' => 'App\Modulos\Personas\Genero', 
  'modelo_etnia' => 'App\Modulos\Personas\Etnia',
  'modelo_tipo' => 'Idrd\Usuarios\Repo\Tipo',
  'modelo_acceso' => 'Idrd\Usuarios\Repo\Acceso',
  'modelo_datos' => 'Idrd\Usuarios\Repo\Datos',
  'modelo_asim' => 'Idrd\Usuarios\Repo\ActividadesSim',
  
   
  //vistas que carga las vistas 
  'vista_lista' => 'list', 
 
  //lista 
  'lista'  => 'idrd.usuarios.lista', 
);