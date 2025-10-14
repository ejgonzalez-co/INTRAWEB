<div>

    {{-- Select con la opción de consecutivo que se va a configurar --}}
    <div class="form-group row m-b-15">
        {!! Form::label('status', trans('Tipo de consecutivo a configurar') . ':', [
            'class' => 'col-form-label col-md-3 required',
        ]) !!}
        <div class="col-md-9">
            {!! Form::select(
                'type_of_request',
                [
                    'tratada' => 'Configurar consecutivo de la matriz del agua tratada',
                    'cruda' => 'Configurar consecutivo de la matriz del agua cruda',
                ],
                null,
                [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.type_of_request }",
                    'v-model' => 'dataForm.tipoAgua',
                    'required' => true,
                ],
            ) !!}

            <small>@lang('Seleccione el') @{{ `@lang('tipo de consecutivo a configurar')` | lowercase }}</small>
            <div class="invalid-feedback" v-if="dataErrors.status">
                <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
            </div>
        </div>
    </div>


    {{-- Modal que se muetra si la seleccion es agua tratada. --}}
    <div class="panel" v-if="dataForm.tipoAgua == 'tratada'">
        <div class="panel-heading ui-sortable-handle">

            <div class="panel-title"><strong>Configurar consecutivo de la matriz de la agua tratada</strong></div>
        </div>

        <div class="panel-body">

            <!-- Nex Consecutiveie Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('nex_consecutiveIE', trans('Consecutivo de partida') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-9">
                    {!! Form::number('nex_consecutiveIE', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.nex_consecutiveIE }",
                        'v-model' => 'dataForm.nex_consecutiveIE',
                        'required' => true,
                    ]) !!}
                    <small>@lang('Ingrese consecutivo de partida debe de ser solo el valor nùmerico')</small>
                    <div class="invalid-feedback" v-if="dataErrors.nex_consecutiveIE">
                        <p class="m-b-0" v-for="error in dataErrors.nex_consecutiveIE">@{{ error }}</p>
                    </div>
                </div>
            </div>


            <!-- Coments Consecutive Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('coments_consecutive', trans('Justificación') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-9">
                    {!! Form::textarea('coments_consecutive', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.coments_consecutive }",
                        'v-model' => 'dataForm.coments_consecutive',
                        'required' => true,
                    ]) !!}
                    <small>@lang('Ingrese una breve justificación, por la cual configura un nuevo consecutivo.')</small>
                    <div class="invalid-feedback" v-if="dataErrors.coments_consecutive">
                        <p class="m-b-0" v-for="error in dataErrors.coments_consecutive">@{{ error }}</p>
                    </div>
                </div>
            </div>


        </div>
    </div>

    {{-- Modal que se muetra si la seleccion es agua cruda --}}
    <div class="panel" v-if="dataForm.tipoAgua == 'cruda'">
        <div class="panel-heading ui-sortable-handle">

            <div class="panel-title"><strong>Configurar consecutivo de la matriz de la agua cruda</strong></div>
        </div>

        <div class="panel-body">

            <!-- Nex Consecutiveic Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('nex_consecutiveIC', trans('Consecutivo de partida') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-9">
                    {!! Form::number('nex_consecutiveIC', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.nex_consecutiveIC }",
                        'v-model' => 'dataForm.nex_consecutiveIC',
                        'required' => true,
                    ]) !!}
                    <small>Ingrese consecutivo de partida debe de ser solo el valor nùmerico</small>
                    <div class="invalid-feedback" v-if="dataErrors.nex_consecutiveIC">
                        <p class="m-b-0" v-for="error in dataErrors.nex_consecutiveIC">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Coments Consecutive Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('coments_consecutive', trans('Justificación') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-9">
                    {!! Form::textarea('coments_consecutive', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.coments_consecutive }",
                        'v-model' => 'dataForm.coments_consecutive',
                        'required' => true,
                    ]) !!}
                    <small>@lang('Ingrese una breve justificación, por la cual configura un nuevo consecutivo.')</small>
                    <div class="invalid-feedback" v-if="dataErrors.coments_consecutive">
                        <p class="m-b-0" v-for="error in dataErrors.coments_consecutive">@{{ error }}</p>
                    </div>
                </div>
            </div>


        </div>
    </div>

</div>
