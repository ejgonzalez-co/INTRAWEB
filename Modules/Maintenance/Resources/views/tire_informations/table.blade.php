<div class="table-responsive text-center">
    <table-component id="tireInformations-table" :data="dataList" sort-by="tireInformations"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')" filter-input-class="form-control col-md-4"
        :cache-lifetime="0">
        <table-column show="id" label="#"></table-column>
        <table-column label="@lang('process')">
            <template slot-scope="row" v-if="row.assignment_type == 'Activo'">
                <label>@{{ row.name_dependencias ?? null}}</label>
            </template>
        </table-column>
        <table-column label="@lang('Name of the equipment or machinery')">
            <template slot-scope="row" v-if="row.assignment_type == 'Activo'">
                <label>@{{ row.name_machinery ?? null}}</label>
            </template>
        </table-column>
        <table-column label="@lang('Plaque')">
            <template slot-scope="row" v-if="row.assignment_type == 'Activo'">
                <label>@{{ row.plaque ?? null}}</label>
            </template>
        </table-column>
        <table-column label="@lang('Dimensión de llanta')">
            <template slot-scope="row">
                <label>@{{ row.tire_reference ?? null}}</label>
            </template>
        </table-column>
        <table-column label="Código de la llanta">
            <template slot-scope="row" v-if="row.assignment_type == 'Activo'">
                <label>@{{ row.code_tire ?? null}}</label>
            </template>
        </table-column>
        <table-column label="@lang('Type Tire')">
            <template slot-scope="row">
                <label>@{{ row.type_tire ?? null}}</label>
            </template>
        </table-column>
        <table-column label="@lang('Position Tire')" >
            <template slot-scope="row" v-if="row.assignment_type == 'Activo'">
                <label>@{{ row.position_tire ?? null}}</label>
            </template>
        </table-column>
        <table-column show="tire_wear" label="@lang('tire wear')">

            <template slot-scope="row" :sortable="false" :filterable="false" v-if="row.assignment_type == 'Activo'">
                <label v-if="row.tire_wear > 75" class="text-light"
                    style="background: red; padding:5px"> <b>@{{ currencyFormat(row . tire_wear) }} %</b> </label>
                    
                <label v-if="row.tire_wear >= 60 && row.tire_wear <= 74.99" style="background: yellow; padding:5px">
                    <b>@{{ currencyFormat(row . tire_wear) }} %</b> </label>
                <label v-if="row.tire_wear <= 59.99 && row.tire_wear > 0" class="text-light"
                    style="background: green; padding:5px"> <b>@{{ currencyFormat(row . tire_wear) }} %</b> </label>
            </template>
        </table-column>
        <table-column label="@lang('tire brand')">
            <template slot-scope="row">
                <label>@{{ row.tire_brand_name ?? null}}</label>
            </template>
        </table-column>
        <table-column show="tire_pressure" label="@lang('inflation pressure')">
            <template slot-scope="row" :sortable="false" :filterable="false" v-if="row.assignment_type == 'Activo'">
                <label v-if="row.tire_pressure == 'Presión Alta'" class="text-light"
                    style="background: red; padding:5px"> <b>Presión Alta</b> </label>
                <label v-if="row.tire_pressure == 'Presión Baja'" class="text-light"
                    style="background: rgb(31, 70, 226); padding:5px"> <b>Presión Baja</b> </label>
                <label v-if="row.tire_pressure == 'Presión Normal'" class="text-light"
                    style="background: rgb(0, 162, 0); padding:5px"> <b>Presión Normal</b> </label>
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button v-if="row.state != 'Dada de baja'" @click="callFunctionComponent('FormTireInformationComponent','showModal',row)" data-backdrop="static"
                     class="btn btn-white btn-icon btn-md"
                 data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                @endif
                {{-- @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo')|| Auth::user()->hasRole('mant Operador Llantas'))
                <button v-if="row.state == null" @click="edit(row)" data-backdrop="static"
                    data-target="#modal-form-tireInformations" data-toggle="modal" class="btn btn-white btn-icon btn-md"
                    data-toggle="tooltip" data-placement="top" title="Adicionar datos">
                    <i class="fas fa-plus"></i>
                </button>
                @endif --}}
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo') || Auth::user()->hasRole('mant Operador Llantas'))
                    @if (empty($machinery))
                        <a  v-if="row.state != null && row.assignment_type == 'Activo' && row.state != 'Dada de baja'" class="btn btn-white btn-icon btn-md"
                        :href="'tire-wears?individual_tire_id='+row.id" title="Agregar desgaste de llanta"><i class="fas fa-minus-circle"></i></a>
                    @else
                        <a v-if="row.state != null && row.assignment_type == 'Activo' && row.state != 'Dada de baja'" class="btn btn-white btn-icon btn-md"
                        :href="'tire-wears?individual_tire_id='+row.id+'/machinery='+{{$machinery}}" title="Agregar desgaste de llanta"><i class="fas fa-minus-circle"></i></a>
                    @endif
                @endif

                <button @click="show(row)" data-target="#modal-view-tireInformations" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button v-if="row.state != 'Dada de baja'" @click="edit(row)" data-target="#modal-delete-provider-contract" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                data-placement="top" title="@lang('drop')">
                <i class="fa fa-trash"></i>
                @endif
                {{-- Inicio boton historial --}}
                {{-- <button @click="show(row)" data-target="#modal-form-historie" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="Ver historial">
                    <i class="fas fa-history"></i>
                </button> --}}






            </template>
        </table-column>
    </table-component>
</div>
