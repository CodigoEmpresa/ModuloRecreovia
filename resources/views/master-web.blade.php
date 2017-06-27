<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @section ('style')
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('public/components/jquery-ui/themes/base/jquery-ui.css') }}" media="screen">
        <link rel="stylesheet" href="{{ asset('public/Css/bootstrap.css') }}" media="screen">
        <link rel="stylesheet" href="{{ asset('public/components/bootstrap-select/dist/css/bootstrap-select.css') }}" media="screen">
        <link rel="stylesheet" href="{{ asset('public/components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css') }}" media="screen">
        <link rel="stylesheet" href="{{ asset('public/components/selectize/dist/css/selectize.bootstrap3.css') }}" media="screen">
        <link rel="stylesheet" href="{{ asset('public/components/datatables.net-bs/css/dataTables.bootstrap.css') }}" media="screen">
        <link rel="stylesheet" href="{{ asset('public/components/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}" media="screen">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="{{ asset('public/components/highcharts/css/highcharts.css') }}" media="screen">
        <link rel="stylesheet" href="{{ asset('public/components/font-awesome/css/font-awesome.css') }}" media="screen">
        <link rel="stylesheet" href="{{ asset('public/components/loaders.css/loaders.min.css') }}" media="screen">
        <link rel="stylesheet" href="{{ asset('public/Css/main.css') }}" media="screen">
    @show

    @section ('script')
        <script src="{{ asset('public/components/jquery/jquery.js') }}"></script>
        <script src="{{ asset('public/components/jquery-ui/jquery-ui.js') }}"></script>
        <script src="{{ asset('public/components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/components/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
        <script src="{{ asset('public/components/moment/moment.js') }}"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('public/components/datatables.net-bs/js/dataTables.bootstrap.js') }}"></script>
        <script src="{{ asset('public/components/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>
        <script src="{{ asset('public/components/highcharts/js/highcharts.js') }}"></script>
        <script src="{{ asset('public/components/selectize/dist/js/standalone/selectize.js') }}"></script>
        <script src="{{ asset('public/components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
        <script src="{{ asset('public/Js/main.js') }}"></script>
    @show



    <title>Recreovía</title>
</head>
<body>
@yield('content')
<div class="ajaxloader">
    <div class="ball-scale-multiple"><div></div><div></div><div></div></div>
    <span>PROCESANDO...</span>
</div>
<!-- FIN Contenedor panel principal -->
</body>
</html>
