<!-- Ht Tic Type Request Id Field -->
<div class="form-group row m-b-15">
	{!! Form::label('ht_tic_type_request_id', trans('Type Knowledge').':', ['class' => 'col-form-label col-md-3 required']) !!}
	<div class="col-md-9">
		<select-check
			css-class="form-control"
			name-field="ht_tic_type_request_id"
			reduce-label="name"
			reduce-key="id"
			name-resource="get-tic-type-requests"
			:value="dataForm"
			:is-required="true">
		</select-check>
		<small>@lang('Select the') el @{{ `@lang('Type Knowledge')` | lowercase }}</small>
		<div class="invalid-feedback" v-if="dataErrors.ht_tic_type_request_id">
			<p class="m-b-0" v-for="error in dataErrors.ht_tic_type_request_id">@{{ error }}</p>
		</div>
	</div>
</div>

<!-- Affair Field -->
<div class="form-group row m-b-15">
	{!! Form::label('affair', trans('Affair').':', ['class' => 'col-form-label col-md-3 required']) !!}
	<div class="col-md-9">
		{!! Form::text('affair', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.affair }", 'v-model' => 'dataForm.affair', 'required' => true]) !!}
		<small>@lang('Enter the') el @{{ `@lang('Affair')` | lowercase }}</small>
		<div class="invalid-feedback" v-if="dataErrors.affair">
			<p class="m-b-0" v-for="error in dataErrors.affair">@{{ error }}</p>
		</div>
	</div>
</div>

<!-- Knowledge Description Field -->
<div class="form-group row m-b-15">
	{!! Form::label('knowledge_description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
	<div class="col-md-9">
		{!! Form::textarea('knowledge_description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.knowledge_description }", 'v-model' => 'dataForm.knowledge_description', 'required' => true]) !!}
		<small>@lang('Enter the') la @{{ `@lang('Description')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.knowledge_description">
            <p class="m-b-0" v-for="error in dataErrors.knowledge_description">@{{ error }}</p>
        </div>
	</div>
</div>

<!--  Knowledge State Field -->
<div class="form-group row m-b-15">
	{!! Form::label('knowledge_state', trans('State').':', ['class' => 'col-form-label col-md-3 required']) !!}
	<div class="col-md-9">
		<select-check
			css-class="form-control"
			name-field="knowledge_state"
			reduce-label="name"
			reduce-key="id"
			name-resource="get-constants/state_knowledge_tic"
			:value="dataForm"
			:is-required="true">
		</select-check>
		<small>@lang('Select the') el @{{ `@lang('State')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.knowledge_state">
            <p class="m-b-0" v-for="error in dataErrors.knowledge_state">@{{ error }}</p>
        </div>
	</div>
</div>


<div class="form-group row m-b-15">
	{!! Form::label('attached', trans('Adjunto').':', ['class' => 'col-form-label col-md-3' ] ) !!}
	<div class="col-md-9">
		<input-file
			:value="dataForm"
			name-field="attached"
			:max-files="30"
			:max-filesize="20"
			file-path="public/help_table/documents_help_table"
			:is-update="isUpdate"
			message="Arrastre o seleccione los archivos"
			help-text="adjunte los archivos que acompañan su base de conocimiento, peso máximo por archivo 5m, cantidad máxima de archivos 10."
		>
		</input-file>
	</div>
</div>

<!-- Affair Field -->
<div class="form-group row m-b-15">
	{!! Form::label('enlace', 'Enlace: ', ['class' => 'col-form-label col-md-3 required']) !!}
	<div class="col-md-9">
		{!! Form::text('enlace', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.enlace }", 'v-model' => 'dataForm.enlace', 'required' => true]) !!}
		<small>@lang('Enter the') el @{{ `@lang('enlace')` | lowercase }}</small>
		<div class="invalid-feedback" v-if="dataErrors.enlace">
			<p class="m-b-0" v-for="error in dataErrors.enlace">@{{ error }}</p>
		</div>
	</div>
</div>