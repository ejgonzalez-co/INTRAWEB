<div class="panel">
    <br>
    <!-- Days Anticipated Field -->
    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('days_anticipated', 'Dias anticipados' . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <date-picker :value="dataForm" :is-inline="true" name-field="non_working_days" mode="multiple"
                    endpoint="holiday-calendar">
                </date-picker>

            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('days_anticipated', 'Dias anticipados' . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::number('days_anticipated', null, [
                    'class' => 'form-control',
                    'v-model' => 'dataForm.days_anticipated',
                    'required' => true,
                ]) !!}
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-15">
            <p>Para configurar los días anticipados de la fecha máxima en un plan de mejoramiento, utiliza este campo.
                Por ejemplo, si configura 5 días y la fecha máxima es el 20 de junio, la notificación se enviará
                automáticamente el 15 de junio. Esto brindará un recordatorio previo para que los evaluados puedan tomar
                las acciones necesarias con anticipación.</p>
        </div>
    </div>

    <!-- Massage Field -->
    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('message', 'Mensaje' . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::textArea('message', null, [
                    'class' => 'form-control',
                    'v-model' => 'dataForm.message',
                    'required' => true,
                ]) !!}
                <small>Por favor, ingresa el mensaje que desea que reciban los evaluados.</small>
            </div>
        </div>
    </div>
    <br>
</div>