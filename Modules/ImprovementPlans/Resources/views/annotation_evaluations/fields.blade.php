<div class="form-group row m-b-15">
    {!! Form::label('objective_evaluation', trans('Anotación').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <textarea cols="30" rows="10" class="form-control" required v-model="dataForm.observation"></textarea>
        <small>Agregue una anotación .</small>
        <div class="invalid-feedback" v-if="dataErrors.objective_evaluation">
            <p class="m-b-0" v-for="error in dataErrors.objective_evaluation">@{{ error }}</p>
        </div>
    </div>
</div>