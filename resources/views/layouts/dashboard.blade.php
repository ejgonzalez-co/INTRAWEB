@extends('layouts.default')

@section('title', 'Tablero de control')

@section('section_img', '/assets/img/components/estadistica.png')

@section('menu')
    @if(Auth::user()->accept_service_terms)
        @include('layouts.menu_dashboard')
    @endif
@endsection


@push('scripts')
<script>
    $(document).ready(function () {
        @if($mostrarPopup)
            $('#modal_lanzamiento').modal('show');
        @endif
    });
</script>
@endpush



@section('content')

    @if(Auth::user()->accept_service_terms)
        <!-- begin widget -->
        <div class="row mt-2" v-show="!query">

            <widget-counter-v2
                icon="fa fa-file-import"
                bg-color-icon="#000080bd"
                {{-- class-css-color="bg-warning" --}}
                :qty="dataForm.total_corr_enviada ?? 0"
                status="redirect"
                title="Correspondencia externa enviada (Físico)"
                name-field="state"
                :value="searchFields"
                url-redirect="{{ '/correspondence/externals?qd='.base64_encode('FISICO') }}"
                title-link-see-more="Ver listado"
            ></widget-counter-v2>

            <widget-counter-v2
                icon="fas fa-bullhorn"
                bg-color-icon="#28a749"
                {{-- class-css-color="bg-blue" --}}
                :qty="dataForm.total_pqrs ?? 0"
                status="redirect"
                title="Total PQRS"
                name-field="state"
                :value="searchFields"
                url-redirect="/pqrs/p-q-r-s"
                title-link-see-more="Ver listado"
            ></widget-counter-v2>

            <widget-counter-v2
                icon="fa fa-file-signature"
                bg-color-icon="#20a6bf"
                {{-- class-css-color="bg-green" --}}
                :qty="dataForm.total_corr_interna_cp ?? 0"
                status="redirect"
                title="Correspondencia externa interna (Cero papel)"
                name-field="state"
                :value="searchFields"
                url-redirect="{{ '/correspondence/internals?qd='.base64_encode('DIGITAL') }}"
                title-link-see-more="Ver listado"
            ></widget-counter-v2>

            <widget-counter-v2
                icon="fa fa-file-word"
                bg-color-icon="#ffc433"
                {{-- class-css-color="bg-secondary" --}}
                :qty="dataForm.total_corr_enviada_cp ?? 0"
                status="redirect"
                title="Correspondencia externa enviada (Cero papel)"
                name-field="state"
                :value="searchFields"
                url-redirect="{{ '/correspondence/externals?qd='.base64_encode('DIGITAL') }}"
                title-link-see-more="Ver listado"
            ></widget-counter-v2>


            <!-- begin card search -->
            <div v-if="dataForm.noticias?.length > 0" @click="$refs.dashboard.toggleNews()" class="cursor-pointer card-header bg-white pointer-cursor d-flex align-items-center w-100" data-toggle="collapse" data-target="#collapseOne">
                <i class="fa fa-newspaper fa-fw mr-2 f-s-16"></i> <b>@{{ ($refs.dashboard?.showSearchOptions)? 'Ocultar noticias' : 'Mostrar noticias' | trans }}</b>
            </div>

            {{-- Sección de noticias de la entidad, por defecto se muestran --}}
            <span v-if="dataForm.noticias?.length > 0" id="collapseOne" class="show w-100">
                <section class="details-card w-100">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3 pt-2"  v-for="post in dataForm.noticias">
                                <div class="card-content">
                                    <div class="card-img d-flex align-items-center">
                                        <img :src="'{{ asset('storage') }}/'+post.image_presentation" alt="" @load="$refs.dashboard.imageLoaded">
                                        <div v-show="!$refs.dashboard.loadedImagesComplete">
                                            <div class="spinner" style="width: 25px; height: 25px;"></div>
                                        </div>
                                        <span><h4>@{{post.category.name}}</h4></span>
                                    </div>
                                    <div class="card-desc">
                                        <small class="text-gray">@{{ post.created_at }}</small>

                                        <h3 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">@{{post.title}}</h3>
                                        <button @click="$refs.dashboard.show(post)" data-target="#modal-view-notices-all" data-toggle="modal" class="btn-card" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                                            Leer más
                                        </button>
                                        <br><br>
                                        <small class="text-gray"><i class="fa fa-eye"> @{{ post.views }}</i></small>
                                        <small class="text-yellow" v-if="post.featured=='Si'"><i class="fa fa-star"></i></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="col-md-12" style="text-align: center;" v-if="dataForm.noticias?.length > 0">
                    <a href="/intranet/notices-public" class="btn btn-primary">Ver más noticias</a>
                </div>
            </span>


            <!-- begin #modal-view-notices-all -->
            <div class="modal fade" id="modal-view-notices-all">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('info_of') noticias</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" id="modal-view-notices-body" v-if="dataShow">
                            @include('intranet::notices.show_fields_notice')
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-warning" type="button" v-print="{id: 'modal-view-notices-body', beforeOpenCallback, openCallback, closeCallback}" :disabled="printOpened">
                                <i class="fa fa-print mr-2" v-if="!printOpened"></i>
                                <div class="spinner mr-2" style="position: sticky; float: inline-start; width: 18px; height: 18px; margin: auto;" v-else></div>
                                @lang('print')
                            </button>
                            <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end #modal-view-notices-all -->



            <fieldset class="card shadow col-md-12 mt-4 mb-4" style="'border: 1px solid rgb(0 0 0 / 6%); border-radius: 5px; padding: 10px; margin: auto; max-width: 90%;">
                <div style="display: flex;">
                    <legend style="max-width: 50px; top: -20px; position: absolute;">
                        <div style="text-align: center; border-radius: 3px; height: 50px; background-color: #2196f3e8">
                            <i class="fa fa-clock" aria-hidden="true" style="vertical-align: bottom; color: white;"></i>
                        </div>
                    </legend>
                    <h5 class="text-black-transparent-6" style="margin-left: 80px; margin-bottom: 20px;">Últimos registros modificados</h5>
                </div>
                <div class="table-responsive">
                    <table-component
                        id="entradas-recientes"
                        :data="dataList"
                        sort-by="recientes"
                        sort-order="asc"
                        table-class="table table-hover m-b-0"
                        :show-filter="false"
                        :show-caption="false"
                        filter-placeholder="@lang('Quick filter')"
                        filter-no-results="@lang('No recent records')"
                        filter-input-class="form-control col-md-4"
                        :cache-lifetime="0"
                        >
                        <table-column show="updated_at" label="@lang('Updated_at')"></table-column>

                        <table-column show="consecutive" label="@lang('Consecutive')"></table-column>

                        <table-column show="title" label="@lang('Título/Asunto')"></table-column>

                        <table-column show="state" label="@lang('State')">
                            <template slot-scope="row">

                                {{-- Documentos Electronicos --}}
                                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.state" v-if="row.state=='Elaboración' && row.module == 'Documentos electrónicos'"></div>
                                <div class="text-black text-center p-4 bg-yellow states_style" v-html="row.state" v-else-if="row.state=='Revisión' || row.state == 'Revisión (Editado por externo)' && row.module == 'Documentos electrónicos'"></div>
                                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.state" v-else-if="row.state=='Pendiente de firma' && row.module == 'Documentos electrónicos'"></div>
                                <div class="text-white text-center p-4 bg-red states_style" v-html="row.state" v-else-if="row.state=='Devuelto para modificaciones' && row.module == 'Documentos electrónicos'"></div>
                                <div class="text-white text-center p-4 bg-green states_style" v-html="row.state" v-else-if="row.state=='Público' && row.module == 'Documentos electrónicos'"></div>

                                {{-- Externa recibida --}}
                                <div class="text-white text-center p-2 bg-orange states_style" v-html="row.state" v-else-if="row.state=='Devuelto' && row.module == 'Externa recibida'"></div>
                                <div class="text-white text-center p-2 bg-green states_style" v-html="row.state" v-else-if="row.state=='Público' && row.module == 'Externa recibida'"></div>
                                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.state" v-else-if="row.state=='Elaboración' && row.module == 'Externa recibida'"></div>

                                {{-- Externa enviada --}}
                                <div class="text-white text-center p-2 bg-orange states_style" v-html="row.state" v-else-if="row.state=='Devuelto' && row.module == 'Externa'"></div>
                                <div class="text-white text-center p-2 bg-green states_style" v-html="row.state" v-else-if="row.state=='Público' && row.module == 'Externa'"></div>
                                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.state" v-else-if="row.state=='Elaboración' && row.module == 'Externa'"></div>
                                <div class="text-black text-center p-4 bg-yellow states_style" v-html="row.state" v-else-if="row.state=='Revisión' && row.module == 'Externa'"></div>
                                <div class="text-white text-center p-4 bg-cyan states_style" v-html="row.state" v-else-if="row.state=='Aprobación' && row.module == 'Externa'"></div>
                                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.state" v-else-if="row.state=='Pendiente de firma' && row.module == 'Externa'"></div>

                                {{-- Interna --}}
                                <div class="text-white text-center p-2 bg-orange states_style" v-html="row.state" v-else-if="row.state=='Devuelto' && row.module == 'Interna'"></div>
                                <div class="text-white text-center p-2 bg-green states_style" v-html="row.state" v-else-if="row.state=='Público' && row.module == 'Interna'"></div>
                                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.state" v-else-if="row.state=='Elaboración' && row.module == 'Interna'"></div>
                                <div class="text-black text-center p-4 bg-yellow states_style" v-html="row.state" v-else-if="row.state=='Revisión' && row.module == 'Interna'"></div>
                                <div class="text-white text-center p-4 bg-cyan states_style" v-html="row.state" v-else-if="row.state=='Aprobación' && row.module == 'Interna'"></div>
                                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.state" v-else-if="row.state=='Pendiente de firma' && row.module == 'Interna'"></div>

                                {{-- Pqrs --}}
                                <div class="text-center estado_a_tiempo" v-html="row.state" v-else-if="row.state=='Asignado' && row.module == 'PQRS'"></div>
                                <div class="text-center estado_a_tiempo" v-html="row.state" v-else-if="row.state=='En trámite' && row.module == 'PQRS'"></div>
                                <div class="text-center estado_a_tiempo" v-html="row.state" v-else-if="row.state=='Esperando respuesta del ciudadano' && row.module == 'PQRS'"></div>
                                <div class="text-center estado_a_tiempo" v-html="row.state" v-else-if="row.state=='Respuesta parcial' && row.module == 'PQRS'"></div>
                                <div class="text-center estado_finalizado_a_tiempo" v-html="row.state" v-else-if="row.state=='Finalizado' && row.module == 'PQRS'"></div>
                                <div class="text-center estado_a_tiempo" v-html="row.state" v-else-if="row.state=='Devuelto' && row.module == 'PQRS'"></div>
                                <div class="text-center estado_cancelado" v-html="row.state" v-else-if="row.state=='Cancelado' && row.module == 'PQRS'"></div>

                                {{-- Estado por defecto --}}
                                <div class="text-center estado_cancelado" v-html="row.state" v-else></div>

                            </template>
                        </table-column>

                        <table-column show="module" label="Módulo"></table-column>

                        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
                            <template slot-scope="row">

                                <a :href="row.link" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" v-if="row.module != 'PQRS'" :title="(row.module != 'Interna' && row.state != 'Público' && row.state != 'Pendiente de firma' && row.permission_edit) || (row.module == 'Interna' && row.permission_edit) ? 'Tramitar' : 'Ver Detalles'"><i :class="(row.module != 'Interna' && row.state != 'Público' && row.state != 'Pendiente de firma' && row.permission_edit) || (row.module == 'Interna' && row.permission_edit) ? 'fas fa-pencil-alt' : 'fas fa-search'"></i></a>

                                <a :href="row.link" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" v-else :title="(row.module == 'PQRS' && row.elaborated_now == {{ Auth::user()->id }} && state != 'Finalizado') ? 'Tramitar' : 'Ver Detalles'">
                                    <i :class="(row.elaborated_now == {{ Auth::user()->id }} && state != 'Finalizado') ? 'fas fa-pencil-alt' : 'fas fa-search'"></i>
                                </a>

                            </template>
                        </table-column>

                    </table-component>
                </div>
            </fieldset>
        </div>
        <!-- end widget -->

        <search-result-universal v-show="query" ref="resultado" ruta-get-datos="consulta-buscador-dashboard"></search-result-universal>

    @else
        <h1 class="page-header text-center m-b-40 m-t-30">Terminos del servicio</h1>

        <p style="text-align: justify;">

            La empresa <strong>{{ env("APP_NAME") }}</strong> ofrece un servicio de publicación de documentos electrónicos en la {{ env("APP_NAME") }}. Este servicio permite a los usuarios publicar documentos en formato PDF o DOCX para que sean accesibles por otros usuarios de la {{ env("APP_NAME") }}.

            Al utilizar el servicio de publicación de documentos electrónicos en la {{ env("APP_NAME") }}, usted acepta los siguientes términos y condiciones:<br />
            <ul>
                <li>Usted es responsable del contenido de los documentos que publica. La empresa no se responsabiliza de la exactitud, integridad o legalidad del contenido de los documentos publicados.</li>
                <li>Los documentos publicados deben cumplir con las políticas y procedimientos de la empresa. La empresa se reserva el derecho de eliminar cualquier documento que no cumpla con estas políticas y procedimientos.</li>
                <li>La empresa puede cambiar estos términos y condiciones en cualquier momento. Usted será notificado de cualquier cambio por correo electrónico o a través de la {{ env("APP_NAME") }}.</li>
            </ul>
        </p>

        <hr>

        <p class="mb-4">Adicional a la aceptación de términos del servicio, debe cambiar la contraseña, ya que es primer vez que ingresa al sistema de {{ env("APP_NAME") }}.</p>

        <form action="{{ route('service-terms-change-password') }}" method="POST">
            @csrf
            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }} row m-b-15 col-sm-6">
                {!! Form::label('password', trans('Nueva contraseña').':', ['class' => 'col-form-label col-md-4 required']) !!}
                <div class="col-md-6{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input required="required" type="password" class="form-control" name="password">
                    <small>Ingrese una contraseña que tenga mínimo 8 caracteres, debe contener al menos una letra mayúscula, una minúscula, un número y un símbolo - /; 0) & @.?! %*.</small><br />
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }} row m-b-15 col-sm-6">
                {!! Form::label('password', trans('Confirmar nueva contraseña').':', ['class' => 'col-form-label col-md-4 required']) !!}
                <div class="col-md-6{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input required="required" type="password" name="password_confirmation" class="form-control">
                    <small>Por favor confirme la contraseña que ingresó.</small>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-12" style="display: flex; justify-content: center;">
                <button type="submit" class="btn btn-success"><i class="fa fa-check mr-2"></i>Aceptar términos y continuar</button>
            </div>
        </form>



    @endif



