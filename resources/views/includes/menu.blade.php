<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a href="{{ url('/welcome') }}" data-role="{{ implode($_SESSION['Usuario']['Roles']) }}" class="navbar-brand">SIM</a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav">
                @if (
                    $_SESSION['Usuario']['Permisos']['editar_profesores'] ||
                    $_SESSION['Usuario']['Permisos']['editar_puntos'] ||
                    $_SESSION['Usuario']['Permisos']['administrar_localidades'] ||
                    $_SESSION['Usuario']['Permisos']['administrar_jornadas'] ||
                    $_SESSION['Usuario']['Permisos']['gestion_global_de_sesiones'] ||
                    $_SESSION['Usuario']['Permisos']['buscador_de_sesiones'] ||
                    $_SESSION['Usuario']['Permisos']['ajustar_cronogramas']
                )
                    <li class="dropdown {{ $seccion && in_array($seccion, ['Profesores', 'Jornadas', 'Puntos', 'Administrar localidades', 'Gestion global de sesiones', 'Buscador de sesiones', 'Agrupar y transferir cronogramas']) ? 'active' : '' }}">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Administración <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">Configuración</li>
                            @if($_SESSION['Usuario']['Permisos']['administrar_jornadas'])
                                <li class="{{ $seccion && $seccion == 'Jornadas' ? 'active' : '' }}">
                                    <a href="{{ url('jornadas') }}">Jornadas</a>
                                </li>
                            @endif
                            @if($_SESSION['Usuario']['Permisos']['editar_puntos'])
                                <li class="{{ $seccion && $seccion == 'Puntos' ? 'active' : '' }}">
                                    <a href="{{ url('puntos') }}">Puntos</a>
                                </li>
                            @endif
                            @if($_SESSION['Usuario']['Permisos']['editar_profesores'])
                                <li class="{{ $seccion && $seccion == 'Profesores' ? 'active' : '' }}">
                                    <a href="{{ url('profesores') }}">Profesores y gestores</a>
                                </li>
                            @endif
                            @if($_SESSION['Usuario']['Permisos']['administrar_localidades'])
                                <li class="{{ $seccion && $seccion == 'Administrar localidades' ? 'active' : '' }}">
                                    <a href="{{ url('localidades/administrar') }}">Distribución de personal</a>
                                </li>
                            @endif
                            @if($_SESSION['Usuario']['Permisos']['gestion_global_de_sesiones'] || $_SESSION['Usuario']['Permisos']['buscador_de_sesiones'])
                                <li class="dropdown-header">Utilidades</li>
                            @endif
                            @if($_SESSION['Usuario']['Permisos']['gestion_global_de_sesiones'])
                                <li class="{{ $seccion && $seccion == 'Gestion global de sesiones' ? 'active' : '' }}">
                                    <a href="{{ url('sesiones/administrar') }}">Gestion global de sesiones</a>
                                </li>
                            @endif
                            @if($_SESSION['Usuario']['Permisos']['buscador_de_sesiones'])
                                <li class="{{ $seccion && $seccion == 'Buscador de sesiones' ? 'active' : '' }}">
                                    <a href="{{ url('sesiones/buscar') }}">Buscador de sesiones</a>
                                </li>
                            @endif
                            @if($_SESSION['Usuario']['Permisos']['ajustar_cronogramas'])
                                <li class="{{ $seccion && $seccion == 'Agrupar y transferir cronogramas' ? 'active' : '' }}">
                                    <a href="{{ url('programacion/ajustar') }}">Agrupar y transferir cronogramas</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(
                    (
                    $_SESSION['Usuario']['Permisos']['programar_sesiones'] ||
                    $_SESSION['Usuario']['Permisos']['revisar_sesiones_gestor']
                    ) &&
                    in_array('Gestor', $_SESSION['Usuario']['Roles'])
                )
                    <li class="dropdown {{ $seccion && in_array($seccion, ['Programación', 'Sesiones gestor']) ? 'active' : '' }}">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Gestores <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">Sesiones</li>
                            <li class="{{ $seccion && $seccion == 'Programación' ? 'active' : '' }}">
                                <a href="{{ url('programacion') }}">Programación</a>
                            </li>
                            <li class="{{ $seccion && $seccion == 'Sesiones gestor' ? 'active' : '' }}">
                                <a href="{{ url('/gestores/sesiones') }}">Consulta</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(
                    (
                    $_SESSION['Usuario']['Permisos']['revisar_sesiones_profesor']
                    ) &&
                    in_array('Profesor', $_SESSION['Usuario']['Roles'])
                )
                    <li class="dropdown {{ $seccion && in_array($seccion, ['Sesiones profesor']) ? 'active' : '' }}">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Profesores <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">Sesiones</li>
                            <li class="{{ $seccion && $seccion == 'Sesiones profesor' ? 'active' : '' }}">
                                <a href="{{ url('/profesores/sesiones') }}">Consulta</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(
                    $_SESSION['Usuario']['Permisos']['validar_reportes_jornadas'] ||
                    $_SESSION['Usuario']['Permisos']['exportar_consolidado_general'] ||
                    $_SESSION['Usuario']['Permisos']['consultar_reportes'] ||
                    ($_SESSION['Usuario']['Permisos']['gestionar_reportes_jornadas'] && in_array('Gestor', $_SESSION['Usuario']['Roles'])) ||
                    ($_SESSION['Usuario']['Permisos']['gestionar_reportes_jornadas'] && in_array('Profesor', $_SESSION['Usuario']['Roles']))
                )
                    <li class="dropdown {{ $seccion && in_array($seccion, ['Revisar informes', 'Informes jornadas', 'Generar informe de actividades por punto', 'Reporte asistencia y participación', 'Reporte de actividades', 'Reporte producto no conforme']) ? 'active' : '' }}">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Informes y consultas <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">Informes de jornadas</li>
                            @if ($_SESSION['Usuario']['Permisos']['validar_reportes_jornadas'])
                                <li class="{{ $seccion && $seccion == 'Revisar informes' ? 'active' : '' }}">
                                    <a href="{{ url('informes/jornadas/revisar') }}">Validar</a>
                                </li>
                            @endif
                            @if ($_SESSION['Usuario']['Permisos']['gestionar_reportes_jornadas'] && in_array('Gestor', $_SESSION['Usuario']['Roles']))
                                <li class="{{ $seccion && in_array($seccion, ['Informes jornadas', 'Generar informe de actividades por punto']) ? 'active' : '' }}">
                                    <a href="{{ url('/informes/jornadas') }}">Consultar</a>
                                </li>
                            @endif
                            @if ($_SESSION['Usuario']['Permisos']['gestionar_reportes_jornadas'] && in_array('Profesor', $_SESSION['Usuario']['Roles']))
                                <li class="{{ $seccion && in_array($seccion, ['Informes jornadas', 'Generar informe de actividades por punto']) ? 'active' : '' }}">
                                    <a href="{{ url('/informes/jornadas/profesor') }}">Consultar</a>
                                </li>
                            @endif
                            @if ($_SESSION['Usuario']['Permisos']['exportar_consolidado_general'])
                                <li class="dropdown-header">Consolidado general jornadas</li>
                                <li class="{{ $seccion && in_array($seccion, ['Consolidado general jornadas']) ? 'active' : '' }}">
                                    <a href="{{ url('/informes/consolidado_general') }}">Generar</a>
                                </li>
                            @endif
                            @if ($_SESSION['Usuario']['Permisos']['consultar_reportes'])
                                <li class="dropdown-header">Reportes</li>
                                <li class="{{ $seccion && in_array($seccion, ['Reporte asistencia y participación']) ? 'active' : '' }}">
                                    <a href="{{ url('/reportes/asistencia') }}">Reporte de participación y asistencia</a>
                                </li>
                                <li class="{{ $seccion && in_array($seccion, ['Reporte de actividades']) ? 'active' : '' }}">
                                    <a href="{{ url('/reportes/actividades') }}">Reporte de actividades</a>
                                </li>
                                <li class="{{ $seccion && in_array($seccion, ['Reporte producto no conforme']) ? 'active' : '' }}">
                                    <a href="{{ url('/reportes/producto_no_conforme') }}">Reporte producto no conforme</a>
                                </li>
                                <li class="{{ $seccion && in_array($seccion, ['Reporte calificación del servicio']) ? 'active' : '' }}">
                                    <a href="{{ url('/reportes/calificacion_del_servicio') }}">Reporte calificación del servicio</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                <li>
                    <a target="_blank" href="{{ url('/buscador') }}">Mapa de actividades</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="http://www.idrd.gov.co/sitio/idrd/" target="_blank">I.D.R.D</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $_SESSION['Usuario']['Persona']['Primer_Apellido'].' '.$_SESSION['Usuario']['Persona']['Primer_Nombre'] }}<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ url('logout') }}">Cerrar sesión</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>