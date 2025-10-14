<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title" >Información general</h4> 
    </div>

    <div class="panel-body">

        <!-- Path General Document Field -->
    <div class="form-group row m-b-15" >
        {!! Form::label('path_general_document', trans('Relacione archivo de importación').':', ['class' => 'col-form-label col-md-3']) !!}
        <div class="col-md-9">
            {!! Form::file('path_general_document', ['accept' => '.csv, .xls, .xlsx', '@change' => 'inputFile($event, "path_general_document")', 'required' => false]) !!}
            <br><small class="f-s-12 text-grey-darker">Seleccione y adjunte el formato. </small>
        </div>
    </div>


        
    </div>
</div>

