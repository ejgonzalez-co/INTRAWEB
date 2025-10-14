<meta charset="utf-8" />
<title>{{ config('app.name') }} | @yield('title')</title>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<meta content="" name="description" />
<meta content="SEVEN Soluciones Informáticas" name="author" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<link rel="icon" href="{{ asset('assets/img/favicon.ico') }}">
<img ref="image" style="display: none;" src="/assets/img/loadingintraweb.gif" alt="Imagen">

{!!Html::style('assets/css/material/app.min.css')!!}
{!!Html::style('css/app.css')!!}
<!-- ================== END BASE CSS STYLE ================== -->

<!-- Select2 component vue-->
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">

{{-- <link rel="stylesheet" type="text/css" href="./jqwidgets/styles/jqx.base.css" /> --}}

@stack('css')
