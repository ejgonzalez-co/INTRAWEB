<table class="table table-hover m-b-0" id="pcPreviousStudies-table">
    <thead>
        <tr>
            <th>Número de estudio previo</th>
            <th>@lang('Type')</th>
            <th>@lang('Organizational Unit')</th>
            <th>@lang('Project') o Necesidad</th>
            <th>@lang('State')</th>
            <th class="w-25">@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(pcPreviousStudies, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ pcPreviousStudies.id }}</td>
            <td>@{{ pcPreviousStudies.type }}</td>
            <td>@{{ pcPreviousStudies.organizational_unit }}</td>

            <td >
                <ul>
                    <li>@{{ pcPreviousStudies.project_or_needs ? pcPreviousStudies.project_or_needs : 'No encontrado' }}</li>
                </ul>
            </td>

            {{-- <td v-else>
                <ul>
                <li v-for="(investment_sheet, key) in pcPreviousStudies.investment_sheets">
                    @{{ investment_sheet.pc_investment_technical_sheets.name_projects.name }} - @{{ investment_sheet.pc_investment_technical_sheets.description_problem_need }}
             
                </li>
                </ul>

            </td> --}}

           
            <td>
                <span :class="pcPreviousStudies.state_colour">
                    @{{ pcPreviousStudies.state_name }}
                </span>
            </td>

            <td>
                @if(Auth::user()->hasRole('PC Asistente de gerencia'))
                    <span v-if="pcPreviousStudies.state == 2 || pcPreviousStudies.state == 8 || pcPreviousStudies.state == 9  || pcPreviousStudies.state == 20">
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        <a :href="'{!! url('contractual-process/pc-previous-studies-documents') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Anexar documentos')"><i class="fas fa-folder-plus"></i></a>
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#send-studies-previous" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send') @lang('o') @lang('back')"><i class="fas fa-share-square"></i></button>
                    </span>

                @elseif (Auth::user()->hasRole('PC Jefe de jurídica'))
                    <span v-if="pcPreviousStudies.state == 6  || pcPreviousStudies.state == 10 || pcPreviousStudies.state == 11 || pcPreviousStudies.state == 13 || pcPreviousStudies.state == 25">
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        <a :href="'{!! url('contractual-process/pc-previous-studies-documents') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Anexar documentos')"><i class="fas fa-folder-plus"></i></a>
                    </span>
                    <button @click="edit(pcPreviousStudies)" v-if="pcPreviousStudies.state == 6  || pcPreviousStudies.state == 10 || pcPreviousStudies.state == 11 || pcPreviousStudies.state == 13 || pcPreviousStudies.state == 25" data-backdrop="static" data-target="#send-studies-previous-juridic" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send') @lang('o') @lang('back')"><i class="fas fa-share-square"></i></button>

                @elseif (Auth::user()->hasRole('PC Revisor de jurídico'))
                    <span v-if="pcPreviousStudies.state==7 || pcPreviousStudies.state==12 || pcPreviousStudies.state==18 || pcPreviousStudies.state==21 || pcPreviousStudies.state==24">
                        <button @click="edit(pcPreviousStudies)"  data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        <a :href="'{!! url('contractual-process/pc-previous-studies-documents') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Anexar documentos')"><i class="fas fa-folder-plus"></i></a>
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#send-studies-previous-juridic" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send') @lang('o') @lang('back')"><i class="fas fa-share-square"></i></button>
                    </span>
                    
                @elseif (Auth::user()->hasRole('PC Gestor planeación'))
                    <span v-if="pcPreviousStudies.state==3">
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        <a :href="'{!! url('contractual-process/pc-previous-studies-documents') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Anexar documentos')"><i class="fas fa-folder-plus"></i></a>
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#send-studies-previous-juridic" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send') @lang('o') @lang('back')"><i class="fas fa-share-square"></i></button>
                    </span>

                @elseif (Auth::user()->hasRole('PC Gestor presupuesto'))
                    <span v-if="pcPreviousStudies.state==4 || pcPreviousStudies.state==26">
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        <a :href="'{!! url('contractual-process/pc-previous-studies-documents') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Anexar documentos')"><i class="fas fa-folder-plus"></i></a>
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#send-studies-previous-juridic" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send') @lang('o') @lang('back')"><i class="fas fa-share-square"></i></button>
                    </span>

                @elseif (Auth::user()->hasRole('PC Gestor de recursos'))
                    <span v-if="pcPreviousStudies.state==5">
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        <a :href="'{!! url('contractual-process/pc-previous-studies-documents') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Anexar documentos')"><i class="fas fa-folder-plus"></i></a>
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#send-studies-previous-juridic" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send') @lang('o') @lang('back')"><i class="fas fa-share-square"></i></button>
                    </span>
                    
                @elseif (Auth::user()->hasRole('PC tesorero'))
                    <span v-if="pcPreviousStudies.state==22 && pcPreviousStudies.approval_treasurer==null">
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        <a :href="'{!! url('contractual-process/pc-previous-studies-documents') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Anexar documentos')"><i class="fas fa-folder-plus"></i></a>
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#send-studies-previous-juridic-approbed" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send') @lang('o') @lang('back')"><i class="fas fa-share-square"></i></button>
                    </span>

                @elseif (Auth::user()->hasRole('PC jurídica especializado 3'))
                    <span v-if="pcPreviousStudies.state==22 && pcPreviousStudies.approval_legal==null">
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        <a :href="'{!! url('contractual-process/pc-previous-studies-documents') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Anexar documentos')"><i class="fas fa-folder-plus"></i></a>
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#send-studies-previous-juridic-approbed" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send') @lang('o') @lang('back')"><i class="fas fa-share-square"></i></button>
                    </span>
                
                @elseif (Auth::user()->hasRole('PC director financiero'))
                    <span v-if="pcPreviousStudies.state==22 && pcPreviousStudies.approval_financial==null">
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        <a :href="'{!! url('contractual-process/pc-previous-studies-documents') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Anexar documentos')"><i class="fas fa-folder-plus"></i></a>
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#send-studies-previous-juridic-approbed" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send') @lang('o') @lang('back')"><i class="fas fa-share-square"></i></button>
                    </span>
                
                @elseif (Auth::user()->hasRole('PC director jurídico'))
                    <span v-if="pcPreviousStudies.state==22 && pcPreviousStudies.approval_counsel==null">
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        <a :href="'{!! url('contractual-process/pc-previous-studies-documents') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Anexar documentos')"><i class="fas fa-folder-plus"></i></a>
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#send-studies-previous-juridic-approbed" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send') @lang('o') @lang('back')"><i class="fas fa-share-square"></i></button>
                    </span>

                @elseif (Auth::user()->hasRole('PC Gerente'))
                    <span v-if="pcPreviousStudies.state==14">
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        <a :href="'{!! url('contractual-process/pc-previous-studies-documents') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Anexar documentos')"><i class="fas fa-folder-plus"></i></a>
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#send-studies-previous-juridic" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send') @lang('o') @lang('back')"><i class="fas fa-share-square"></i></button>
                    </span>

                @else
                    <span v-if="pcPreviousStudies.state==1 || pcPreviousStudies.state==17 || pcPreviousStudies.state==22 && pcPreviousStudies.approval_leader==null">
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        <a :href="'{!! url('contractual-process/pc-previous-studies-documents') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Anexar documentos')"><i class="fas fa-folder-plus"></i></a>
                        <button @click="edit(pcPreviousStudies)" data-backdrop="static" data-target="#send-studies-previous" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send')"><i class="fas fa-share-square"></i></button>
                    </span>
                @endif

                    <button @click="show(pcPreviousStudies)" data-target="#modal-view-pc-previous-studies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                    <a :href="'{!! url('contractual-process/pc-previous-studies-history') !!}?pc=' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Change history')"><i class="fas fa-history"></i></a>

                    <button @click="show(pcPreviousStudies)" data-target="#modal-view-news-studie" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Monitoring and Control')"><i class="fa fa-list"></i></button>

                    @if(Auth::user()->hasRole('PC Líder de proceso'))
                    <button @click="drop(pcPreviousStudies[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                    @endif

                    <a :href="'{!! url('contractual-process/pc-previous-studies-generate-pdf') !!}/' + pcPreviousStudies[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Export') @lang('Previous study')" target="_blank"><i class="fas fa-file-pdf"></i></a>

            </td>
        </tr>
    </tbody>
</table>
