<!-- Id Dependencia Field -->
<div class="form-group row m-b-15">
    {!! Form::label('dependencias_id', trans('Dependencia').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="dependencias_id" reduce-label="nombre" name-resource="/intranet/get-dependencies" :value="dataForm" :is-required="true"></select-check>
        <small>Seleccione la dependencia a autorizar.</small>
    </div>
</div>

<!-- Responsable Field -->
<div class="form-group row m-b-15">
    {!! Form::label('responsable', trans('Responsable').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
            <select-check css-class="form-control" name-field="responsable" reduce-label="name" reduce-key="id"
                :name-resource="'get-users-dependency/'+dataForm.dependencias_id" :value="dataForm" :key="dataForm.dependencias_id"
                :is-required="true">
            </select-check>

        <small>Seleccione el responsable de la autorización.</small>
    </div>
</div>

<!-- Condición para saber si ya selecciono un tipo de activo y mostrar las categorías -->
<!-- <span v-if="dataForm.mant_asset_type_id"> -->
    <div class="form-group row m-b-15">
        <dynamic-list 
            label-button-add="Agregar ítem a la lista" 
            :data-list.sync="dataForm.authorized_categories_model" 
            class-table="table-responsive table-bordered" 
            class-container="w-100 p-10"
            :data-list-options="[
                            {label:'Tipo de activo', name:'mant_asset_type_id', isShow: true, refList: 'selectRefAsset'},
                            {label:'Categoría', name:'mant_category_id', isShow: true, refList: 'selectRefCategory'}
                        ]">
            <template #fields="scope">

                <!-- Tipo activo Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('mant_asset_type_id', trans('Tipo de activo').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check 
                        :ids-to-empty="['mant_category_id']" 
                        ref-select-check="selectRefAsset"
                        css-class="form-control" 
                        name-field="mant_asset_type_id" 
                        reduce-label="name" 
                        reduce-key="id" 
                        name-resource="get-type-assets-full" 
                        :value="scope.dataForm" 
                        :is-required="true">
                        </select-check>
                        <small>Seleccione el tipo de activo a autorizar.</small>
                    </div>
                </div>

                <!-- Category Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('Categoría', trans('Categoría').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                        ref-select-check="selectRefCategory" 
                        css-class="form-control" 
                        name-field="mant_category_id" 
                        reduce-label="name" 
                        reduce-key="id" 
                        name-resource="get-categories-full" 
                        :value="scope.dataForm" 
                        :is-required="true">
                        </select-check>
                        <small>Seleccione la categoría a autorizar.</small>
                    </div>
                </div>

            </template>
        </dynamic-list>
    </div>
<!-- </span> -->