<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información de la oportunidad de mejora</strong></div>
    </div>
    <div class="panel-body">

        <div class="row">
            <!-- Criteria Name Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Nombre'):</strong>
            <p class="col-9 text-break">@{{ dataShow.name_opportunity_improvement }}.</p>
        </div>

        <div class="row">
            <!-- Observations Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Observations'):</strong>
            <p class="col-9 text-break">@{{ dataShow.description_cause_analysis }}.</p>
        </div>


        <div class="row">
            <!-- Weight Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Peso del criterio en el plan'):</strong>
            <p class="col-9 text-break">@{{ dataShow.weight ? dataShow.weight + "%" : "No establecido"  }}.</p>
        </div>
    </div>
</div>
