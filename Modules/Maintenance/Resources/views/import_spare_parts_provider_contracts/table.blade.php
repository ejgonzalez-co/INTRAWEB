<table class="table table-hover m-b-0" id="import-spare-parts-provider-contracts-table">
    <thead>
        <tr>
            {{-- <th>#</th> --}}
            <th>Item</th>
            <th>@lang('Descripción')</th>
            <th>@lang('Unidad de medida')</th>
            <th>@lang('Valor unitario')</th>
            <th>@lang('IVA')</th>
            <th>@lang('Valor total')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(importSparePartsProviderContract, key) in advancedSearchFilterPaginate()" :key="key">
            {{-- <td>@{{ getIndexItem(key) }}</td> --}}
            <td>@{{ importSparePartsProviderContract.item }}</td>
            <td>@{{ importSparePartsProviderContract.description }}</td>
            <td>@{{ importSparePartsProviderContract.unit_measure }}</td>
            <td>$@{{ currencyFormat(importSparePartsProviderContract.unit_value) }}</td>
            <td>$@{{ currencyFormat(importSparePartsProviderContract.iva) }}</td>
            <td>$@{{ currencyFormat(importSparePartsProviderContract.total_value) }}</td>
            <td>
                <button @click="edit(importSparePartsProviderContract)" data-backdrop="static" data-target="#modal-form-import-spare-parts-provider-contracts-edit" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(importSparePartsProviderContract)" data-target="#modal-view-import-spare-parts-provider-contracts" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(importSparePartsProviderContract[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
