@extends('layouts.default')

@section('title', 'Noticias')

@section('section_img', '/assets/img/components/news.png')

@section('menu')
    @include('intranet::layouts.menus.menu_notice_panel')
@endsection

@section('content')

<crud
    name="categories"
    :resource="{default: 'notices', get: 'get-notices-public', show: 'notices'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">Noticias</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Noticias de la entidad</h1>
        <!-- end page-header -->

        

        <section class="details-card">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 pt-4"  v-for="post in dataList">
                        <div class="card-content">
                            <div class="card-img d-flex align-items-center">
                                <img v-if="post.image_presentation" :src="'{{ asset('storage') }}/'+post.image_presentation" alt="">
                                <span><h4>@{{post.category.name}}</h4></span>
                            </div>
                            <div class="card-desc">
                                <small class="text-gray">@{{ post.created_at }}</small>

                                <h3>@{{post.title}}</h3>
                                <button @click="show(post)" data-target="#modal-view-notices-all" data-toggle="modal" class="btn-card" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                                    Leer más
                                </button>
                                <br><br>
                                <small class="text-gray"><i class="fa fa-eye"> @{{ post.views }}</i></small>


                            </div>
                        </div>
                    </div>                
                </div>
            </div>
        </section>

          <!-- begin #modal-view-notices-all -->
          <div class="modal fade" id="modal-view-notices-all">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') noticias</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="modal-view-notices-body">
                        @include('intranet::notices.show_fields_notice')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContent('modal-view-notices-body');"><i class="fa fa-print mr-2"></i>@lang('print')</button>

                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-notices-all -->
    </div>


</crud>
@endsection

<style>

/* card details start  */
@import url('https://fonts.googleapis.com/css?family=Raleway:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Roboto+Condensed:400,400i,700,700i');
section{
    padding: 50px 0;
}
.content_litle {
      width: 230px;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
}
.details-card {
	background: #ecf0f1;
}

.card-content {
    min-height: 450px;
	background: #ffffff;
	border: 4px;
	box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);
}

.card-img {
	position: relative;
	overflow: hidden;
	border-radius: 0;
	z-index: 1;
    height: 230px;
}

.card-img img {
	width: 100%;
	height: auto;
    /* height: 250px; */
	display: block;
}

.category{

    background: #2196f3;
    padding: 6px;
    color: #fff;
    font-size: 12px;
    border-radius: 4px;
    width: 30%;
 
}
.card-img span {
	position: absolute;
    top: 15%;
    left: 25%;
    background: #2196f3;
    padding: 6px;
    color: #fff;
    font-size: 12px;
    border-radius: 4px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
    transform: translate(-50%,-50%);
}
.card-img span h4{
        font-size: 12px;
        margin:0;
        padding:10px 5px;
         line-height: 0;
}
.card-desc {
	padding: 1.25rem;
}

.card-desc h3 {
	color: #000000;
    font-weight: 600;
    font-size: 1.5em;
    line-height: 1.3em;
    margin-top: 0;
    margin-bottom: 5px;
    padding: 0;
}

.card-desc p {
	color: #747373;
    font-size: 14px;
	font-weight: 400;
	font-size: 1em;
	line-height: 1.5;
	margin: 0px;
	margin-bottom: 20px;
	padding: 0;
	font-family: 'Raleway', sans-serif;
}
.btn-card{
	background-color: #2196f3;
	color: #fff;
	box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);
    padding: .84rem 2.14rem;
    font-size: .81rem;
    -webkit-transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    -o-transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    margin: 0;
    border: 0;
    -webkit-border-radius: .125rem;
    border-radius: .125rem;
    cursor: pointer;
    text-transform: uppercase;
    white-space: normal;
    word-wrap: break-word;
    color: #fff;
}
.btn-card:hover {
    background: #2196f3;
    color: #fff;
}
a.btn-card {
    text-decoration: none;
    color: #fff;
}
/* End card section */
    </style>



@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-categories').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

      
    // Función para imprimir el contenido de un identificador pasado por parámetro
    function printContent(divName) {
        // Se obtiene el elemento del id recibido por parámetro
        var printContent = document.getElementById(divName);
        // Se guarda en una variable la nueva pestaña
        var printWindow = window.open("");
        // Se obtiene el encabezado de la página actual para no peder estilos
        var headContent = document.getElementsByTagName('head')[0].innerHTML;
        // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
        printWindow.document.write(headContent);
        // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
        printWindow.document.write(printContent.innerHTML);
        printWindow.document.close();
        // Se enfoca en la pestaña nueva
        printWindow.focus();
        // Se abre la ventana para imprimir el contenido de la pestaña nueva
        printWindow.onload = function() {
            printWindow.print();
            // printWindow.close();
        };   
    }
</script>
@endpush
