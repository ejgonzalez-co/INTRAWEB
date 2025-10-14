
<!-- Variables Field -->
<div class="form-group row m-b-15">
    <h6 class="col-form-label">Elaborar tabla de retención documental oficina productora
    </h6>


    
    <div class="col-md-12 mt-5">
        <!--  Other officials Field destination-->
        <div class="form-group row m-b-15">
            {!! Form::label('users', 'Relacionar Serie o Subserie:', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                    
                <add-list-autocomplete :value="dataForm" name-prop="nameFalse"
                    name-field-autocomplete="trd_autocomplete" name-field="trd"
                    name-resource="get-series-subseries"
                    name-options-list="trd_list" :name-labels-display="['type','name','no_serieosubserie']"  name-key="id_series_subseries" 
                    help="Ingrese el nombre o el código de la serie o subserie documental que desea relacionar a la TRD, seleccione una opción del listado y luego de clic en el botón Relacionar serie o subserie"
                    :key="keyRefresh">
                </add-list-autocomplete>
            </div>
        </div>
    </div>

</div>