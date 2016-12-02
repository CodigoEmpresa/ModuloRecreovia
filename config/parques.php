<?php

return array( 
  'conexion' => 'db_parques',
  
  'modelo_parque' => '\Idrd\Parques\Repo\Parque',
  'modelo_tipo' => '\Idrd\Parques\Repo\TipoParque',
  'modelo_localidad' => 'App\Modulos\Parques\Localidad',
  'modelo_upz' => 'App\Modulos\Parques\Upz',
  'modelo_barrio' => 'Idrd\Parques\Repo\Barrio',
  'modelo_dotacion' => 'Idrd\Parques\Repo\Dotacion'
);