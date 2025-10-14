@if (Auth::user()->hasRole('Soporte TIC'))
    <div class="panel">
        <div class="panel-body">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-2 required" for="">Funcionario quien revisa</label>
                <div class="col-md-4">
                    <select-check css-class="form-control" name-field="reviewer_id" reduce-label="name" reduce-key="id"
                        name-resource="reviewers" :value="dataForm" :is-required="true"
                        :key="keyRefresh">
                    </select-check>
                    <small>Seleccione el nombre del funcionario quien revisa el concepto técnico.</small>
                </div>
            </div>
        </div>
    </div>
@endif
