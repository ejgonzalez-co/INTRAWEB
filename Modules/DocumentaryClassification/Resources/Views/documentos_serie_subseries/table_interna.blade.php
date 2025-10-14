<div class="table-responsive">
    <table-component
        id="typeDocumentaries-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="typeDocumentaries"
        sort-order="asc"
        table-class="table table-hover m-b-0"
        :show-filter="false"
        :pagination="dataPaginator"
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4"
        :cache-lifetime="0"
        >
        <table-column show="consecutivo" label="@lang('Consecutivo')"></table-column>
        <table-column show="tipo_documento" label="@lang('Tipo de documento')"></table-column>
        <table-column show="folios" label="@lang('folios')"></table-column>
        <table-column show="origen" label="@lang('Origen')"></table-column>
        <table-column label="@lang('Notas')">
        <label>Correspondencia interna</label></table-column>
        <table-column show="nombre" label="@lang('Nombre de dependencia')"></table-column>
        <table-column show="name_serie" label="@lang('Nombre de serie')"></table-column>
        <table-column show="no_serie" label="@lang('Código de serie')"></table-column>
        <table-column show="name_subserie" label="@lang('Nombre de subserie')"></table-column>
        <table-column show="no_subserie" label="@lang('Código de subserie')"></table-column>







        {{-- <table-column show="description" label="@lang('Description')"></table-column> --}}

    </table-component>
</div>