@endsection

@push('css')

<style>
    .card-desc img{
        width: 100% !important;
    }
    /* card details start  */
    @import url('https://fonts.googleapis.com/css?family=Raleway:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Roboto+Condensed:400,400i,700,700i');
    section{
        padding: 20px 0;
        padding-top: 5px;
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
        /* min-height: 380px; */
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
        padding: 0.5rem;
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
        padding: .2rem 1rem;
        font-size: .75rem;
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
        /* text-transform: uppercase; */
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

    #estilo-main
    {
        background: #FFFFFF;
        margin: 0 auto;
        font-size: 13px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        position: relative;
        width: 100%;
        min-height: 100%;
        left: 0;
        top: 0;
        cursor: default;
        overflow: hidden;
    }

    table, ul.estilo-hmenu
    {
        font-size: 13px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
    }

    h1, h2, h3, h4, h5, h6, p, a, ul, ol, li
    {
        margin: 0;
        padding: 0;
    }

    .estilo-button
    {
        border: 0;
        border-collapse: separate;
        -webkit-background-origin: border !important;
        -moz-background-origin: border !important;
        background-origin: border-box !important;
        background: #1BA1DA;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        border-width: 0;
        padding: 0 21px;
        margin: 0 auto;
        height: 28px;
    }

    .estilo-postcontent, .estilo-postheadericons, .estilo-postfootericons, .estilo-blockcontent, ul.estilo-vmenu a
    {
        text-align: left;
    }

    .estilo-postcontent, .estilo-postcontent li, .estilo-postcontent table, .estilo-postcontent a, .estilo-postcontent a:link, .estilo-postcontent a:visited, .estilo-postcontent a.visited, .estilo-postcontent a:hover, .estilo-postcontent a.hovered
    {
        font-size: 15px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        line-height: 125%;
    }

    .estilo-postcontent p
    {
        margin: 13px 0;
    }

    .estilo-postcontent h1, .estilo-postcontent h1 a, .estilo-postcontent h1 a:link, .estilo-postcontent h1 a:visited, .estilo-postcontent h1 a:hover, .estilo-postcontent h2, .estilo-postcontent h2 a, .estilo-postcontent h2 a:link, .estilo-postcontent h2 a:visited, .estilo-postcontent h2 a:hover, .estilo-postcontent h3, .estilo-postcontent h3 a, .estilo-postcontent h3 a:link, .estilo-postcontent h3 a:visited, .estilo-postcontent h3 a:hover, .estilo-postcontent h4, .estilo-postcontent h4 a, .estilo-postcontent h4 a:link, .estilo-postcontent h4 a:visited, .estilo-postcontent h4 a:hover, .estilo-postcontent h5, .estilo-postcontent h5 a, .estilo-postcontent h5 a:link, .estilo-postcontent h5 a:visited, .estilo-postcontent h5 a:hover, .estilo-postcontent h6, .estilo-postcontent h6 a, .estilo-postcontent h6 a:link, .estilo-postcontent h6 a:visited, .estilo-postcontent h6 a:hover, .estilo-blockheader .t, .estilo-blockheader .t a, .estilo-blockheader .t a:link, .estilo-blockheader .t a:visited, .estilo-blockheader .t a:hover, .estilo-vmenublockheader .t, .estilo-vmenublockheader .t a, .estilo-vmenublockheader .t a:link, .estilo-vmenublockheader .t a:visited, .estilo-vmenublockheader .t a:hover, .estilo-headline, .estilo-headline a, .estilo-headline a:link, .estilo-headline a:visited, .estilo-headline a:hover, .estilo-slogan, .estilo-slogan a, .estilo-slogan a:link, .estilo-slogan a:visited, .estilo-slogan a:hover, .estilo-postheader, .estilo-postheader a, .estilo-postheader a:link, .estilo-postheader a:visited, .estilo-postheader a:hover
    {
        font-size: 22px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        line-height: 120%;
    }

    .estilo-postcontent a, .estilo-postcontent a:link
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: none;
        color: #1BA1DA;
    }

    .estilo-postcontent a:visited, .estilo-postcontent a.visited
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: none;
        color: #1BA1DA;
    }

    .estilo-postcontent  a:hover, .estilo-postcontent a.hover
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: underline;
        color: #A8CF45;
    }

    .estilo-postcontent h1
    {
        color: #6FB154;
        margin: 10px 0 0;
        font-size: 26px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-blockcontent h1
    {
        margin: 10px 0 0;
        font-size: 26px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-postcontent h1 a, .estilo-postcontent h1 a:link, .estilo-postcontent h1 a:hover, .estilo-postcontent h1 a:visited, .estilo-blockcontent h1 a, .estilo-blockcontent h1 a:link, .estilo-blockcontent h1 a:hover, .estilo-blockcontent h1 a:visited
    {
        font-size: 26px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-postcontent h2
    {
        color: #8D9CAA;
        margin: 10px 0 0;
        font-size: 22px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-blockcontent h2
    {
    margin: 10px 0 0;
    font-size: 22px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-postcontent h2 a, .estilo-postcontent h2 a:link, .estilo-postcontent h2 a:hover, .estilo-postcontent h2 a:visited, .estilo-blockcontent h2 a, .estilo-blockcontent h2 a:link, .estilo-blockcontent h2 a:hover, .estilo-blockcontent h2 a:visited
    {
    font-size: 22px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-postcontent h3
    {
    color: #6FB154;
    margin: 10px 0 0;
    font-size: 20px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-blockcontent h3
    {
    margin: 10px 0 0;
    font-size: 20px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-postcontent h3 a, .estilo-postcontent h3 a:link, .estilo-postcontent h3 a:hover, .estilo-postcontent h3 a:visited, .estilo-blockcontent h3 a, .estilo-blockcontent h3 a:link, .estilo-blockcontent h3 a:hover, .estilo-blockcontent h3 a:visited
    {
    font-size: 20px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-postcontent h4
    {
    color: #4E6883;
    margin: 10px 0 0;
    font-size: 18px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-blockcontent h4
    {
    margin: 10px 0 0;
    font-size: 18px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-postcontent h4 a, .estilo-postcontent h4 a:link, .estilo-postcontent h4 a:hover, .estilo-postcontent h4 a:visited, .estilo-blockcontent h4 a, .estilo-blockcontent h4 a:link, .estilo-blockcontent h4 a:hover, .estilo-blockcontent h4 a:visited
    {
    font-size: 18px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-postcontent h5
    {
    color: #4E6883;
    margin: 10px 0 0;
    font-size: 15px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-blockcontent h5
    {
    margin: 10px 0 0;
    font-size: 15px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-postcontent h5 a, .estilo-postcontent h5 a:link, .estilo-postcontent h5 a:hover, .estilo-postcontent h5 a:visited, .estilo-blockcontent h5 a, .estilo-blockcontent h5 a:link, .estilo-blockcontent h5 a:hover, .estilo-blockcontent h5 a:visited
    {
    font-size: 15px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-postcontent h6
    {
    color: #A6B7C9;
    margin: 10px 0 0;
    font-size: 13px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-blockcontent h6
    {
    margin: 10px 0 0;
    font-size: 13px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-postcontent h6 a, .estilo-postcontent h6 a:link, .estilo-postcontent h6 a:hover, .estilo-postcontent h6 a:visited, .estilo-blockcontent h6 a, .estilo-blockcontent h6 a:link, .estilo-blockcontent h6 a:hover, .estilo-blockcontent h6 a:visited
    {
    font-size: 13px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    header, footer, article, nav, #estilo-hmenu-bg, .estilo-sheet, .estilo-hmenu a, .estilo-vmenu a, .estilo-slidenavigator > a, .estilo-checkbox:before, .estilo-radiobutton:before
    {
    -webkit-background-origin: border !important;
    -moz-background-origin: border !important;
    background-origin: border-box !important;
    }

    header, footer, article, nav, #estilo-hmenu-bg, .estilo-sheet, .estilo-slidenavigator > a, .estilo-checkbox:before, .estilo-radiobutton:before
    {
    display: block;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    }

    ul
    {
    list-style-type: none;
    }

    ol
    {
    list-style-position: inside;
    }

    html, body
    {
    height: 100%;
    }

    /**
    * 2. Prevent iOS text size adjust after orientation change, without disabling
    *    user zoom.
    * https://github.com/necolas/normalize.css
    */

    html
    {
    -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%;
    }

    body
    {
    padding: 0;
    margin: 0;
    min-width: 700px;
    color: #696969;
    }

    .estilo-header:after, #estilo-header-bg:after, .estilo-layout-cell:after, .estilo-layout-wrapper:after, .estilo-footer:after, .estilo-nav:after, #estilo-hmenu-bg:after, .estilo-sheet:after, .cleared, .clearfix:after
    {
    clear: both;
    display: table;
    content: '';
    }

    form
    {
    padding: 0 !important;
    margin: 0 !important;
    }

    table.position
    {
    position: relative;
    width: 100%;
    table-layout: fixed;
    }

    li h1, .estilo-postcontent li h1, .estilo-blockcontent li h1
    {
    margin: 1px;
    }

    li h2, .estilo-postcontent li h2, .estilo-blockcontent li h2
    {
    margin: 1px;
    }

    li h3, .estilo-postcontent li h3, .estilo-blockcontent li h3
    {
    margin: 1px;
    }

    li h4, .estilo-postcontent li h4, .estilo-blockcontent li h4
    {
    margin: 1px;
    }

    li h5, .estilo-postcontent li h5, .estilo-blockcontent li h5
    {
    margin: 1px;
    }

    li h6, .estilo-postcontent li h6, .estilo-blockcontent li h6
    {
    margin: 1px;
    }

    li p, .estilo-postcontent li p, .estilo-blockcontent li p
    {
    margin: 1px;
    }

    .estilo-shapes
    {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    overflow: hidden;
    z-index: 0;
    }

    .estilo-slider-inner
    {
    position: relative;
    overflow: hidden;
    width: 100%;
    height: 100%;
    }

    .estilo-slidenavigator > a
    {
    display: inline-block;
    vertical-align: middle;
    outline-style: none;
    font-size: 1px;
    }

    .estilo-slidenavigator > a:last-child
    {
    margin-right: 0 !important;
    }

    .estilo-positioncontrol-1597702934
    {
    display: block;
    left: 100%;
    margin-left: -650px;
    position: absolute;
    top: -2px;
    width: 685px;
    height: 52px;
    z-index: 101;
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    transform: rotate(0deg);
    }

    .estilo-positioncontrol-370885776
    {
    display: block;
    left: 0.35%;
    margin-left: -1px;
    position: absolute;
    top: 12px;
    width: 430px;
    height: 100px;
    z-index: 102;
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    transform: rotate(0deg);
    }

    .estilo-header
    {
    margin: 0 auto;
    background-repeat: no-repeat;
    height: 130px;
    background-image: url('../images/header.png');
    background-position: center top;
    position: relative;
    min-width: 700px;
    max-width: 1728px;
    width: 95%;
    z-index: auto !important;
    }

    .custom-responsive .estilo-header
    {
    background-image: url('../images/header.png');
    background-position: center top;
    }

    .default-responsive .estilo-header, .default-responsive #estilo-header-bg
    {
    background-image: url('../images/header.png');
    background-position: center center;
    background-size: cover;
    }

    .estilo-header>div.estilo-nostyle, .estilo-header>div.estilo-block, .estilo-header>div.estilo-post
    {
    position: absolute;
    z-index: 101;
    }

    .estilo-nav
    {
    position: absolute;
    margin: 0;
    bottom: 0;
    width: 100%;
    z-index: 100;
    text-align: right;
    }

    ul.estilo-hmenu a, ul.estilo-hmenu a:link, ul.estilo-hmenu a:visited, ul.estilo-hmenu a:hover
    {
    outline: none;
    position: relative;
    z-index: 11;
    }

    ul.estilo-hmenu, ul.estilo-hmenu ul
    {
    display: block;
    margin: 0;
    padding: 0;
    border: 0;
    list-style-type: none;
    }

    ul.estilo-hmenu li
    {
    position: relative;
    z-index: 5;
    display: block;
    float: left;
    background: none;
    margin: 0;
    padding: 0;
    border: 0;
    }

    ul.estilo-hmenu li:hover
    {
    z-index: 10000;
    white-space: normal;
    }

    ul.estilo-hmenu:after, ul.estilo-hmenu ul:after
    {
    content: ".";
    height: 0;
    display: block;
    visibility: hidden;
    overflow: hidden;
    clear: both;
    }

    ul.estilo-hmenu, ul.estilo-hmenu ul
    {
    min-height: 0;
    }

    ul.estilo-hmenu
    {
    display: inline-block;
    vertical-align: bottom;
    }

    .estilo-nav:before
    {
    content: ' ';
    }

    nav.estilo-nav
    {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    }

    .estilo-hmenu-extra1
    {
    position: relative;
    display: block;
    float: left;
    width: auto;
    height: auto;
    background-position: center;
    }

    .estilo-hmenu-extra2
    {
    position: relative;
    display: block;
    float: right;
    width: auto;
    height: auto;
    background-position: center;
    }

    .estilo-menuitemcontainer
    {
    margin: 0 auto;
    }

    ul.estilo-hmenu>li
    {
    margin-left: 5px;
    }

    ul.estilo-hmenu>li:first-child
    {
    margin-left: 2px;
    }

    ul.estilo-hmenu>li:last-child, ul.estilo-hmenu>li.last-child
    {
    margin-right: 2px;
    }

    ul.estilo-hmenu>li>a
    {
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    padding: 0 5px;
    margin: 0 auto;
    position: relative;
    display: block;
    height: 54px;
    cursor: pointer;
    text-decoration: none;
    color: #A8CF45;
    line-height: 54px;
    text-align: center;
    }

    .estilo-hmenu>li>a, .estilo-hmenu>li>a:link, .estilo-hmenu>li>a:visited, .estilo-hmenu>li>a.active, .estilo-hmenu>li>a:hover
    {
    font-size: 22px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    font-weight: normal;
    font-style: normal;
    text-decoration: none;
    text-transform: none;
    font-variant: normal;
    text-align: left;
    }

    ul.estilo-hmenu>li>a.active
    {
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    padding: 0 5px;
    margin: 0 auto;
    color: #075F12;
    text-decoration: none;
    }

    ul.estilo-hmenu>li>a:visited, ul.estilo-hmenu>li>a:hover, ul.estilo-hmenu>li:hover>a
    {
    text-decoration: none;
    }

    ul.estilo-hmenu>li>a:hover, .desktop ul.estilo-hmenu>li:hover>a
    {
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    padding: 0 5px;
    margin: 0 auto;
    }

    ul.estilo-hmenu>li>a:hover, .desktop ul.estilo-hmenu>li:hover>a
    {
    color: #075F12;
    text-decoration: none;
    }

    ul.estilo-hmenu li li a
    {
    background: #B9C2CB;
    background: transparent;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    padding: 0 10px;
    margin: 0 auto;
    }

    ul.estilo-hmenu li li
    {
    float: none;
    width: auto;
    margin-top: 2px;
    margin-bottom: 2px;
    }

    .desktop ul.estilo-hmenu li li ul>li:first-child
    {
    margin-top: 0;
    }

    ul.estilo-hmenu li li ul>li:last-child
    {
    margin-bottom: 0;
    }

    .estilo-hmenu ul a
    {
    display: block;
    white-space: nowrap;
    height: 28px;
    min-width: 7em;
    border: 0 solid transparent;
    text-align: left;
    line-height: 28px;
    color: #A8CF45;
    font-size: 18px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    text-decoration: none;
    margin: 0;
    }

    .estilo-hmenu ul a:link, .estilo-hmenu ul a:visited, .estilo-hmenu ul a.active, .estilo-hmenu ul a:hover
    {
    text-align: left;
    line-height: 28px;
    color: #A8CF45;
    font-size: 18px;
    font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    text-decoration: none;
    margin: 0;
    }

    ul.estilo-hmenu li li:after
    {
    display: block;
    position: absolute;
    content: ' ';
    height: 0;
    top: -1px;
    left: 0;
    right: 0;
    z-index: 1;
    border-bottom: 1px dotted #C6D1DD;
    }

    .desktop ul.estilo-hmenu li li:first-child:before, .desktop ul.estilo-hmenu li li:first-child:after
    {
    display: none;
    }

    ul.estilo-hmenu ul li a:hover, .desktop ul.estilo-hmenu ul li:hover>a
    {
        background: #FFFFFF;
        background: transparent;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        margin: 0 auto;
    }

    .estilo-hmenu ul a:hover
    {
        text-decoration: none;
        color: #075F12;
    }

    .desktop .estilo-hmenu ul li:hover>a
    {
        color: #075F12;
    }

    ul.estilo-hmenu ul:before
    {
        background: #EFF2F5;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        border: 1px dotted rgba(207, 216, 226, 0.9);
        margin: 0 auto;
        display: block;
        position: absolute;
        content: ' ';
        z-index: 1;
    }

    .desktop ul.estilo-hmenu li:hover>ul
    {
        visibility: visible;
        top: 100%;
    }

    .desktop ul.estilo-hmenu li li:hover>ul
    {
        top: 0;
        left: 100%;
    }

    ul.estilo-hmenu ul
    {
        visibility: hidden;
        position: absolute;
        z-index: 10;
        left: 0;
        top: 0;
        background-image: url('../images/spacer.gif');
    }

    .desktop ul.estilo-hmenu>li>ul
    {
        padding: 16px 36px 36px 36px;
        margin: -10px 0 0 -30px;
    }

    .desktop ul.estilo-hmenu ul ul
    {
        padding: 36px 36px 36px 18px;
        margin: -36px 0 0 -5px;
    }

    .desktop ul.estilo-hmenu ul.estilo-hmenu-left-to-right
    {
        right: auto;
        left: 0;
        margin: -10px 0 0 -30px;
    }

    .desktop ul.estilo-hmenu ul.estilo-hmenu-right-to-left
    {
        left: auto;
        right: 0;
        margin: -10px -30px 0 0;
    }

    .desktop ul.estilo-hmenu li li:hover>ul.estilo-hmenu-left-to-right
    {
        right: auto;
        left: 100%;
    }

    .desktop ul.estilo-hmenu li li:hover>ul.estilo-hmenu-right-to-left
    {
        left: auto;
        right: 100%;
    }

    .desktop ul.estilo-hmenu ul ul.estilo-hmenu-left-to-right
    {
        right: auto;
        left: 0;
        padding: 36px 36px 36px 18px;
        margin: -36px 0 0 -5px;
    }

    .desktop ul.estilo-hmenu ul ul.estilo-hmenu-right-to-left
    {
        left: auto;
        right: 0;
        padding: 36px 18px 36px 36px;
        margin: -36px -5px 0 0;
    }

    .desktop ul.estilo-hmenu li ul>li:first-child
    {
        margin-top: 0;
    }

    .desktop ul.estilo-hmenu li ul>li:last-child
    {
        margin-bottom: 0;
    }

    .desktop ul.estilo-hmenu ul ul:before
    {
        border-radius: 5px;
        top: 30px;
        bottom: 30px;
        right: 30px;
        left: 12px;
    }

    .desktop ul.estilo-hmenu>li>ul:before
    {
        top: 10px;
        right: 30px;
        bottom: 30px;
        left: 30px;
    }

    .desktop ul.estilo-hmenu>li>ul.estilo-hmenu-left-to-right:before
    {
        right: 30px;
        left: 30px;
    }

    .desktop ul.estilo-hmenu>li>ul.estilo-hmenu-right-to-left:before
    {
        right: 30px;
        left: 30px;
    }

    .desktop ul.estilo-hmenu ul ul.estilo-hmenu-left-to-right:before
    {
        right: 30px;
        left: 12px;
    }

    .desktop ul.estilo-hmenu ul ul.estilo-hmenu-right-to-left:before
    {
        right: 12px;
        left: 30px;
    }

    .desktop ul.estilo-hmenu>li.ext>a
    {
        white-space: nowrap;
    }

    .desktop ul.estilo-hmenu>li.ext>a:hover, .desktop ul.estilo-hmenu>li.ext:hover>a, .desktop ul.estilo-hmenu>li.ext:hover>a.active
    {
        background: none;
        padding: 0 5px 0 5px;
        margin: -0 -0 0 -0;
        overflow: hidden;
        position: relative;
        border: none;
        border-radius: 0;
        box-shadow: none;
        color: #075F12;
    }

    .desktop ul.estilo-hmenu>li.ext>a:hover:before, .desktop ul.estilo-hmenu>li.ext:hover>a:before, .desktop ul.estilo-hmenu>li.ext:hover>a.active:before
    {
        position: absolute;
        content: ' ';
        top: 0;
        right: 0;
        left: 0;
        bottom: -1px;
        background-color: #EFF2F5;
        border: 1px Dotted rgba(207, 216, 226, 0.9);
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        box-shadow: 0 0 0 rgba(0, 0, 0, 0.8);
        z-index: -1;
    }

    .desktop ul.estilo-hmenu>li.ext:hover>ul
    {
    padding-top: 5px;
    margin-top: 0;
    }

    .desktop ul.estilo-hmenu>li.ext:hover>ul:before
    {
    top: -1px;
    clip: rect(7px, auto, auto, auto);
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    box-shadow: 0 0 0 rgba(0, 0, 0, 0.8);
    }

    ul.estilo-hmenu>li.ext>.ext-r, ul.estilo-hmenu>li.ext>.ext-l, ul.estilo-hmenu>li.ext>.ext-m, ul.estilo-hmenu>li.ext>.ext-off
    {
    display: none;
    z-index: 12;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    }

    .desktop ul.estilo-hmenu>li.ext>ul
    {
    z-index: 13;
    }

    .desktop ul.estilo-hmenu>li.ext.ext-r:hover>.ext-r, .desktop ul.estilo-hmenu>li.ext.ext-l:hover>.ext-l
    {
        position: absolute;
        display: block;
        overflow: hidden;
        height: 7px;
        top: 100%;
        padding-top: 1px;
        margin-top: -1px;
    }

    .desktop ul.estilo-hmenu>li.ext:hover>.ext-r:before, .desktop ul.estilo-hmenu>li.ext:hover>.ext-l:before
    {
        position: absolute;
        content: ' ';
        top: 0;
        bottom: -1px;
        background-color: #EFF2F5;
        border: 1px Dotted rgba(207, 216, 226, 0.9);
        box-shadow: 0 0 0 rgba(0, 0, 0, 0.8);
    }

    .desktop ul.estilo-hmenu>li.ext.ext-r:hover>.ext-r
    {
        left: 100%;
        right: auto;
        padding-left: 0;
        margin-left: 0;
        padding-right: 1px;
        margin-right: -1px;
    }

    .desktop ul.estilo-hmenu>li.ext.ext-r:hover>.ext-r:before
    {
        right: 0;
        left: -1px;
        border-top-left-radius: 0;
        border-top-right-radius: 5px;
    }

    .desktop ul.estilo-hmenu>li.ext.ext-l:hover>.ext-l
    {
        right: 100%;
        left: auto;
        padding-right: 0;
        margin-right: 0;
        padding-left: 1px;
        margin-left: -1px;
    }

    .desktop ul.estilo-hmenu>li.ext.ext-l:hover>.ext-l:before
    {
        right: -1px;
        left: 0;
        border-top-right-radius: 0;
        border-top-left-radius: 5px;
    }

    .desktop ul.estilo-hmenu>li.ext:hover>.ext-m, .desktop ul.estilo-hmenu>li.ext:hover>.ext-off
    {
        position: absolute;
        display: block;
        overflow: hidden;
        height: 6px;
        top: 100%;
    }

    .desktop ul.estilo-hmenu>li.ext.ext-r:hover>.ext-m
    {
        left: -0;
        right: 0;
        padding-right: 0;
        padding-left: 0;
    }

    .desktop ul.estilo-hmenu>li.ext:hover>.ext-off
    {
        left: -0;
        right: -0;
        padding-left: 0;
        padding-right: 0;
    }

    .desktop ul.estilo-hmenu>li.ext.ext-l:hover>.ext-m
    {
        right: -0;
        left: 0;
        padding-left: 0;
        padding-right: 0;
    }

    .desktop ul.estilo-hmenu>li.ext.ext-l.ext-r:hover>.ext-m
    {
        right: -0;
        left: -0;
        padding-left: 0;
        padding-right: 0;
    }

    .desktop ul.estilo-hmenu>li.ext:hover>.ext-m:before, .desktop ul.estilo-hmenu>li.ext:hover>.ext-off:before
    {
        position: absolute;
        content: ' ';
        top: -1px;
        bottom: -1px;
        background-color: #EFF2F5;
        border: 1px Dotted rgba(207, 216, 226, 0.9);
        box-shadow: 0 0 0 rgba(0, 0, 0, 0.8);
    }

    .desktop ul.estilo-hmenu>li.ext.ext-r:hover>.ext-m:before
    {
        right: -1px;
        left: 0;
    }

    .desktop ul.estilo-hmenu>li.ext.ext-l:hover>.ext-m:before
    {
        left: -1px;
        right: 0;
    }

    .desktop ul.estilo-hmenu>li.ext.ext-l.ext-r:hover>.ext-m:before
    {
        left: -1px;
        right: -1px;
    }

    .estilo-sheet
    {
        margin: 0 auto;
        position: relative;
        cursor: auto;
        width: 95%;
        min-width: 700px;
        max-width: 1728px;
        z-index: auto !important;
    }

    .estilo-layout-wrapper
    {
        position: relative;
        margin: 0 auto 0 auto;
        z-index: auto !important;
    }

    .estilo-content-layout
    {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .estilo-content-layout-row
    {
        display: table-row;
    }

    .estilo-layout-cell
    {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        display: table-cell;
        vertical-align: top;
    }

    .estilo-postcontent .estilo-content-layout
    {
        border-collapse: collapse;
    }

    .estilo-vmenublock
    {
        background: #FAFAFA;
        background: rgba(250, 250, 250, 0.95);
        border: 1px solid #DEDEDE;
        margin: 3px;
    }

    div.estilo-vmenublock img
    {
        margin: 0;
    }

    .estilo-vmenublockheader
    {
        padding: 10px 0;
        margin: 0 auto;
    }

    .estilo-vmenublockheader .t, .estilo-vmenublockheader .t a, .estilo-vmenublockheader .t a:link, .estilo-vmenublockheader .t a:visited, .estilo-vmenublockheader .t a:hover
    {
        color: #FA6114;
        font-size: 20px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        margin: 0 10px;
    }

    .estilo-vmenublockcontent
    {
        margin: 0 auto;
    }

    ul.estilo-vmenu, ul.estilo-vmenu ul
    {
        list-style: none;
        display: block;
    }

    ul.estilo-vmenu, ul.estilo-vmenu li
    {
        display: block;
        margin: 0;
        padding: 0;
        width: auto;
        line-height: 0;
    }

    ul.estilo-vmenu
    {
        margin-top: 0;
        margin-bottom: 0;
    }

    ul.estilo-vmenu ul
    {
        display: none;
        margin: 0;
        padding: 0;
        position: relative;
    }

    ul.estilo-vmenu ul.active
    {
        display: block;
    }

    ul.estilo-vmenu>li>a
    {
        padding: 0 15px;
        margin: 0 auto;
        font-size: 18px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        text-decoration: none;
        color: #A8CF45;
        min-height: 30px;
        line-height: 30px;
    }

    ul.estilo-vmenu a
    {
        display: block;
        cursor: pointer;
        z-index: 1;
        position: relative;
    }

    ul.estilo-vmenu li
    {
        position: relative;
    }

    ul.estilo-vmenu>li
    {
        margin-top: 1px;
    }

    ul.estilo-vmenu>li>ul
    {
        padding: 0;
        margin-top: 0;
        margin-bottom: 0;
    }

    ul.estilo-vmenu>li:first-child
    {
        margin-top: 0;
    }

    ul.estilo-vmenu>li>a:before
    {
        content: url('../images/vmenuitemicon.png');
        margin-right: 5px;
        bottom: 2px;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
    }

    .opera ul.estilo-vmenu>li>a:before
    {
        bottom: 0;
    }

    ul.estilo-vmenu>li>a.active:before
    {
        content: url('../images/vmenuactiveitemicon.png');
        margin-right: 5px;
        bottom: 2px;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
    }

    .opera ul.estilo-vmenu>li>a.active:before
    {
        bottom: 0;
    }

    ul.estilo-vmenu>li>a:hover:before, ul.estilo-vmenu>li>a.active:hover:before, ul.estilo-vmenu>li:hover>a:before, ul.estilo-vmenu>li:hover>a.active:before
    {
        content: url('../images/vmenuhovereditemicon.png');
        margin-right: 5px;
        bottom: 2px;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
    }

    .opera ul.estilo-vmenu>li>a:hover:before, .opera   ul.estilo-vmenu>li>a.active:hover:before, .opera   ul.estilo-vmenu>li:hover>a:before, .opera   ul.estilo-vmenu>li:hover>a.active:before
    {
        bottom: 0;
    }

    ul.estilo-vmenu>li>a:hover, ul.estilo-vmenu>li>a.active:hover
    {
        padding: 0 15px;
        margin: 0 auto;
    }

    ul.estilo-vmenu>li>a:hover, ul.estilo-vmenu>li>a.active:hover
    {
        text-decoration: none;
    }

    ul.estilo-vmenu a:hover, ul.estilo-vmenu a.active:hover
    {
        color: #075F12;
    }

    ul.estilo-vmenu>li>a.active:hover>span.border-top, ul.estilo-vmenu>li>a.active:hover>span.border-bottom
    {
        background-color: transparent;
    }

    ul.estilo-vmenu>li>a.active
    {
        padding: 0 15px;
        margin: 0 auto;
        text-decoration: none;
        color: #075F12;
    }

    ul.estilo-vmenu>li:after, ul.estilo-vmenu>li>ul:after
    {
        display: block;
        position: absolute;
        content: ' ';
        left: 0;
        right: 0;
        top: -1px;
    }

    ul.estilo-vmenu>li:after, ul.estilo-vmenu>li>ul:after
    {
        z-index: 1;
        height: 0;
        border-bottom: 1px dotted #D0D6DC;
    }

    ul.estilo-vmenu>li:first-child:before, ul.estilo-vmenu>li:first-child:after
    {
        display: none;
    }

    ul.estilo-vmenu>li>ul:before
    {
        margin: 0 auto;
        display: block;
        position: absolute;
        content: ' ';
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }

    ul.estilo-vmenu li li a
    {
        margin: 0 auto;
        position: relative;
    }

    ul.estilo-vmenu ul li
    {
        margin: 0;
        padding: 0;
    }

    ul.estilo-vmenu li li
    {
        position: relative;
        margin-top: 0;
    }

    ul.estilo-vmenu ul a
    {
        display: block;
        position: relative;
        min-height: 22px;
        overflow: visible;
        padding: 0;
        padding-left: 27px;
        padding-right: 27px;
        z-index: 0;
        line-height: 22px;
        color: #6FB154;
        font-size: 13px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        text-decoration: none;
        margin-left: 0;
        margin-right: 0;
    }

    ul.estilo-vmenu ul a:visited, ul.estilo-vmenu ul a.active:hover, ul.estilo-vmenu ul a:hover, ul.estilo-vmenu ul a.active
    {
        line-height: 22px;
        color: #6FB154;
        font-size: 13px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        text-decoration: none;
        margin-left: 0;
        margin-right: 0;
    }

    ul.estilo-vmenu ul ul a
    {
        padding-left: 54px;
    }

    ul.estilo-vmenu ul ul ul a
    {
        padding-left: 81px;
    }

    ul.estilo-vmenu ul ul ul ul a
    {
        padding-left: 108px;
    }

    ul.estilo-vmenu ul ul ul ul ul a
    {
        padding-left: 135px;
    }

    ul.estilo-vmenu ul>li>a:hover, ul.estilo-vmenu ul>li>a.active:hover
    {
        margin: 0 auto;
    }

    ul.estilo-vmenu ul li a:hover, ul.estilo-vmenu ul li a.active:hover
    {
        text-decoration: none;
        color: #075F12;
    }

    ul.estilo-vmenu ul a:hover:after
    {
        background-position: center;
    }

    ul.estilo-vmenu ul a.active:hover:after
    {
        background-position: center;
    }

    ul.estilo-vmenu ul a.active:after
    {
        background-position: bottom;
    }

    ul.estilo-vmenu ul>li>a.active
    {
        margin: 0 auto;
    }

    ul.estilo-vmenu ul a.active, ul.estilo-vmenu ul a:hover, ul.estilo-vmenu ul a.active:hover
    {
        text-decoration: none;
        color: #075F12;
    }

    .estilo-block
    {
        background: #FAFAFA;
        background: rgba(250, 250, 250, 0.95);
        padding: 15px;
        margin: 3px;
    }

    div.estilo-block img
    {
        border: none;
        margin: 0;
    }

    .estilo-blockheader
    {
        border-bottom: 1px solid #006400;
        padding: 6px 5px;
        margin: 0 auto 2px;
    }

    .estilo-blockheader .t, .estilo-blockheader .t a, .estilo-blockheader .t a:link, .estilo-blockheader .t a:visited, .estilo-blockheader .t a:hover
    {
        color: #006600;
        font-size: 22px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        text-align: center;
        margin: 0 5px;
    }

    .estilo-blockcontent
    {
        padding: 5px;
        margin: 0 auto;
        color: #2E3D4C;
        font-size: 16px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        line-height: 175%;
    }

    .estilo-blockcontent table, .estilo-blockcontent li, .estilo-blockcontent a, .estilo-blockcontent a:link, .estilo-blockcontent a:visited, .estilo-blockcontent a:hover
    {
        color: #2E3D4C;
        font-size: 16px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        line-height: 175%;
    }

    .estilo-blockcontent p
    {
        margin: 0 5px;
    }

    .estilo-blockcontent a, .estilo-blockcontent a:link
    {
        color: #1BA1DA;
        font-size: 16px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-blockcontent a:visited, .estilo-blockcontent a.visited
    {
        color: #1BA1DA;
        font-size: 16px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: none;
    }

    .estilo-blockcontent a:hover, .estilo-blockcontent a.hover
    {
        color: #6FB154;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: none;
    }

    .estilo-block li
    {
        font-size: 13px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        line-height: 175%;
        color: #4D6580;
        margin: 5px 0 0 10px;
    }

    .estilo-breadcrumbs
    {
        margin: 0 auto;
    }

    a.estilo-button, a.estilo-button:link, a:link.estilo-button:link, body a.estilo-button:link, a.estilo-button:visited, body a.estilo-button:visited, input.estilo-button, button.estilo-button
    {
        text-decoration: none;
        font-size: 18px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        white-space: nowrap;
        text-align: center;
        color: #FFFFFF;
        margin: 0 5px 0 0 !important;
        overflow: visible;
        cursor: pointer;
        text-indent: 0;
        line-height: 28px;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
    }

    .estilo-button img
    {
        margin: 0;
        vertical-align: middle;
    }

    .firefox2 .estilo-button
    {
        display: block;
        float: left;
    }

    input, select, textarea, a.estilo-search-button span
    {
        vertical-align: middle;
        font-size: 18px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
    }

    .estilo-block select
    {
        width: 96%;
    }

    input.estilo-button
    {
        float: none !important;
        -webkit-appearance: none;
    }

    .estilo-button.active, .estilo-button.active:hover
    {
        background: #075F12;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        border-width: 0;
        padding: 0 21px;
        margin: 0 auto;
    }

    .estilo-button.active, .estilo-button.active:hover
    {
        color: #FFFFFF !important;
    }

    .estilo-button.hover, .estilo-button:hover
    {
        background: #6FB154;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        border-width: 0;
        padding: 0 21px;
        margin: 0 auto;
    }

    .estilo-button.hover, .estilo-button:hover
    {
        color: #FFFFFF !important;
    }

    input[type="text"], input[type="password"], input[type="email"], input[type="url"], input[type="color"], input[type="date"], input[type="datetime"], input[type="datetime-local"], input[type="month"], input[type="number"], input[type="range"], input[type="tel"], input[type="time"], input[type="week"], textarea
    {
        background: #FFFFFF;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
        border: 1px solid #D9DEE3;
        margin: 0 auto;
    }

    input[type="text"], input[type="password"], input[type="email"], input[type="url"], input[type="color"], input[type="date"], input[type="datetime"], input[type="datetime-local"], input[type="month"], input[type="number"], input[type="range"], input[type="tel"], input[type="time"], input[type="week"], textarea
    {
        width: auto;
        padding: 8px 0;
        color: #52784F !important;
        font-size: 16px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        text-indent: 10px;
        text-shadow: none;
    }

    input.estilo-error, textarea.estilo-error
    {
        background: #F9FAFB;
        border: 1px solid #E2341D;
        margin: 0 auto;
    }

    input.estilo-error, textarea.estilo-error
    {
        color: #3D5166 !important;
        font-size: 13px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
    }

    form.estilo-search input[type="text"]
    {
        background: #FFFFFF;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        border-width: 0;
        margin: 0 auto;
        width: 100%;
        padding: 3px 0;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        color: #76726F !important;
        font-size: 14px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
    }

    form.estilo-search
    {
        background-image: none;
        border: 0;
        display: block;
        position: relative;
        top: 0;
        padding: 0;
        margin: 5px;
        left: 0;
        line-height: 0;
        width: 240px;
    }

    form.estilo-search input, a.estilo-search-button
    {
        -webkit-appearance: none;
        top: 0;
        right: 0;
    }

    form.estilo-search>input, a.estilo-search-button
    {
        bottom: 0;
        left: 0;
        vertical-align: middle;
    }

    form.estilo-search input[type="submit"], input.estilo-search-button, a.estilo-search-button
    {
        border-radius: 0;
        margin: 0 auto;
    }

    form.estilo-search input[type="submit"], input.estilo-search-button, a.estilo-search-button
    {
        position: absolute;
        left: auto;
        display: block;
        border: none;
        background: url('../images/searchicon.png') center center no-repeat;
        width: 24px;
        height: 100%;
        padding: 0;
        color: #FFFFFF !important;
        cursor: pointer;
    }

    a.estilo-search-button span.estilo-search-button-text
    {
        display: none;
    }

    label.estilo-checkbox:before
    {
        background: #F9FAFB;
        -webkit-border-radius: 1px;
        -moz-border-radius: 1px;
        border-radius: 1px;
        border-width: 0;
        margin: 0 auto;
        width: 16px;
        height: 16px;
    }

    label.estilo-checkbox
    {
        cursor: pointer;
        font-size: 13px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        line-height: 16px;
        display: inline-block;
        color: #364049 !important;
    }

    .estilo-checkbox>input[type="checkbox"]
    {
        margin: 0 5px 0 0;
    }

    label.estilo-checkbox.active:before
    {
        background: #FC905A;
        -webkit-border-radius: 1px;
        -moz-border-radius: 1px;
        border-radius: 1px;
        border-width: 0;
        margin: 0 auto;
        width: 16px;
        height: 16px;
        display: inline-block;
    }

    label.estilo-checkbox.hovered:before
    {
        background: #D9DEE3;
        -webkit-border-radius: 1px;
        -moz-border-radius: 1px;
        border-radius: 1px;
        border-width: 0;
        margin: 0 auto;
        width: 16px;
        height: 16px;
        display: inline-block;
    }

    label.estilo-radiobutton:before
    {
        background: #F9FAFB;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        border-width: 0;
        margin: 0 auto;
        width: 12px;
        height: 12px;
    }

    label.estilo-radiobutton
    {
        cursor: pointer;
        font-size: 13px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        line-height: 12px;
        display: inline-block;
        color: #364049 !important;
    }

    .estilo-radiobutton>input[type="radio"]
    {
        vertical-align: baseline;
        margin: 0 5px 0 0;
    }

    label.estilo-radiobutton.active:before
    {
        background: #B9C2CB;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        border-width: 0;
        margin: 0 auto;
        width: 12px;
        height: 12px;
        display: inline-block;
    }

    label.estilo-radiobutton.hovered:before
    {
        background: #D9DEE3;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        border-width: 0;
        margin: 0 auto;
        width: 12px;
        height: 12px;
        display: inline-block;
    }

    .estilo-comments
    {
        border-top: 1px dotted #A1ADBA;
        margin: 0 auto;
        margin-top: 25px;
    }

    .estilo-comments h2
    {
        color: #23292F;
    }

    .estilo-comment-inner
    {
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        padding: 5px;
        margin: 0 auto;
        margin-left: 96px;
    }

    .estilo-comment-avatar
    {
        float: left;
        width: 80px;
        height: 80px;
        padding: 2px;
        background: #fff;
        border: 1px solid #E2E8EE;
    }

    .estilo-comment-avatar>img
    {
        margin: 0 !important;
        border: none !important;
    }

    .estilo-comment-content
    {
        padding: 10px 0;
        color: #303F50;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-comment
    {
        margin-top: 6px;
    }

    .estilo-comment:first-child
    {
        margin-top: 0;
    }

    .estilo-comment-header
    {
        color: #23292F;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        line-height: 100%;
    }

    .estilo-comment-header a, .estilo-comment-header a:link, .estilo-comment-header a:visited, .estilo-comment-header a.visited, .estilo-comment-header a:hover, .estilo-comment-header a.hovered
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        line-height: 100%;
    }

    .estilo-comment-header a, .estilo-comment-header a:link
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: bold;
        font-style: normal;
        color: #758799;
    }

    .estilo-comment-header a:visited, .estilo-comment-header a.visited
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #758799;
    }

    .estilo-comment-header a:hover, .estilo-comment-header a.hovered
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #758799;
    }

    .estilo-comment-content a, .estilo-comment-content a:link, .estilo-comment-content a:visited, .estilo-comment-content a.visited, .estilo-comment-content a:hover, .estilo-comment-content a.hovered
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-comment-content a, .estilo-comment-content a:link
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #CD4704;
    }

    .estilo-comment-content a:visited, .estilo-comment-content a.visited
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #3F5369;
    }

    .estilo-comment-content a:hover, .estilo-comment-content a.hovered
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #CD4704;
    }

    .estilo-pager
    {
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        padding: 6px;
        margin: 2px;
    }

    .estilo-pager>*:last-child
    {
        margin-right: 0 !important;
    }

    .estilo-pager>span
    {
        cursor: default;
    }

    .estilo-pager>*
    {
        background: #F5F5F5;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        padding: 10px;
        margin: 0 4px 0 auto;
        line-height: normal;
        position: relative;
        display: inline-block;
        margin-left: 0;
    }

    .estilo-pager a:link, .estilo-pager a:visited, .estilo-pager .active
    {
        line-height: normal;
        font-size: 14px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: none;
        color: #1BA1DA;
    }

    .estilo-pager .active
    {
        background: #A8CF45;
        padding: 10px;
        margin: 0 4px 0 auto;
        color: #075F12;
    }

    .estilo-pager .more
    {
        margin: 0 4px 0 auto;
    }

    .estilo-pager a.more:link, .estilo-pager a.more:visited
    {
        color: #1BA1DA;
    }

    .estilo-pager a:hover
    {
        background: #B9C2CB;
        padding: 10px;
        margin: 0 4px 0 auto;
    }

    .estilo-pager  a:hover, .estilo-pager  a.more:hover
    {
        text-decoration: none;
        color: #21262C;
    }

    .estilo-pager>*:after
    {
        margin: 0 0 0 auto;
        display: inline-block;
        position: absolute;
        content: ' ';
        top: 0;
        width: 0;
        height: 100%;
        right: 0;
        text-decoration: none;
    }

    .estilo-pager>*:last-child:after
    {
        display: none;
    }

    .estilo-commentsform
    {
        background: #E2E8EE;
        background: transparent;
        padding: 10px;
        margin: 0 auto;
        margin-top: 25px;
        color: #23292F;
    }

    .estilo-commentsform h2
    {
        padding-bottom: 10px;
        margin: 0;
        color: #23292F;
    }

    .estilo-commentsform label
    {
        display: inline-block;
        line-height: 25px;
    }

    .estilo-commentsform input:not([type=submit]), .estilo-commentsform textarea
    {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        width: 100%;
        max-width: 100%;
    }

    .estilo-commentsform .form-submit
    {
        margin-top: 10px;
    }

    .estilo-post
    {
        padding: 5px;
        margin: 20px;
    }

    a img
    {
        border: 0;
    }

    .estilo-article img, img.estilo-article, .estilo-block img, .estilo-footer img
    {
        margin: 7px 7px 7px 7px;
    }

    .estilo-metadata-icons img
    {
        border: none;
        vertical-align: middle;
        margin: 2px;
    }

    .estilo-article table, table.estilo-article
    {
        border-collapse: collapse;
        margin: 1px;
    }

    .estilo-post .estilo-content-layout-br
    {
        height: 0;
    }

    .estilo-article th, .estilo-article td
    {
        padding: 2px;
        border: solid 1px #B9C2CB;
        vertical-align: top;
        text-align: left;
    }

    .estilo-article th
    {
        text-align: center;
        vertical-align: middle;
        padding: 7px;
    }

    pre
    {
        overflow: auto;
        padding: 0.1em;
    }

    .preview-cms-logo
    {
        border: 0;
        margin: 1em 1em 0 0;
        float: left;
    }

    .image-caption-wrapper
    {
        padding: 7px 7px 7px 7px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    .image-caption-wrapper img
    {
        margin: 0 !important;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    .image-caption-wrapper div.estilo-collage
    {
        margin: 0 !important;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    .image-caption-wrapper p
    {
        font-size: 80%;
        text-align: right;
        margin: 0;
    }

    .estilo-postheader
    {
        color: #6FB154;
        margin: 5px 10px;
        font-size: 28px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
    }

    .estilo-postheader a, .estilo-postheader a:link, .estilo-postheader a:visited, .estilo-postheader a.visited, .estilo-postheader a:hover, .estilo-postheader a.hovered
    {
        font-size: 28px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
    }

    .estilo-postheader a, .estilo-postheader a:link
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: none;
        text-align: left;
        color: #6FB154;
    }

    .estilo-postheader a:visited, .estilo-postheader a.visited
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: none;
        text-align: left;
        color: #6FB154;
    }

    .estilo-postheader a:hover, .estilo-postheader a.hovered
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: none;
        text-align: left;
        color: #6FB154;
    }

    .estilo-postheadericons, .estilo-postheadericons a, .estilo-postheadericons a:link, .estilo-postheadericons a:visited, .estilo-postheadericons a:hover
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #4E6883;
    }

    .estilo-postheadericons
    {
        padding: 1px;
        margin: 0 0 0 10px;
    }

    .estilo-postheadericons a, .estilo-postheadericons a:link
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: none;
        color: #FB722D;
    }

    .estilo-postheadericons a:visited, .estilo-postheadericons a.visited
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        text-decoration: none;
        color: #587493;
    }

    .estilo-postheadericons a:hover, .estilo-postheadericons a.hover
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: normal;
        text-decoration: underline;
        color: #E65005;
    }

    .estilo-postdateicon:before
    {
        content: url('../images/postdateicon.png');
        margin-right: 6px;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
        bottom: auto;
    }

    .opera .estilo-postdateicon:before
    {
        bottom: 0;
    }

    .estilo-postauthoricon:before
    {
        content: url('../images/postauthoricon.png');
        margin-right: 6px;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
        bottom: auto;
    }

    .opera .estilo-postauthoricon:before
    {
        bottom: 0;
    }

    .estilo-postpdficon:before
    {
        content: url('../images/system/pdf_button.png');
        margin-right: 6px;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
        bottom: auto;
    }

    .opera .estilo-postpdficon:before
    {
        bottom: 0;
    }

    .estilo-postprinticon:before
    {
        content: url('../images/system/printButton.png');
        margin-right: 6px;
        bottom: 2px;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
    }

    .opera .estilo-postprinticon:before
    {
        bottom: 0;
    }

    .estilo-postemailicon:before
    {
        content: url('../images/system/emailButton.png');
        margin-right: 6px;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
        bottom: auto;
    }

    .opera .estilo-postemailicon:before
    {
        bottom: 0;
    }

    .estilo-postediticon:before
    {
        content: url('../images/system/edit.png');
        margin-right: 6px;
        bottom: 2px;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
    }

    .opera .estilo-postediticon:before
    {
        bottom: 0;
    }

    .estilo-postcontent ul>li:before, .estilo-post ul>li:before, .estilo-textblock ul>li:before
    {
        content: url('../images/postbullets.png');
        margin-right: 10px;
        bottom: 2px;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
    }

    .opera .estilo-postcontent ul>li:before, .opera   .estilo-post ul>li:before, .opera   .estilo-textblock ul>li:before
    {
        bottom: 0;
    }

    .estilo-postcontent li, .estilo-post li, .estilo-textblock li
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #303F50;
        margin: 3px 0 0 11px;
    }

    .estilo-postcontent ul>li, .estilo-post ul>li, .estilo-textblock ul>li, .estilo-postcontent ol, .estilo-post ol, .estilo-textblock ol
    {
        padding: 0;
    }

    .estilo-postcontent ul>li, .estilo-post ul>li, .estilo-textblock ul>li
    {
        padding-left: 17px;
    }

    .estilo-postcontent ul>li:before, .estilo-post ul>li:before, .estilo-textblock ul>li:before
    {
        margin-left: -17px;
    }

    .estilo-postcontent ol, .estilo-post ol, .estilo-textblock ol, .estilo-postcontent ul, .estilo-post ul, .estilo-textblock ul
    {
        margin: 1em 0 1em 11px;
    }

    .estilo-postcontent li ol, .estilo-post li ol, .estilo-textblock li ol, .estilo-postcontent li ul, .estilo-post li ul, .estilo-textblock li ul
    {
        margin: 0.5em 0 0.5em 11px;
    }

    .estilo-postcontent li, .estilo-post li, .estilo-textblock li
    {
        margin: 3px 0 0 0;
    }

    .estilo-postcontent ol>li, .estilo-post ol>li, .estilo-textblock ol>li
    {
        overflow: visible;
    }

    .estilo-postcontent ul>li, .estilo-post ul>li, .estilo-textblock ul>li
    {
        overflow-x: visible;
        overflow-y: hidden;
    }

    blockquote
    {
        background: #EFF2F5 url('../images/postquote.png') no-repeat scroll;
        padding: 10px 10px 10px 47px;
        margin: 10px 0 0 25px;
        color: #0D1216;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: italic;
        text-align: left;
        overflow: auto;
        clear: both;
    }

    blockquote a, .estilo-postcontent blockquote a, .estilo-blockcontent blockquote a, .estilo-footer blockquote a, blockquote a:link, .estilo-postcontent blockquote a:link, .estilo-blockcontent blockquote a:link, .estilo-footer blockquote a:link, blockquote a:visited, .estilo-postcontent blockquote a:visited, .estilo-blockcontent blockquote a:visited, .estilo-footer blockquote a:visited, blockquote a:hover, .estilo-postcontent blockquote a:hover, .estilo-blockcontent blockquote a:hover, .estilo-footer blockquote a:hover
    {
        color: #0D1216;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        font-weight: normal;
        font-style: italic;
        text-align: left;
    }

    blockquote p, .estilo-postcontent blockquote p, .estilo-blockcontent blockquote p, .estilo-footer blockquote p
    {
        margin: 0;
        margin: 5px 0;
    }

    .Sorter img
    {
        border: 0;
        vertical-align: middle;
        padding: 0;
        margin: 0;
        position: static;
        z-index: 1;
        width: 12px;
        height: 6px;
    }

    .Sorter a
    {
        position: relative;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #647587;
    }

    .Sorter a:link
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #647587;
    }

    .Sorter a:visited, .Sorter a.visited
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #647587;
    }

    .Sorter a:hover, .Sorter a.hover
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #4C5967;
    }

    .Sorter
    {
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #364049;
    }

    .Navigator .estilo-ccs-navigator img, .Navigator img
    {
        border: 0;
        margin: 0;
        vertical-align: middle;
    }

    tr.Navigator td, td span.Navigator
    {
        text-align: center;
        vertical-align: middle;
    }

    .estilo-postdatepickericon:before
    {
        content: url('../images/postdatepickericon.png');
        margin-right: 6px;
        bottom: 0;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
    }

    .opera .estilo-postdatepickericon:before
    {
        bottom: 0;
    }

    .estilo-postdatepickerlefticon:before
    {
        content: url('../images/postdatepickerlefticon.png');
        margin-right: 6px;
        bottom: 0;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
    }

    .opera .estilo-postdatepickerlefticon:before
    {
        bottom: 0;
    }

    .estilo-postdatepickerrighticon:before
    {
        content: url('../images/postdatepickerrighticon.png');
        margin-right: 6px;
        bottom: 0;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
    }

    .opera .estilo-postdatepickerrighticon:before
    {
        bottom: 0;
    }

    .estilo-footer
    {
        background: #FFFFFF url('../images/footer.png') scroll;
        margin: 0 auto;
        position: relative;
        color: #455B73;
        font-size: 13px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        line-height: 175%;
        text-align: center;
        padding: 0;
    }

    .estilo-footer a, .estilo-footer a:link, .estilo-footer a:visited, .estilo-footer a:hover, .estilo-footer td, .estilo-footer th, .estilo-footer caption
    {
        color: #455B73;
        font-size: 13px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        line-height: 175%;
    }

    .estilo-footer p
    {
        padding: 0;
        text-align: center;
    }

    .estilo-footer a, .estilo-footer a:link
    {
        color: #4D6580;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: none;
    }

    .estilo-footer a:visited
    {
        color: #3B4E63;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: none;
    }

    .estilo-footer a:hover
    {
        color: #C84504;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        text-decoration: underline;
    }

    .estilo-footer h1
    {
        color: #8A99A8;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-footer h2
    {
        color: #A1ADBA;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-footer h3
    {
        color: #FA681E;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-footer h4
    {
        color: #9CAFC4;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-footer h5
    {
        color: #9CAFC4;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-footer h6
    {
        color: #9CAFC4;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
    }

    .estilo-footer img
    {
        border: none;
        margin: 0;
    }

    .estilo-footer-inner
    {
        margin: 0 auto;
        min-width: 700px;
        max-width: 1728px;
        width: 95%;
        padding: 20px;
        padding-right: 20px;
        padding-left: 20px;
    }

    .estilo-rss-tag-icon
    {
        background: url('../images/footerrssicon.png') no-repeat scroll;
        margin: 0 auto;
        min-height: 32px;
        min-width: 32px;
        display: inline-block;
        text-indent: 35px;
        background-position: left center;
        vertical-align: middle;
    }

    .estilo-rss-tag-icon:empty
    {
        vertical-align: middle;
    }

    .estilo-facebook-tag-icon
    {
        background: url('../images/footerfacebookicon.png') no-repeat scroll;
        margin: 0 auto;
        min-height: 32px;
        min-width: 32px;
        display: inline-block;
        text-indent: 35px;
        background-position: left center;
        vertical-align: middle;
    }

    .estilo-facebook-tag-icon:empty
    {
        vertical-align: middle;
    }

    .estilo-twitter-tag-icon
    {
        background: url('../images/footertwittericon.png') no-repeat scroll;
        margin: 0 auto;
        min-height: 32px;
        min-width: 32px;
        display: inline-block;
        text-indent: 35px;
        background-position: left center;
        vertical-align: middle;
    }

    .estilo-twitter-tag-icon:empty
    {
        vertical-align: middle;
    }

    .estilo-tumblr-tag-icon
    {
        background: url('../images/tumblricon.png') no-repeat scroll;
        margin: 0 auto;
        min-height: 32px;
        min-width: 32px;
        display: inline-block;
        text-indent: 35px;
        background-position: left center;
        vertical-align: middle;
    }

    .estilo-tumblr-tag-icon:empty
    {
        vertical-align: middle;
    }

    .estilo-pinterest-tag-icon
    {
        background: url('../images/pinteresticon.png') no-repeat scroll;
        margin: 0 auto;
        min-height: 32px;
        min-width: 32px;
        display: inline-block;
        text-indent: 35px;
        background-position: left center;
        vertical-align: middle;
    }

    .estilo-pinterest-tag-icon:empty
    {
        vertical-align: middle;
    }

    .estilo-vimeo-tag-icon
    {
        background: url('../images/vimeoicon.png') no-repeat scroll;
        margin: 0 auto;
        min-height: 32px;
        min-width: 32px;
        display: inline-block;
        text-indent: 35px;
        background-position: left center;
        vertical-align: middle;
    }

    .estilo-vimeo-tag-icon:empty
    {
        vertical-align: middle;
    }

    .estilo-youtube-tag-icon
    {
        background: url('../images/youtubeicon.png') no-repeat scroll;
        margin: 0 auto;
        min-height: 32px;
        min-width: 32px;
        display: inline-block;
        text-indent: 35px;
        background-position: left center;
        vertical-align: middle;
    }

    .estilo-youtube-tag-icon:empty
    {
        vertical-align: middle;
    }

    .estilo-linkedin-tag-icon
    {
        background: url('../images/linkedinicon.png') no-repeat scroll;
        margin: 0 auto;
        min-height: 32px;
        min-width: 32px;
        display: inline-block;
        text-indent: 35px;
        background-position: left center;
        vertical-align: middle;
    }

    .estilo-linkedin-tag-icon:empty
    {
        vertical-align: middle;
    }

    .estilo-footer ul>li:before
    {
        content: url('../images/footerbullets.png');
        margin-right: 6px;
        bottom: 2px;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 0;
        line-height: 0;
        margin-left: -13px;
    }

    .opera .estilo-footer ul>li:before
    {
        bottom: 0;
    }

    .estilo-footer li
    {
        font-size: 13px;
        font-family: Abel, Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
        color: #6E2602;
    }

    .estilo-footer ul>li, .estilo-footer ol
    {
        padding: 0;
    }

    .estilo-footer ul>li
    {
        padding-left: 13px;
    }

    .estilo-page-footer, .estilo-page-footer a, .estilo-page-footer a:link, .estilo-page-footer a:visited, .estilo-page-footer a:hover
    {
        font-family: Arial;
        font-size: 10px;
        letter-spacing: normal;
        word-spacing: normal;
        font-style: normal;
        font-weight: normal;
        text-decoration: underline;
        color: #4C5967;
    }

    .estilo-page-footer
    {
        position: relative;
        z-index: auto !important;
        padding: 1em;
        text-align: center !important;
        text-decoration: none;
        color: #324253;
    }

    .estilo-lightbox-wrapper
    {
        background: #333;
        background: rgba(0, 0, 0, .8);
        bottom: 0;
        left: 0;
        padding: 0 100px;
        position: fixed;
        right: 0;
        text-align: center;
        top: 0;
        z-index: 1000000;
    }

    .estilo-lightbox, .estilo-lightbox-wrapper .estilo-lightbox-image
    {
        cursor: pointer;
    }

    .estilo-lightbox-wrapper .estilo-lightbox-image
    {
        border: 6px solid #fff;
        border-radius: 3px;
        display: none;
        max-width: 100%;
        vertical-align: middle;
    }

    .estilo-lightbox-wrapper .estilo-lightbox-image.active
    {
        display: inline-block;
    }

    .estilo-lightbox-wrapper .lightbox-error
    {
        background: #fff;
        border: 1px solid #b4b4b4;
        border-radius: 10px;
        box-shadow: 0 2px 5px #333;
        height: 80px;
        opacity: .95;
        padding: 20px;
        position: fixed;
        width: 300px;
        z-index: 100;
    }

    .estilo-lightbox-wrapper .loading
    {
        background: #fff url('../images/preloader-01.gif') center center no-repeat;
        border: 1px solid #b4b4b4;
        border-radius: 10px;
        box-shadow: 0 2px 5px #333;
        height: 32px;
        opacity: .5;
        padding: 10px;
        position: fixed;
        width: 32px;
        z-index: 10100;
    }

    .estilo-lightbox-wrapper .arrow
    {
        cursor: pointer;
        height: 100px;
        opacity: .5;
        filter: alpha(opacity=50);
        position: fixed;
        width: 82px;
        z-index: 10003;
    }

    .estilo-lightbox-wrapper .arrow.left
    {
        left: 9px;
    }

    .estilo-lightbox-wrapper .arrow.right
    {
        right: 9px;
    }

    .estilo-lightbox-wrapper .arrow:hover
    {
        opacity: 1;
        filter: alpha(opacity=100);
    }

    .estilo-lightbox-wrapper .arrow.disabled
    {
        display: none;
    }

    .estilo-lightbox-wrapper .arrow-t, .estilo-lightbox-wrapper .arrow-b
    {
        background-color: #fff;
        border-radius: 3px;
        height: 6px;
        left: 26px;
        position: relative;
        width: 30px;
    }

    .estilo-lightbox-wrapper .arrow-t
    {
        top: 38px;
    }

    .estilo-lightbox-wrapper .arrow-b
    {
        top: 50px;
    }

    .estilo-lightbox-wrapper .close
    {
        cursor: pointer;
        height: 22px;
        opacity: .5;
        filter: alpha(opacity=50);
        position: fixed;
        right: 39px;
        top: 30px;
        width: 22px;
        z-index: 10003;
    }

    .estilo-lightbox-wrapper .close:hover
    {
        opacity: 1;
        filter: alpha(opacity=100);
    }

    .estilo-lightbox-wrapper .close .cw, .estilo-lightbox-wrapper .close .ccw
    {
        background-color: #fff;
        border-radius: 3px;
        height: 6px;
        position: absolute;
        left: -4px;
        top: 8px;
        width: 30px;
    }

    .estilo-lightbox-wrapper .cw
    {
        transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
        -o-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
    }

    .estilo-lightbox-wrapper .ccw
    {
        transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
        -o-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
    }

    .estilo-lightbox-wrapper .close-alt, .estilo-lightbox-wrapper .arrow-right-alt, .estilo-lightbox-wrapper .arrow-left-alt
    {
        color: #fff;
        display: none;
        font-size: 2.5em;
        line-height: 100%;
    }

    .ie8 .estilo-lightbox-wrapper .close-alt, .ie8 .estilo-lightbox-wrapper .arrow-right-alt, .ie8 .estilo-lightbox-wrapper .arrow-left-alt
    {
        display: block;
    }

    .ie8 .estilo-lightbox-wrapper .cw, .ie8 .estilo-lightbox-wrapper .ccw
    {
        display: none;
    }

    .estilo-content-layout .estilo-sidebar1
    {
        margin: 0 auto;
        width: 430px;
    }

    .estilo-content-layout .estilo-content
    {
        margin: 0 auto;
    }

    .estilo-content-layout .estilo-sidebar2
    {
        margin: 0 auto;
        width: 300px;
    }

    fieldset
    {
        border: none;
    }

    fieldset dl
    {
        display: block;
        margin: 0;
        padding: 0;
        background: none;
    }

    fieldset dt
    {
        display: block;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        width: 12em;
        height: 2em;
        margin: 0;
        padding: 0;
        float: left;
        clear: both;
        background: none;
        line-height: 2em;
        overflow: hidden;
    }

    fieldset dd
    {
        display: block;
        min-height: 2em;
        margin: 0 0 0 12em;
        padding: 0;
        background: none;
        line-height: 2em;
    }

    fieldset label
    {
        display: inline-block;
        width: 12em;
    }

    fieldset textarea
    {
        vertical-align: text-top;
    }

    .img-fulltext-left, .img-intro-left
    {
        float: left;
        display: block;
        border: none;
        padding: 0;
        margin: 0 0.3em 0.3em 0;
        margin-top: 0;
    }

    .img-fulltext-right, .img-intro-right
    {
        float: right;
        display: block;
        border: none;
        padding: 0;
        margin: 0 0 0.3em 0.3em;
        margin-top: 0;
    }

    ul.pagenav
    {
        clear: both;
        list-style: none;
        display: block;
        margin: 0;
        padding: 0;
    }

    ul.pagenav li, ul.pagenav li:before
    {
        display: block;
        background: none;
        margin: 0;
        padding: 0;
        width: 50%;
        text-align: center;
        content: normal;
    }

    ul.pagenav li.pagenav-prev
    {
        float: left;
    }

    ul.pagenav li.pagenav-next
    {
        margin: 0 0 0 50%;
    }

    div.item-page dl.tabs
    {
        display: block;
        margin: 0;
        padding: 0;
    }

    div.item-page dl.tabs:before
    {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        display: block;
        float: left;
        width: 1em;
        height: 2em;
        overflow: hidden;
    }

    div.item-page dl.tabs dt
    {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        display: block;
        float: left;
        height: 2em;
        overflow: hidden;
        border-left: solid 1px #B9C2CB;
        border-top: solid 1px #B9C2CB;
    }

    div.item-page dl.tabs dt h3
    {
        margin: 0;
        padding: 0 1em;
        line-height: 2em;
        font-size: 100%;
        overflow: hidden;
    }

    div.item-page dl.tabs dt h3 a
    {
        text-decoration: none;
    }

    div.item-page dl.tabs:after
    {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        display: block;
        border-left: solid 1px #B9C2CB;
        content: " ";
        overflow: hidden;
        height: 2em;
    }

    div.item-page div.current
    {
        clear: both;
        border: solid 1px #B9C2CB;
    }

    div.item-page div.current dd.tabs
    {
        margin: 0;
        padding: 0;
    }

    div.item-page .panel
    {
        border: solid 1px #B9C2CB;
        margin-top: -1px;
    }

    div.item-page .panel h3
    {
        margin: 0;
        padding: 0;
    }

    div.item-page .panel h3 a
    {
        display: block;
        padding: 6px;
        text-decoration: none;
    }

    div.item-page .panel h3.pane-toggler-down a
    {
        border-bottom: solid 1px #B9C2CB;
    }

    div.item-page .panel .pane-slider
    {
        margin: 0;
        padding: 0;
    }

    div.item-page div.pagination ul
    {
        clear: both;
        list-style: none;
        display: block;
        margin: 0;
        padding: 0;
    }

    div.item-page div.pagination li
    {
        display: block;
        width: 50%;
        margin: 0;
        padding: 0;
        text-align: center;
        float: left;
        white-space: nowrap;
    }

    div.item-page div.pagination:after
    {
        visibility: hidden;
        display: block;
        font-size: 0;
        content: " ";
        clear: both;
        height: 0;
    }

    .edit.item-page select + div
    {
        width: 100% !important;
    }

    .edit.item-page div > ul
    {
        border: 1px solid #B9C2CB;
    }

    .edit.item-page .search-field
    {
        overflow: visible;
    }

    .edit.item-page .search-field > input
    {
        width: 100% !important;
    }

    .edit.item-page ul > li:before
    {
        content: normal;
    }

    .edit.item-page fieldset
    {
        border: solid 1px #B9C2CB;
    }

    .edit.item-page fieldset legend
    {
        padding: 7px;
        font-weight: bold;
    }

    #editor-xtd-buttons
    {
        float: left;
        padding: 0;
    }

    .toggle-editor
    {
        float: right;
    }

    #searchForm .phrases-box
    {
        display: block;
        float: left;
    }

    #searchForm .ordering-box
    {
        text-align: right;
    }

    #searchForm .phrases-box label, #searchForm .ordering-box label, #searchForm .only label
    {
        display: inline-block;
        width: auto;
        height: 2em;
        margin: 0;
        padding: 0 0.3em;
    }

    #mod-finder-searchform label
    {
        display: block;
    }

    #mod-finder-searchform input.inputbox
    {
        width: 100%;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        max-width: 300px;
    }

    #login-form fieldset label
    {
        width: 100%;
    }

    #login-form #form-login-username label, #login-form #form-login-password label
    {
        display: block;
    }

    #login-form #form-login-username input, #login-form #form-login-password input
    {
        width: 100%;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        max-width: 300px;
    }

    .breadcrumbs img
    {
        margin: 0;
        padding: 0;
        border: none;
        outline: none;
    }

    dl.stats-module
    {
        padding: 0.3em 0 0.3em 0.3em;
        margin: 0;
    }

    dl.stats-module dt
    {
        float: left;
        display: block;
        line-height: 1.5em;
        min-height: 1.5em;
        width: 10em;
        padding: 0.3em 0.3em 0 0;
        margin: 0;
        font-weight: bold;
    }

    dl.stats-module dd
    {
        display: block;
        line-height: 1.5em;
        min-height: 1.5em;
        margin: 0 0 0 10em;
    }

    div.mod-languages ul
    {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    div.mod-languages li
    {
        background: none;
        margin: 0 0.3em;
        padding: 0;
    }

    div.mod-languages ul.lang-inline li
    {
        display: inline;
    }

    div.mod-languages ul.lang-block li
    {
        display: block;
    }

    div.mod-languages img
    {
        border: none;
        margin: 0;
        padding: 0;
    }

    div.clr
    {
        clear: both;
    }

    #system-message ul li
    {
        background-image: none;
    }

    ul.actions, ul.actions li, ul.actions li img
    {
        display: inline;
        margin: 0;
        padding: 0;
        border: none;
    }

    ul.actions li
    {
        background: none;
        list-style: none;
    }

    .items-row
    {
        display: table;
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    .items-row .item
    {
        display: table-cell;
        vertical-align: top;
    }

    .items-row .row-separator
    {
        display: none;
    }

    div.pagination p.counter
    {
        display: inline-block;
        margin: 0 0.3em 0 0;
        padding: 0;
        background: none;
    }

    div.pagination ul, div.pagination ul li, div.pagination ul > li:before
    {
        display: inline-block;
        list-style: none;
        margin: 0;
        padding: 0 0.3em;
        background: none;
        content: normal;
    }

    div.tip-wrap
    {
        background: #fff;
        border: 1px solid #aaa;
    }

    div.tip-wrap div.tip
    {
        padding: 0.3em;
    }

    div.tip-wrap div.tip-title
    {
        font-weight: bold;
    }

    table.category
    {
        width: 100%;
    }

    table.category thead th img
    {
        padding: 0 0 0 0.3em;
        margin: 0;
        border: none;
    }

    span.hasTip a img
    {
        padding: 0;
        margin: 0;
        border: none;
    }

    div.categories-list ul li span.item-title, div.cat-children ul li span.item-title
    {
        display: block;
        margin: 0 0 0.3em 0;
    }

    div.categories-list ul li div.category-desc, div.cat-children ul li div.category-desc
    {
        margin: 0 0 0.3em 0;
    }

    div.categories-list dl, div.cat-children dl
    {
        display: block;
        padding-left: 0;
        padding-right: 0;
        margin-left: 0;
        margin-right: 0;
        background: none;
    }

    div.categories-list dl dt, div.cat-children dl dt, div.categories-list dl dd, div.cat-children dl dd
    {
        display: inline-block;
        padding: 0;
        margin: 0;
        background: none;
    }

    div.img_caption p.img_caption
    {
        padding: 0.3em 0;
        margin: 0;
    }

    form .search label, form .finder label
    {
        display: none;
    }

    #search-searchword
    {
        margin-bottom: 5px;
    }

    .cols-2 .column-1, .cols-2 .column-2, .cols-3 .column-1, .cols-3 .column-2, .cols-3 .column-3
    {
        float: left;
        clear: right;
    }

    .cols-2 .column-1
    {
        width: 50%;
    }

    .cols-2 .column-2
    {
        width: 50%;
    }

    .cols-3 .column-1
    {
        width: 33%;
    }

    .cols-3 .column-2
    {
        width: 33%;
    }

    .cols-3 .column-3
    {
        width: 34%;
    }

    .row-separator
    {
        clear: both;
        float: none;
        font-size: 1px;
        display: block;
    }

    ul.categories-module li h1, ul.categories-module li h2, ul.categories-module li h3, ul.categories-module li h4, ul.categories-module li h5, ul.categories-module li h6
    {
        display: inline;
    }

    .only, .phrases
    {
        border: solid 1px #ccc;
        margin: 10px 0 0 0px;
        padding: 15px;
        line-height: 1.3em;
    }

    div.tags
    {
        display: inline;
    }

    .tag-category ul > li:before
    {
        content: normal;
    }

    ul.list-striped > li:before
    {
        content: normal;
    }

    .list-striped
    {
        border-top: 1px solid #ddd;
    }

    .list-striped li, .list-striped dd
    {
        border-bottom: 1px solid #ddd;
    }

    .accordion
    {
        margin-bottom: 18px;
    }

    .accordion-group
    {
        margin-bottom: 2px;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }

    .accordion-heading
    {
        border-bottom: 0;
    }

    .accordion-heading .accordion-toggle
    {
        display: block;
        padding: 8px 15px;
    }

    .accordion-toggle
    {
        cursor: pointer;
    }

    .accordion-inner
    {
        padding: 9px 15px;
        border-top: 1px solid #e5e5e5;
    }

    .accordion-body.in:hover
    {
        overflow: visible;
    }

    .element-invisible
    {
        position: absolute;
        padding: 0;
        margin: 0;
        border: 0;
        height: 1px;
        width: 1px;
        overflow: hidden;
    }

    /* Begin Additional CSS Styles */
    .separador-azul {
        height: 5px;
        width: 100%;
        background: #C7EBFA;
        border-radius: 3px;
        margin-bottom:30px;
    }

    .estilo-footer {
        background-repeat: no-repeat;
        background-position: bottom center;
        background-size: 95%;
        height: 145px;
    }

    .barra-accesos{
        background-repeat: no-repeat;
        height: 50px;
    }

    .acceso-correo,.acceso-redes,.acceso-buscar{
        float: left;
        padding-top:12px;
    }

    .acceso-correo{
        padding-left: 60px;
    }

    .acceso-redes{
        padding-left: 80px;
    }

    .acceso-buscar{
        padding-left: 40px;
    }

    .login-inicio{
        width:25rem;
        margin-left: auto;
        margin-right:auto;
    }

    /*** ESTILOS DISPLAY NEWS PORTADA ***/

    span.titulos-noticias {
        font-size:1.5rem;
        display: block;
        padding: 0 1rem;

    }

    span.titulos-noticias a {
        color: #75c34b;
        text-decoration: none;
    }

    span.titulos-noticias a:hover {
        color: #008000;
    }


    span.textos-noticias {
        font-size:1.1rem;
        display: block;
        padding: 0 1rem;
    }

    span.textos-noticias a {
        color: #696969;
        text-decoration: none;
    }

    span.textos-noticias a:hover {
        color: #808080;
    }

    .cols-3 .column-1, .cols-3 .column-2, .cols-3 .column-3 {

        height: 26rem;
        border: 1px solid #dcdcdc;
        width: 28%;
        margin: 1rem;
        height: 17rem;
    }

    span.readmore {
        font-size: 1.1rem;
        padding: 5px 0px 0px;
        display: block;
        margin: 1rem;
        text-align: center;
    }

    span.readmore:hover {
        font-size: 1.1rem;
        padding: 5px 0px 0px;
        display: block;
        margin: 1rem;
        text-align: center;
    }

    span.readmore a {
        color: white;
        text-decoration:none;
    }

    .item.column-1 img, .item.column-2 img, .item.column-3 img {
        max-width:100%;
    }

    span.create {
        padding: 0.6rem;
    }

    .consultaFactura{
        border-radius: 16px 16px 0px 0px;
    }
    .estilo-postcontent ul>li:before, .estilo-post ul>li:before{
        content: url(../images/vmenuactiveitemicon.png)!important;
    }
    .estilo-article th, .estilo-article td{
        border: none!important;
    }

    .container {
        margin-left: 0px;
    }
    /* End Additional CSS Styles */
</style>

@endpush
