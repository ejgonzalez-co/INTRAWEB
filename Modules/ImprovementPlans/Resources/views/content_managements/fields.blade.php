<div class="panel">
    <br>
    <div class="col-md-9">
        <!-- Name Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'disabled' => true]) !!}
            </div>
        </div>
    </div>

    <div class="col-md-9" v-if="dataForm.name == 'Logo y creditaciones'">
        <div class="form-group row m-b-15">
            {!! Form::label('attached', trans('Archivo') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::file('archive', [
                    'accept' => '.png, .jpg, .jpeg',
                    '@change' => 'inputFile($event, "archive")',
                    'required' => true,
                    'class' => 'form-control'
                ]) !!}
                <small style="color: rgb(195, 195, 195);">El archivo debe ser una imagen en formato png, jpeg o jpg, con unas dimensiones de 150x100 y un tamaño menor a 1MB.</small>
            </div>
        </div>
    </div>

    <div class="col-md-9" v-if="dataForm.name != 'Logo y creditaciones'">
        <!-- Archive Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('color', trans('Color').':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                <input type="color" class="form-control" v-model="dataForm.color">
                <small v-if="dataForm.name === 'Color de botones'">Seleccione el color de los botones.</small>
                <small v-if="dataForm.name === 'Titulos secundarios'">Seleccione el color de los textos secundarios.</small>
            </div>
        </div>
    </div>
    <br>
</div>