<div class="col-md-12">
    <div class="col-md-11">
        <!-- Brand Name Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('brand_name', trans('Brand Name') . ':', ['class' => 'col-form-label col-md-4 required']) !!}
            <div class="col-md-8">
                {!! Form::text('brand_name', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.brand_name }",
                    'v-model' => 'dataForm.brand_name',
                    'required' => true,
                ]) !!}
                <small>@lang('Enter the') @{{ `@lang('Brand Name')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.brand_name">
                    <p class="m-b-0" v-for="error in dataErrors.brand_name">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

</div>


<dynamic-list label-button-add="Agregar referencia de la llanta" :data-list.sync="dataForm.tire_references"
    :data-list-options="[
        { label: 'Referencia de la llanta', name: 'reference_name', isShow: true }
    ]"
    class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
    <template #fields="scope">
        <div class="col-md-11">
            <!-- Name Field -->
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 required">Referencia de la llanta:</label>
                <div class="col-md-8">
                    <input class="form-control" v-model="scope.dataForm.reference_name">
                    <small>Ingrese el nombre de la referencia de la llanta</small>
                </div>
            </div>
        </div>

    </template>
</dynamic-list>