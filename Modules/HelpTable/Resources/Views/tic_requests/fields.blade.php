@if(Auth::user()->hasRole('Soporte TIC') || Auth::user()->hasRole('Proveedor TIC') )
<div v-if="isUpdate" class="panel" data-sortable-id="ui-general-1">
	<!-- begin panel-heading -->
	<div class="panel-heading ui-sortable-handle">
		<h4 class="panel-title"><strong>Detalle de la solicitud: @{{ dataForm.id }}</strong></h4>
	</div>
	<!-- end panel-heading -->
	<!-- begin panel-body -->
	<div class="panel-body">
		<div class="row">

         <div class="col-md-12">
            <!-- Request Type Id Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('ht_tic_type_request_id', trans('Request Type').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  <span class="form-control-plaintext">@{{ dataForm.tic_type_request? dataForm.tic_type_request.name: '' }}</span>
               </div>
            </div>
         </div>

         <div class="col-md-12">
            <!--  Priority Request Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('priority_request', trans('Priority Request').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  <span class="form-control-plaintext">@{{ dataForm.priority_request_name }}</span>
               </div>
            </div>
         </div>

         <div class="col-md-12">
            <!-- Ht Tic Type Tic Categories Id Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('ht_tic_type_tic_categories_id', trans('Category').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  <span class="form-control-plaintext">@{{ dataForm.tic_type_tic_categories? dataForm.tic_type_tic_categories.name: '' }}</span>
               </div>
            </div>
         </div>


         <div class="col-md-12">
            <!-- Affair Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('affair', trans('Affair').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  <span class="form-control-plaintext">@{{ dataForm.affair }}</span>
               </div>
            </div>
         </div>

         <div class="col-md-12">
            <!-- Descripcion Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  <span class="form-control-plaintext">@{{ dataForm.description }}</span>
               </div>
            </div>
         </div>

         <div class="col-md-12">
            <!-- User Id Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('users_id', trans('Funcionario solicitante').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  <span class="form-control-plaintext">@{{ dataForm.users.name }}</span>
               </div>
            </div>
         </div>


         {{-- <div class="col-md-12">
            <!-- Nombre de usuario que solicita el requerimiento Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('username_requesting_requirement', trans('Nombre de usuario que solicita el requerimiento').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  <span class="form-control-plaintext">@{{ dataForm.username_requesting_requirement }}</span>
               </div>
            </div>
         </div>

         <div class="col-md-12">
            <!-- Ubicación Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('location', trans('Ubicación').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  <span class="form-control-plaintext">@{{ dataForm.location }}</span>
               </div>
            </div>
         </div> --}}
            
      </div>
   </div>
   <!-- end panel-body -->
</div>
@endif


<div class="panel" data-sortable-id="ui-general-1">
	<!-- begin panel-heading -->
	<div class="panel-heading ui-sortable-handle">
		<h4 class="panel-title"><strong>Datos de la solicitud: @{{ dataForm.id }}</strong></h4>
      
	</div>
	<!-- end panel-heading -->
	<!-- begin panel-body -->
	<div class="panel-body">
      @if(!Auth::user()->hasRole('Usuario TIC'))
      <div class="row">
         <div v-if="isUpdate || '{!! Auth::user()->hasRole('Administrador TIC') !!}'"  class="col-md-12">
            <!-- Ht Tic Request Status Id Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('ht_tic_request_status_id', trans('State').':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  <select-check
                        css-class="form-control"
                        name-field="ht_tic_request_status_id"
                        reduce-label="name"
                        reduce-key="id"
                        name-resource="get-tic-request-statuses"
                        :value="dataForm"
                        :is-required="true"
                        :enable-search="true">
                  </select-check>
               </div>
            </div>
         </div>

      </div>
      @endif
		<div  class="row" v-if="dataForm.ht_tic_request_status_id != 7 && dataForm.ht_tic_request_status_id != 8">


         @if(Auth::user()->hasRole('Administrador TIC'))
         <div class="col-md-12">
            <!-- Ht Tic Type Request Id Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('ht_tic_type_request_id', trans('Request Type').':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  <select-check
                        css-class="form-control"
                        name-field="ht_tic_type_request_id"
                        reduce-label="name"
                        reduce-key="id"
                        name-resource="get-tic-type-requests"
                        :value="dataForm"
                        :is-required="true"
                        :enable-search="true">
                  </select-check>
               </div>
            </div>
         </div>

         <div class="col-md-12">
            <!--  Priority Request Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('priority_request', trans('Priority Request').':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  <select-check
                     css-class="form-control"
                     name-field="priority_request"
                     reduce-label="name"
                     reduce-key="id"
                     name-resource="get-constants/priority_request"
                     :value="dataForm"
                     :is-required="true"
                     :enable-search="true">
                  </select-check>
               </div>
            </div>
         </div>

         <div class="col-md-12">
            <!-- Category Id Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('ht_tic_type_tic_categories_id', trans('Category').':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  <select-check
                        css-class="form-control"
                        name-field="ht_tic_type_tic_categories_id"
                        reduce-label="name"
                        reduce-key="id"
                        name-resource="get-categories-actives"
                        :value="dataForm"
                        :is-required="true"
                        :enable-search="true">
                  </select-check>
               </div>
            </div>
         </div>

         
         <div class="col-md-12" v-if="dataForm.ht_tic_type_tic_categories_id">
            <!-- Category Id Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('ht_tic_type_assets_id', trans('Type').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
             

               <select-check-depend 
                  css-class="form-control" 
                  name-field="ht_tic_type_assets_id" 
                  reduce-label="name" 
                  reduce-key="id" 
                  :name-resource="'get-tic-type-assets'"
                  :value="dataForm" 
                  :is-required="false" 
                  dependent-id="ht_tic_type_tic_categories_id" 
                  foreign-key="ht_tic_type_tic_categories_id">
                  </select-check-depend>
               </div>
            </div>
         </div>


       
         @endif

         <div v-if="!isUpdate || '{!! Auth::user()->hasRole('Administrador TIC') !!}'" class="col-md-12">
            <!-- Affair Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('affair', trans('Affair').':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
               
                  {!! Form::text('affair', null, ['class' => 'form-control', 'v-model' => 'dataForm.affair', 'required' => true]) !!}
                  {{-- Valida si el cliente es seven para poner esta ayuda --}}
                  @if(str_contains(strtolower(config('app.name')), 'seven'))
                  <small>
                     Copia el Ejemplo y modifícalo: <br>
                     <b>General - Mesa de ayuda - Agregar nuevos filtros</b><br>
                     -Tipo: General ó Cliente<br>
                     -Módulo/s: Todos ó Correspondencia, pqrs, etc<br>
                     -Resumen: Explica la situación en max 5 palabras<br>
                  </small>
                  @endif
               </div>
             
            </div>
         </div>

         <div v-if="'{!! Auth::user()->hasRole('Administrador TIC') !!}'" class="col-md-12">
            <!-- Affair Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('acceso_remoto','Acceso remoto:', ['class' => 'col-form-label col-md-3']) !!}
               <div class="switcher col-md-9">
                  <input type="checkbox" name="acceso_remoto" id="acceso_remoto"
                        v-model="dataForm.acceso_remoto">
                  <label for="acceso_remoto"></label>
                  <small>@lang('Seleccione si se necesita un acceso remoto')</small>
               </div>
            </div>
         </div>

         <div v-if="dataForm.acceso_remoto && dataForm.acceso_remoto !== false" class="col-md-12">
            <!-- Affair Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('codigo_conexion','Código de conexión:', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
               
                  {!! Form::text('codigo_conexion', null, ['class' => 'form-control', 'v-model' => 'dataForm.codigo_conexion']) !!}
                  {{-- Valida si el cliente es seven para poner esta ayuda --}}
                  <small>
                     Ingrese el código de conexión del equipo remoto
                  </small>
               </div>
             
            </div>
         </div>

         <div v-if="dataForm.acceso_remoto && dataForm.acceso_remoto !== false" class="col-md-12">
            <!-- Affair Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('clave_conexion', 'Clave de conexión:', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
               
                  {!! Form::text('clave_conexion', null, ['class' => 'form-control', 'v-model' => 'dataForm.clave_conexion']) !!}
                  {{-- Valida si el cliente es seven para poner esta ayuda --}}
                  <small>
                     Ingrese la clave de conexión del equipo remoto
                  </small>
               </div>
             
            </div>
         </div>
         
        

         <div v-if="!isUpdate || '{!! Auth::user()->hasRole('Administrador TIC') !!}'" class="col-md-12">
            <!-- Descripcion Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  {!! Form::textarea('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'required' => true]) !!}
                  {{-- Valida si el cliente es seven para poner esta ayuda --}}
                  @if(str_contains(strtolower(config('app.name')), 'seven'))
                  <small>
                     Copia el Ejemplo y modifícalo: <br>
                     1. Tipo: General ó Cliente<br>
                     2. Módulo/s: Todos ó Correspondencia, pqrs, etc<br>
                     3. Situación: Explica la situación<br>
                     4. Usuario: Todos ó Poner rol u usuario<br>
                     5. Evidencias: Adjuntar y explicar<br>
                  </small>
                  @endif
               </div>
            </div>
         </div>

        

      

         
         <!-- Valida que el usuario logueado no sea solo usuario tic -->
         @if(!Auth::user()->hasRole('Usuario TIC'))
         <div v-if="!isUpdate || '{!! Auth::user()->hasRole('Administrador TIC') !!}'"  class="col-md-12">
            <!-- User Id Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('users_id', trans('Funcionario solicitante').':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  <select-check
                        css-class="form-control"
                        name-field="users_id"
                        :reduce-label="['dependency', 'name']"
                        reduce-key="id"
                        name-resource="get-users-tic"
                        :value="dataForm"
                        :is-required="true"
                        :enable-search="true">
                  </select-check>
               </div>
            </div>
         </div>
         @endif

         {{-- <div class="col-md-12">
            <!-- Nombre de usuario que solicita el requerimiento Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('username_requesting_requirement', trans('Nombre de usuario que solicita el requerimiento').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  {!! Form::text('username_requesting_requirement', null, ['class' => 'form-control', 'v-model' => 'dataForm.username_requesting_requirement', 'maxlength' => 30]) !!}
               </div>
            </div>
         </div>

         <div class="col-md-12">
            <!-- Ubicación Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('location', trans('Ubicación').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  {!! Form::select('location', ['Piso 1' => 'Piso 1', 'Piso 2' => 'Piso 2', 'Piso 3' => 'Piso 3', 'Piso 4' => 'Piso 4', 'Piso 5' => 'Piso 5', 'Piso 6' => 'Piso 6', 'Piso 7' => 'Piso 7', 'Piso 8' => 'Piso 8', 'Piso 9' => 'Piso 9', 'Piso 10' => 'Piso 10', 'Piso 11' => 'Piso 11', 'Piso 12' => 'Piso 12', 'Piso 13' => 'Piso 13', 'Piso 14' => 'Piso 14', 'Piso 15' => 'Piso 15', 'Piso 16' => 'Piso 16', 'Piso 17' => 'Piso 17', 'Piso 18' => 'Piso 18', 'Piso 19' => 'Piso 19', 'Almacén' => 'Almacén', 'Gestión Documental' => 'Gestión Documental', 'Ingresos Públicos' => 'Ingresos Públicos', 'Tesorería' => 'Tesorería', 'Pasaportes' => 'Pasaportes', 'Centro de Convenciones' => 'Centro de Convenciones', 'Laboratorio Departamental' => 'Laboratorio Departamental', 'CRUE' => 'CRUE'], null, ['class' => 'form-control', 'v-model' => 'dataForm.location']) !!}
               </div>
            </div>
         </div> --}}

        
         {{-- @endif --}}

         @if(Auth::user()->hasRole('Administrador TIC'))
         <div class="col-md-12">
            <!-- Support type  Id Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('support_type', trans('Tipo de soporte').':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  <select-check
                        css-class="form-control"
                        name-field="support_type"
                        reduce-label="name"
                        reduce-key="id"
                        name-resource="get-constants/support_type_tic"
                        :value="dataForm"
                        :is-required="true"
                        :enable-search="true">
                  </select-check>
               </div>
            </div>
         </div>

         <div class="col-md-12" v-if="dataForm.support_type == 1">
            <!-- Funcionario Id Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('assigned_user_id', trans('Funcionario').':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  <select-check
                        css-class="form-control"
                        name-field="assigned_user_id"
                        reduce-label="name"
                        reduce-key="id"
                        name-resource="get-support-users-tic"
                        :value="dataForm"
                        :is-required="true"
                        :key="dataForm.support_type"
                        :enable-search="true"
                        >
                  </select-check>
               </div>
            </div>
         </div>

         <div class="col-md-12" v-if="dataForm.support_type == 2">
            <!-- Supplier Id Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('assigned_user_id', trans('Tic Providers').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  <select-check
                        css-class="form-control"
                        name-field="assigned_user_id"
                        reduce-label="name"
                        reduce-key="users_id"
                        name-resource="get-supplier-users-tic"
                        :value="dataForm"
                        :is-required="true"
                        :key="dataForm.support_type"
                        >
                  </select-check>
               </div>
            </div>
         </div>
         @endif
      </div>
      @if(!Auth::user()->hasRole('Usuario TIC'))
      <div class="row">
         <div class="col-md-12">
            <!-- Tracing Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('tracing', trans('Actividad de seguimiento').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  {{-- {!! Form::textarea('tracing', null, ['class' => 'form-control', 'v-model' => 'dataForm.tracing']) !!} --}}
                  <text-area-editor
                     :value="dataForm"
                     name-field="tracing"
                     :hide-modules="{
                        'bold': true, 
                        'image': true,
                        'code': true,
                        'link': true
                     }"
                     placeholder="Ingrese el seguimiento"
                     :numero1="1"
                  >
                  </text-area-editor>
               </div>
            </div>
            {{-- <example-component1 name-field="tracing" :value="dataForm"></example-component1> --}}
            {{-- <ckeditor value="Hello, World!"></ckeditor> --}}
         </div>
      </div>
      
      @endif
      

      {{-- <!-- Supplier Id Field -->
      <div class="form-group row m-b-15">
         {!! Form::label('assigned_user_id', trans('Archivos').':', ['class' => 'col-form-label col-md-3']) !!}
         <div class="col-md-9">
         
               <input-file :file-name-real="true":value="dataForm" name-field="url_documents" :max-files="30" :max-filesize="11"
               file-path="public/help_table/documents"
               message="Arrastre o seleccione los archivos"
               help-text="Agregue aquí máximo 10 archivos a esta solicitud. Peso máximo 5MB."
               :mostrar-eliminar-adjunto="true"
               :is-update="isUpdate"
               accepted-files=".jpeg,.jpg,.png,.pdf,.docx,.xls,.xlsx,.doc">
               </input-file>
         </div>
      </div> --}}
      @if(Auth::user()->hasRole('Administrador TIC') || Auth::user()->hasRole('Usuario TIC') ||Auth::user()->hasRole('Soporte TIC') )

         
         <div  class="form-group row m-b-15" v-if="!isUpdate" >
               {!! Form::label('ht_sedes_tic_request_id', trans('Sede del usuario') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  <select-check css-class="form-control" name-field="ht_sedes_tic_request_id" reduce-label="name"
                     reduce-key="id" name-resource="get-sedes-tics" :value="dataForm" :is-required="true" :enable-search="true">
                  </select-check>
               </div>
         </div>
         {{-- <div class="form-group row m-b-15" v-if="!isUpdate">
            {!! Form::label('ht_dependencias_tic_request_id', trans('Dirigir a la dependencia') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
               <select-check css-class="form-control" name-field="ht_dependencias_tic_request_id"
                  reduce-label="name" reduce-key="id"
                  :name-resource="'get-dependencias-tics/'+dataForm.ht_sedes_tic_request_id" :value="dataForm"
                  :key="dataForm.ht_sedes_tic_request_id" :enable-search="true">
               </select-check>
            </div>
         </div> --}}

            <div  class="form-group row m-b-15" v-if="isUpdate" >
               {!! Form::label('ht_sedes_tic_request_id', trans('Sede del usuario') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  <input class="form-control" type="text" :value="dataForm.sede_tic_request ? dataForm.sede_tic_request.name : 'Ninguna'" disabled>
               </div>

            </div>
            <div  class="form-group row m-b-15" v-if="isUpdate">
               {!! Form::label('ht_sedes_tic_request_id', trans('Dirigir a la dependencia') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  <input class="form-control" type="text" :value="dataForm.dependencia_tic_request ? dataForm.dependencia_tic_request.name : 'Ninguna'" disabled>
               </div>
            </div>        

          
      @endif
      

      <div class="row" v-if="dataForm.ht_tic_request_status_id == 7 || dataForm.ht_tic_request_status_id == 8">

         @if(Auth::user()->hasRole('Administrador TIC'))
         <div class="col-md-12">
            <!-- Descripcion Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  <span class="form-control-plaintext">@{{ dataForm.description }}</span>
               </div>
            </div>
         </div>

         <div class="col-md-12">
            <!-- Tracing Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('tracing', trans('Actividad de seguimiento').':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  <text-area-editor
                     :value="dataForm"
                     name-field="tracing"
                     :hide-modules="{
                        'bold': true, 
                        'image': true,
                        'code': true,
                        'link': true
                     }"
                     placeholder="Ingrese el seguimiento"
                  >
                  </text-area-editor>
               </div>
            </div>
         </div>
         {{-- @else
         <div class="col-md-12">
            <!-- Descripcion Field -->
            <div class="form-group row m-b-15">
               {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                     {!! Form::textarea('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'required' => true]) !!}
               </div>
            </div>
          </div> --}}
         @endif

      </div>
   </div>
   <!-- end panel-body -->
</div>

<!-- Opcion cuando la solicitud se ha atentido y esta pendiente por encuesta o esta cerrada -->
<div v-if="(dataForm.ht_tic_request_status_id == 4 || dataForm.ht_tic_request_status_id == 5 || dataForm.ht_tic_request_status_id == 8 ) && dataForm.tic_maintenances.length <= 0" class="col-md-6">
   <div class="form-group row m-b-15">
      {!! Form::label('generate_maintenance', trans('Generar mantenimiento').':', ['class' => 'col-form-label col-md-4']) !!}
      <!-- Sendemail switcher -->
      <div class="switcher col-md-6 m-t-5">
         <input type="checkbox" name="generate_maintenance" id="generate_maintenance" v-model="dataForm.generate_maintenance">
         <label for="generate_maintenance"></label>
      </div>
   </div>
</div>

<!-- Datos cuando una solicitud genero un mantenimiento de un activo tic -->
<div v-if="dataForm.generate_maintenance && (dataForm.ht_tic_request_status_id == 4 || dataForm.ht_tic_request_status_id == 5 || dataForm.ht_tic_request_status_id == 8 )">

   
      
   <div class="col-md-12">
      <div class="form-group row m-b-15">
         {!! Form::label('id_tower_inventory', 'Número de inventario:', ['class' => 'col-form-label col-md-3 required']) !!}
         <div class="col-md-9">
            <select-check
                  css-class="form-control"
                  name-field="id_tower_inventory"
                  reduce-label="tower_inventory_number"
                  reduce-key="id"
                  name-resource="get-equipment-resumes-all"
                  :value="dataForm"
                  :is-required="false"
                  name-field-object="tower_inventory"
                  :enable-search="true" 
                  >
            </select-check>
         </div>
      </div>
      <!-- Ht Tic Assets Id Id Field -->
      <div class="form-group row m-b-15">
         {!! Form::label('asset_type', 'Tipo de activo:', ['class' => 'col-form-label col-md-3 required']) !!}
         <div class="col-md-9">
           
            <input-check 
            prefix="otros"
            :disabled="true"
            name-field="asset_type"
            css-class="form-control" 
            :value="dataForm"
            :key="dataForm.id_tower_inventory"
            type-input="llenadoPorObjeto"
            :value-recibido="['tower_inventory','asset_type']"
            ></input-check>
           
           
            {{-- <select-check
                  css-class="form-control"
                  name-field="ht_tic_type_assets_id"
                  reduce-label="name"
                  reduce-key="id"
                  :name-resource="'get-tic-type-assets-by-category/'+dataForm.ht_tic_type_tic_categories_id"
                  :value="dataForm"
                  :is-required="false"
                  :key="dataForm.support_type"
                  >
            </select-check> --}}
         </div>
      </div>
{{--    
      <div class="form-group row m-b-15">
         {!! Form::label('ht_tic_assets_id', trans('active').':', ['class' => 'col-form-label col-md-3']) !!}
         <div class="col-md-9">
      
            <select-check-depend 
               ref-select-check="selectRefCategory" 
               css-class="form-control" 
               name-field="ht_tic_assets_id" 
               reduce-label="name" 
               reduce-key="id" 
               :name-resource="'get-tic-assets'"
               {{-- :name-resource="'get-tic-assets-by-category/'+dataForm.ht_tic_type_assets_id" 
               :value="dataForm" 
               :is-required="false" 
               dependent-id="ht_tic_type_assets_id" 
               foreign-key="ht_tic_type_assets_id">
               </select-check-depend>
         </div>
      </div> --}}

   </div>
   @include('help_table::tic_maintenances.fields')

   <div class="panel">
      <div class="panel-heading">
          <div class="panel-title"><strong>Lista de chequeo mantenimiento</strong></div>
      </div>
      <div class="panel-body">
          <!-- Has Internal And External Hardware Cleaning Field -->
          <div class="form-group row m-b-15">
              {!! Form::label(
                  'has_internal_and_external_hardware_cleaning',
                  trans('Has Internal And External Hardware Cleaning') . ':',
                  ['class' => 'col-form-label col-md-3 required'],
              ) !!}
              <div class="col-md-3">
                  <select class="form-control" name="has_internal_and_external_hardware_cleaning"
                      v-model="dataForm.has_internal_and_external_hardware_cleaning" required>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                  </select>
                  <div class="invalid-feedback" v-if="dataErrors.has_internal_and_external_hardware_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.has_internal_and_external_hardware_cleaning">
                          @{{ error }}</p>
                  </div>
              </div>

              <!-- Observation Internal And External Hardware Cleaning Field -->
              {!! Form::label('observation_internal_and_external_hardware_cleaning', trans('Observation') . ':', [
                  'class' => 'col-form-label col-md-2',
              ]) !!}
              <div class="col-md-3">
                  {!! Form::textarea('observation_internal_and_external_hardware_cleaning', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_internal_and_external_hardware_cleaning }",
                      'v-model' => 'dataForm.observation_internal_and_external_hardware_cleaning',
                      'rows' => '3'
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.observation_internal_and_external_hardware_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.observation_internal_and_external_hardware_cleaning">
                          @{{ error }}</p>
                  </div>
              </div>
          </div>

          <!-- Has Ram Cleaning Field -->
          <div class="form-group row m-b-15">
              {!! Form::label('has_ram_cleaning', trans('Has Ram Cleaning') . ':', [
                  'class' => 'col-form-label col-md-3 required',
              ]) !!}
              <div class="col-md-3">
                  <select class="form-control" name="has_ram_cleaning" v-model="dataForm.has_ram_cleaning" required>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                  </select>
              </div>

              <!-- Observation Ram Cleaning Field -->
              {!! Form::label('observation_ram_cleaning', trans('Observation') . ':', [
                  'class' => 'col-form-label col-md-2',
              ]) !!}
              <div class="col-md-3">
                  {!! Form::textarea('observation_ram_cleaning', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_ram_cleaning }",
                      'v-model' => 'dataForm.observation_ram_cleaning',
                      'rows' => '3'
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.observation_ram_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.observation_ram_cleaning">@{{ error }}</p>
                  </div>
              </div>
          </div>

          <!-- Has Board Memory Cleaning Field -->
          <div class="form-group row m-b-15">
              {!! Form::label('has_board_memory_cleaning', trans('Has Board Memory Cleaning') . ':', [
                  'class' => 'col-form-label col-md-3 required',
              ]) !!}
              <div class="col-md-3">
                  <select class="form-control" name="has_board_memory_cleaning" v-model="dataForm.has_board_memory_cleaning"
                      required>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                  </select>
              </div>

              <!-- Observation Board Memory Cleaning Field -->
              {!! Form::label('observation_board_memory_cleaning', trans('Observation') . ':', [
                  'class' => 'col-form-label col-md-2',
              ]) !!}
              <div class="col-md-3">
                  {!! Form::textarea('observation_board_memory_cleaning', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_board_memory_cleaning }",
                      'v-model' => 'dataForm.observation_board_memory_cleaning',
                      'rows' => '3'
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.observation_board_memory_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.observation_board_memory_cleaning">
                          @{{ error }}
                      </p>
                  </div>
              </div>
          </div>

          <!-- Has Power Supply Cleaning Field -->
          <div class="form-group row m-b-15">
              {!! Form::label('has_power_supply_cleaning', trans('Has Power Supply Cleaning') . ':', [
                  'class' => 'col-form-label col-md-3 required',
              ]) !!}
              <div class="col-md-3">
                  <select class="form-control" name="has_power_supply_cleaning" v-model="dataForm.has_power_supply_cleaning"
                      required>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                  </select>
              </div>
              <!-- Observation Power Supply Cleaning Field -->
              {!! Form::label('observation_power_supply_cleaning', trans('Observation') . ':', [
                  'class' => 'col-form-label col-md-2',
              ]) !!}
              <div class="col-md-3">
                  {!! Form::textarea('observation_power_supply_cleaning', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_power_supply_cleaning }",
                      'v-model' => 'dataForm.observation_power_supply_cleaning',
                      'rows' => '3'
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.observation_power_supply_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.observation_power_supply_cleaning">
                          @{{ error }}
                      </p>
                  </div>
              </div>
          </div>

          <!-- Has Dvd Drive Cleaning Field -->
          <div class="form-group row m-b-15">
              {!! Form::label('has_dvd_drive_cleaning', trans('Has Dvd Drive Cleaning') . ':', [
                  'class' => 'col-form-label col-md-3 required',
              ]) !!}
              <div class="col-md-3">
                  <select class="form-control" name="has_dvd_drive_cleaning" v-model="dataForm.has_dvd_drive_cleaning" required>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                  </select>
                  <div class="invalid-feedback" v-if="dataErrors.has_dvd_drive_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.has_dvd_drive_cleaning">@{{ error }}</p>
                  </div>
              </div>

              <!-- Observation Dvd Drive Cleaning Field -->
              {!! Form::label('observation_dvd_drive_cleaning', trans('Observation') . ':', [
                  'class' => 'col-form-label col-md-2',
              ]) !!}
              <div class="col-md-3">
                  {!! Form::textarea('observation_dvd_drive_cleaning', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_dvd_drive_cleaning }",
                      'v-model' => 'dataForm.observation_dvd_drive_cleaning',
                      'rows' => '3'
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.observation_dvd_drive_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.observation_dvd_drive_cleaning">
                          @{{ error }}</p>
                  </div>
              </div>
          </div>

          <!-- Has Monitor Cleaning Field -->
          <div class="form-group row m-b-15">
              {!! Form::label('has_monitor_cleaning', trans('Has Monitor Cleaning') . ':', [
                  'class' => 'col-form-label col-md-3 required',
              ]) !!}
              <div class="col-md-3">
                  <select class="form-control" name="has_monitor_cleaning" v-model="dataForm.has_monitor_cleaning" required>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                  </select>
                  <div class="invalid-feedback" v-if="dataErrors.has_monitor_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.has_monitor_cleaning">@{{ error }}</p>
                  </div>
              </div>

              <!-- Observation Monitor Cleaning Field -->
              {!! Form::label('observation_monitor_cleaning', trans('Observation') . ':', [
                  'class' => 'col-form-label col-md-2',
              ]) !!}
              <div class="col-md-3">
                  {!! Form::textarea('observation_monitor_cleaning', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_monitor_cleaning }",
                      'v-model' => 'dataForm.observation_monitor_cleaning',
                      'rows' => '3'
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.observation_monitor_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.observation_monitor_cleaning">@{{ error }}
                      </p>
                  </div>
              </div>
          </div>

          <!-- Has Keyboard Cleaning Field -->
          <div class="form-group row m-b-15">
              {!! Form::label('has_keyboard_cleaning', trans('Has Keyboard Cleaning') . ':', [
                  'class' => 'col-form-label col-md-3 required',
              ]) !!}
              <div class="col-md-3">
                  <select class="form-control" name="has_keyboard_cleaning" v-model="dataForm.has_keyboard_cleaning" required>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                  </select>
                  <div class="invalid-feedback" v-if="dataErrors.has_keyboard_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.has_keyboard_cleaning">@{{ error }}</p>
                  </div>
              </div>

              <!-- Observation Keyboard Cleaning Field -->
              {!! Form::label('observation_keyboard_cleaning', trans('Observation') . ':', [
                  'class' => 'col-form-label col-md-2',
              ]) !!}
              <div class="col-md-3">
                  {!! Form::textarea('observation_keyboard_cleaning', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_keyboard_cleaning }",
                      'v-model' => 'dataForm.observation_keyboard_cleaning',
                      'rows' => '3'
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.observation_keyboard_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.observation_keyboard_cleaning">
                          @{{ error }}</p>
                  </div>
              </div>
          </div>

          <!-- Has Mouse Cleaning Field -->
          <div class="form-group row m-b-15">
              {!! Form::label('has_mouse_cleaning', trans('Has Mouse Cleaning') . ':', [
                  'class' => 'col-form-label col-md-3 required',
              ]) !!}
              <div class="col-md-3">
                  <select class="form-control" name="has_mouse_cleaning" v-model="dataForm.has_mouse_cleaning" required>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                  </select>
                  <div class="invalid-feedback" v-if="dataErrors.has_mouse_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.has_mouse_cleaning">@{{ error }}</p>
                  </div>
              </div>

              <!-- Observation Mouse Cleaning Field -->
              {!! Form::label('observation_mouse_cleaning', trans('Observation') . ':', [
                  'class' => 'col-form-label col-md-2',
              ]) !!}
              <div class="col-md-3">
                  {!! Form::textarea('observation_mouse_cleaning', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_mouse_cleaning }",
                      'v-model' => 'dataForm.observation_mouse_cleaning',
                      'rows' => '3'
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.observation_mouse_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.observation_mouse_cleaning">@{{ error }}
                      </p>
                  </div>
              </div>
          </div>

          <!-- Has Thermal Paste Change Field -->
          <div class="form-group row m-b-15">
              {!! Form::label('has_thermal_paste_change', trans('Has Thermal Paste Change') . ':', [
                  'class' => 'col-form-label col-md-3 required',
              ]) !!}
              <div class="col-md-3">
                  <select class="form-control" name="has_thermal_paste_change" v-model="dataForm.has_thermal_paste_change" required>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                  </select>
                  <div class="invalid-feedback" v-if="dataErrors.has_thermal_paste_change">
                      <p class="m-b-0" v-for="error in dataErrors.has_thermal_paste_change">@{{ error }}</p>
                  </div>
              </div>

              <!-- Observation Thermal Paste Change Field -->
              {!! Form::label('observation_thermal_paste_change', trans('Observation') . ':', [
                  'class' => 'col-form-label col-md-2',
              ]) !!}
              <div class="col-md-3">
                  {!! Form::textarea('observation_thermal_paste_change', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_thermal_paste_change }",
                      'v-model' => 'dataForm.observation_thermal_paste_change',
                      'rows' => '3'
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.observation_thermal_paste_change">
                      <p class="m-b-0" v-for="error in dataErrors.observation_thermal_paste_change">
                          @{{ error }}</p>
                  </div>
              </div>
          </div>

          <!-- Has Heatsink Cleaning Field -->
          <div class="form-group row m-b-15">
              {!! Form::label('has_heatsink_cleaning', trans('Has Heatsink Cleaning') . ':', [
                  'class' => 'col-form-label col-md-3 required',
              ]) !!}
              <div class="col-md-3">
                  <select class="form-control" name="has_heatsink_cleaning" v-model="dataForm.has_heatsink_cleaning" required>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                  </select>
                  <div class="invalid-feedback" v-if="dataErrors.has_heatsink_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.has_heatsink_cleaning">@{{ error }}</p>
                  </div>
              </div>

              <!-- Observation Heatsink Cleaning Field -->
              {!! Form::label('observation_heatsink_cleaning', trans('Observation') . ':', [
                  'class' => 'col-form-label col-md-2',
              ]) !!}
              <div class="col-md-3">
                  {!! Form::textarea('observation_heatsink_cleaning', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_heatsink_cleaning }",
                      'v-model' => 'dataForm.observation_heatsink_cleaning',
                      'rows' => '3'
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.observation_heatsink_cleaning">
                      <p class="m-b-0" v-for="error in dataErrors.observation_heatsink_cleaning">
                          @{{ error }}</p>
                  </div>
              </div>
          </div>

          <!-- Technical Report Field -->
          <div class="form-group row m-b-15">
              {!! Form::label('technical_report', trans('Technical Report') . ':', [
                  'class' => 'col-form-label col-md-3',
              ]) !!}
              <div class="col-md-3">
                  {!! Form::textarea('technical_report', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.technical_report }",
                      'v-model' => 'dataForm.technical_report',
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.technical_report">
                      <p class="m-b-0" v-for="error in dataErrors.technical_report">@{{ error }}</p>
                  </div>
              </div>

              <!-- Observation Field -->
              {!! Form::label('observation', trans('Observation') . ':', ['class' => 'col-form-label col-md-2']) !!}
              <div class="col-md-3">
                  {!! Form::textarea('observation', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }",
                      'v-model' => 'dataForm.observation',
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.observation">
                      <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
                  </div>
              </div>
          </div>

      </div>
  </div>
</div>


