<style type="text/css">
	.tg{border-collapse:collapse;border-spacing:0;table-layout: fixed; width: 100%}
	td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
	  overflow:hidden;padding:5px 5px;word-break:normal;}
	th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
	  font-weight:normal;overflow:hidden;padding:5px 5px;word-break:normal;}
	.negrilla{border-color:inherit;font-weight:bold;}
	.text_center{text-align:center;}
	.text_justify{text-align:justify;}
	.fondo_gris {border-color:inherit;background-color: #F2F2F2;}
	.width_100{width:100%;}
	
	</style>
	
	<table class="tg">
	
	<tbody>
	  <tr>
		<th class="text_center" colspan="3" rowspan="4"><img src="{{ public_path() . '/assets/img/logo_azul.png' }}" alt="Escudo de Armenia" height="70px" width="123px"></th>
		<th class="negrilla" colspan="6" rowspan="4"><b>Estudios Previos</b></th>
		<th class="fondo_gris negrilla" colspan="4">Documento Controlado</th>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="4">Código: DJSG-R-004</td>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="4">Versión: 13</td>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="4">Fecha de Emisión: 20-04-24</td>
	  </tr>
	
	  <tr>
		<td class="fondo_gris negrilla" colspan="3">Unidad Organizativa:</td>
		<td class="text_center" colspan="2">{{ $previousStudy->organizational_unit }}</td>
	
		<td class="fondo_gris negrilla" colspan="3">Programa</td>
		@if ($previousStudy->program)
		<td class="text_center" colspan="5">{!! html_entity_decode($previousStudy->program) !!}</td>
		@else 
		  <td class="text_center" colspan="5">N/A</td>
		@endif
	
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla" colspan="3">Subprograma</td>
		@if ($previousStudy->subprogram)
		<td class="text_center" colspan="2">{!! html_entity_decode($previousStudy->subprogram) !!}</td>
		@else 
		  <td class="text_center" colspan="2">N/A</td>
		@endif
	
		<td class="fondo_gris negrilla" colspan="3">Línea de Proyecto:</td>
		@if ($previousStudy->lineproject)
		<td class="text_center" colspan="5">{!! html_entity_decode($previousStudy->lineproject) !!}</td>
		@else 
		  <td class="text_center" colspan="5">N/A</td>
		@endif
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla" colspan="3" rowspan="2">Proyecto:</td>
		@if ($previousStudy->project)
		<td class="text_center" colspan="6" rowspan="2" >{!! html_entity_decode($previousStudy->project) !!}</td>
		@else 
		<td class="text_center" colspan="6" rowspan="2">N/A</td>
		@endif
		<td class="fondo_gris negrilla" rowspan="2">Fecha</td>
		<td class="fondo_gris negrilla text_center">AA</td>
		<td class="fondo_gris negrilla text_center">MM</td>
		<td class="fondo_gris negrilla text_center">DD</td>
	  </tr>
	  <tr>
		<td class="text_center">{{ $previousStudy->dateYear }}</td>
		<td class="text_center">{{ $previousStudy->dateMonth }}</td>
		<td class="text_center">{{ $previousStudy->dateDay }}</td>
	  </tr>
	  <tr>
		<td colspan="13"></td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">1. Marco Legal (Dirección Jurídica)</td>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="13">Empresas Públicas de Armenia ESP., es una Empresa Industrial y Comercial del Estado del Orden Municipal, que regula sus actividades contractuales con fundamento en las siguientes disposiciones:<br><br>Artículo 31 de La ley 142 de 1994: por la cual se establece el Régimen de los Servicios Públicos Domiciliarios y se dictan otras disposiciones.  De otra parte la Ley 689 de 2001: por medio de la cual se modifica parcialmente la Ley 142 de  1994, en cuanto al Artículo 31, preceptúa: ¨ Régimen de la Contratación. Los Contratos que celebren las Entidades Estatales que presten los Servicios Públicos a los que se refiere esta Ley no estarán sujetos a las disposiciones del Estatuto General de la Contratación de la Administración Pública, salvo en lo que la presente Ley disponga otra cosa.<br></td>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="13">Acuerdo No. 013 de octubre 08 de 2007: en su Artículo 9. Actos y Contratos, establece que los actos y contratos de Empresas Públicas de Armenia ESP. Se regirán por las Reglas del Derecho Privado, salvo las excepciones consagradas expresamente en la Constitución Política de Colombia, la Ley 142 de 1994 y las demás disposiciones reglamentarias de los Servicios Públicos Domiciliarios y el actual Manual  de Contratación adoptado mediante el Acuerdo 17 de Julio del 2015 señala en su Artículo 21 el Régimen aplicable a los Contratos suscritos por Empresas Publicas de Armenia ESP.</td>
	  </tr>
	  <tr>
		<td colspan="13"></td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">2. Justificación técnica (Unidad Organizativa)</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="3">2.1 Descripción de la necesidad:</td>
		<td style="white-space: pre-wrap;" colspan="10">{{ $previousStudy->justification_tecnic_description }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="3">2.2 Planteamiento Técnico de Solución:</td>
		<td style="white-space: pre-wrap;" colspan="10">{{ $previousStudy->justification_tecnic_approach }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="3">2.3 Modalidad del Contrato a Celebrar:</td>
		<td style="white-space: pre-wrap;" colspan="10">{{ $previousStudy->justification_tecnic_modality }}</td>
	  </tr>
	  <tr>
		<td colspan="13"></td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">3. Fundamentos jurídicos de la modalidad de selección</td>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="13">{{ $previousStudy->fundaments_juridics }}</td>
	  </tr>
	  <tr>
		<td colspan="13"></td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">4. Imputación presupuestal e interventoría</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="3">4.1 Rubro Presupuestal:</td>
		<td colspan="10" style="white-space: pre-wrap;">{{$previousStudy->imputation_budget_rubro }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="3">4.2 Interventor y/o Supervisor Sugerido:</td>
		<td style="white-space: pre-wrap;" colspan="10">{{ $previousStudy->imputation_budget_interventor }}</td>
	  </tr>
	  <tr>
		<td colspan="13"></td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">5. Determinación del objeto contractual (Descripción, Unidad Organizativa)</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="2">Objeto:</td>
		<td style="white-space: pre-wrap;" colspan="11">{{ $previousStudy->determination_object }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="2">Valor:</td>
		<td style="white-space: pre-wrap;" colspan="3">{{ $previousStudy->determination_value }}</td>
		<td class="fondo_gris" colspan="3">Plazo de Ejecución:</td>
		<td style="white-space: pre-wrap;" colspan="5">{{ $previousStudy->determination_time_limit }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="2">Forma de Pago:</td>
		<td style="white-space: pre-wrap;" colspan="11">{{ $previousStudy->determination_form_pay }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" rowspan="3">Obligaciones Principales</td>
		<td style="white-space: pre-wrap;" colspan="12">{{ $previousStudy->obligation_principal }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="12">Colocar <span style="font-weight:bold">(X) </span>en caso de que aplique la siguiente obligación al objeto del contrato, de lo contrario coloque (NA) No aplica</td>
	  </tr>
	  <tr>
		@if ($previousStudy->obligation_principal_documentation == 1)
		  <td class="text_center">X</td>
		@else 
		  <td class=""></td>
		@endif
		<td class="fondo_gris" colspan="11">Verificar la existencia de <span style="font-weight:bold">la documentación </span>en el Sistema de Gestión Integrado de las actividades a ejecutar aplicables al objeto del contrato y en el marco de la Política de Gestión del conocimiento y la innovación de Empresas Públicas de Armenia ESP., <span style="font-weight:bold">documentarla, ajustarla y/o actualizarla</span> teniendo en cuenta las acciones realizadas y las lecciones aprendidas en el desarrollo de las obligaciones contractuales ejecutadas.</td>
	  </tr>
	  <tr>
		<td colspan="13"></td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="5">6. Situación de predios</td>
		<td class="fondo_gris negrilla text_center">Si</td>
		<td class="fondo_gris negrilla text_center">No</td>
		<td class="fondo_gris negrilla" colspan="6">Observaciones</td>
	  </tr>
	  <tr>
		<td class="fondo_gris text_justify" colspan="5">La obra a realizar afecta exclusivamente predios públicos </td>
		@if ($previousStudy->situation_estates_public == 1)
		  <td class="text_center">X</td>
		  <td class=""></td>
		@else 
		  <td class=""></td>
		  <td class="text_center">X</td>
		@endif
		<td style="white-space: pre-wrap;" colspan="6">{{ $previousStudy->situation_estates_public_observation }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris text_justify" colspan="5">La obra a realizar afecta un predio privado </td>
		@if ($previousStudy->situation_estates_private == 1)
		  <td class="text_center">X</td>
		  <td class=""></td>
		@else 
		  <td class=""></td>
		  <td class="text_center">X</td>
		@endif
		<td style="white-space: pre-wrap;" colspan="6">{{ $previousStudy->situation_estates_private_observation }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">Solución planteada y avance de la misma</td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="5">Afectación a servidumbre</td>
		<td class="fondo_gris negrilla text_center">Si</td>
		<td class="fondo_gris negrilla text_center">No</td>
		<td class="fondo_gris negrilla text_center" colspan="6">Observaciones</td>
	  </tr>
	  <tr>
		<td class="fondo_gris text_center" colspan="5">El Predio a intervenir se encuentra afectado por servidumbre </td>
		@if ($previousStudy->solution_servitude == 1)
		  <td class="text_center">X</td>
		  <td class=""></td>
		@else 
		  <td class=""></td>
		  <td class="text_center">X</td>
		@endif
		<td style="white-space: pre-wrap;" colspan="6">{{ $previousStudy->solution_servitude_observation }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="5">Solución planteada y avance de la misma</td>
		<td class="fondo_gris negrilla text_center">Si</td>
		<td class="fondo_gris negrilla text_center">No</td>
		<td class="fondo_gris negrilla text_center" colspan="6">Observaciones</td>
	  </tr>
	  <tr>
		<td class="fondo_gris text_center" colspan="5">Se requiere trámite de conciliación con el propietario </td>
		@if ($previousStudy->solution_owner == 1)
		  <td class="text_center">X</td>
		  <td class=""></td>
		@else 
		  <td class=""></td>
		  <td class="text_center">X</td>
		@endif
		<td style="white-space: pre-wrap;" colspan="6">{{ $previousStudy->solution_owner_observation }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">Trámite de conciliación con el propietario</td>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="13">{{ $previousStudy->process_concilation }}</td>
	  </tr>
	  <tr>
		<td colspan="13"></td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">Tramite de licencias y permisos </td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla" colspan="5">Trámite</td>
		<td class="fondo_gris negrilla">Si</td>
		<td class="fondo_gris negrilla">No</td>
		<td class="fondo_gris negrilla" colspan="2">Entidad</td>
		<td class="fondo_gris negrilla" colspan="4">Observaciones</td>
	  </tr>
	  <tr>
		<td class="fondo_gris"  colspan="5">La obra requiere Licencia Ambiental</td>
		@if ($previousStudy->process_licenses_environment == 1)
		  <td class="text_center">X</td>
		  <td class=""></td>
		@else 
		  <td class=""></td>
		  <td class="text_center">X</td>
		@endif
		
		<td class="fondo_gris text_center" colspan="2">Corporación Autónoma Regional del Quindío CRQ</td>
		<td class="fondo_gris text_center" colspan="4">Ver: www.crq.gov.co<br>Link Tramites</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="5">La obra requiere permiso ocupación de cauces, playas y lechos</td>
		@if ($previousStudy->process_licenses_beach == 1)
		  <td class="text_center">X</td>
		  <td class=""></td>
		@else 
		  <td class=""></td>
		  <td class="text_center">X</td>
		@endif
		<td class="fondo_gris text_center" colspan="2">Corporación Autónoma Regional del Quindío CRQ</td>
		<td class="fondo_gris text_center" colspan="4">Ver: www.crq.gov.co<br>Link Tramites</td>
	
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="5">La obra requiere permisos de aprovechamiento forestal</td>
		@if ($previousStudy->process_licenses_forestal == 1)
		  <td class="text_center">X</td>
		  <td class=""></td>
		@else 
		  <td class=""></td>
		  <td class="text_center">X</td>
		@endif
		<td class="fondo_gris text_center" colspan="2">Corporación Autónoma Regional del Quindío CRQ</td>
		<td class="fondo_gris text_center" colspan="4">Ver: www.crq.gov.co<br>Link Tramites</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="5">La obra requiere permiso de corte y aprovechamiento de guadua</td>
		@if ($previousStudy->process_licenses_guadua == 1)
		  <td class="text_center">X</td>
		  <td class=""></td>
		@else 
		  <td class=""></td>
		  <td class="text_center">X</td>
		@endif
		<td class="fondo_gris text_center" colspan="2">Corporación Autónoma Regional del Quindío CRQ</td>
		<td class="fondo_gris text_center" colspan="4">Ver: www.crq.gov.co<br>Link Tramites</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="5">La obra requiere permiso de "Aprovechamiento forestal árboles aislados"</td>
		@if ($previousStudy->process_licenses_tree == 1)
		  <td class="text_center">X</td>
		  <td class=""></td>
		@else 
		  <td class=""></td>
		  <td class="text_center">X</td>
		@endif
		<td class="fondo_gris text_center" colspan="2">Corporación Autónoma Regional del Quindío CRQ</td>
		<td class="fondo_gris text_center" colspan="4">Ver: www.crq.gov.co<br>Link Tramites</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="5">Requiere permiso para la ocupación temporal de carreteras concesionadas</td>
		@if ($previousStudy->process_licenses_road == 1)
		  <td class="text_center">X</td>
		  <td class=""></td>
		@else 
		  <td class=""></td>
		  <td class="text_center">X</td>
		@endif
		<td class="fondo_gris text_center" colspan="2">Instituto Nacional de Concesiones INCO</td>
		<td class="fondo_gris text_center" colspan="4">Ver: www.inco.gov.co<br>Link Trámites</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="5">La obra requiere permiso de corte y demolición de pavimento</td>
		@if ($previousStudy->process_licenses_demolition == 1)
		  <td class="text_center">X</td>
		  <td class=""></td>
		@else 
		  <td class=""></td>
		  <td class="text_center">X</td>
		@endif
		<td class="fondo_gris text_center" colspan="2">Secretaria de Infraestructura Municipal</td>
		<td class="fondo_gris text_center" colspan="4">De requerirlo el contratista debe enviar un oficio que contemple la Ubicación de la Obra</td>
	  </tr>
	  <tr>
		<td class="fondo_gris" colspan="5">La obra requiere permiso para intervención del Árbol Urbano</td>
		@if ($previousStudy->process_licenses_tree_urban == 1)
		  <td class="text_center">X</td>
		  <td class=""></td>
		@else 
		  <td class=""></td>
		  <td class="text_center">X</td>
		@endif
		<td class="fondo_gris text_center" colspan="2">Departamento Administrativo de Planeación</td>
		<td class="fondo_gris text_center" colspan="4">Ver: www.armenia.gov.co<br>Link Trámites</td>
	  </tr>
	  <tr>
		<td colspan="13"></td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">7. Tipificación, cuantificación y asignación de riesgos previsibles, no asegurables (Si tiene riesgos)</td>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="13">
	  
		@if(count($previousStudy->pcPreviousStudiesTipifications)>0)
			  <table class="tg">
				  <thead class="text_center fondo_gris negrilla text_center">
					  <tr>
						  <td class="text_center">Tipo</td>
						  <td class="text_center">Riesgos</td>
						  <td class="text_center">Efecto</td>
						  <td class="text_center">Probabilidad de ocurrencia (de 1 a 5)</td>
						  <td class="text_center">Impacto (1 a 5)</td>
						  <td class="text_center">Asignación del Riesgo</td>
					  </tr>
				  </thead>
				  <tbody>
	
				  @foreach($previousStudy->pcPreviousStudiesTipifications as $tipification)
					<tr>
					  <td>{{ $tipification->type_danger }}</td>
					  <td>{{ $tipification->danger }}</td>
					  <td>{{ $tipification->effect }}</td>
					  <td class="text_center">{{ $tipification->probability }}</td>
					  <td class="text_center">{{ $tipification->impact }}</td>
					  <td>{{ $tipification->allocation_danger }}</td>
					</tr>
				  @endforeach
				  
				  </tbody>
			  </table>
	
		@else
		  <p class="text_center">N/A</p>
		@endif
		</td>
	  </tr>
	  <tr>
		<td colspan="13"></td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">8. Indicación de las coberturas de los riesgos asegurables </td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">En la etapa Pre-contractual</td>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="13">{{ $previousStudy->indication_danger_precontractual }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">En la etapa de ejecución (Garantía Única) </td>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="13">{{ $previousStudy->indication_danger_ejecution }}</td>
	  </tr>
	  <tr>
		<td colspan="13"></td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla text_center" colspan="13">Revisó (Dirección Jurídica y Secretaria General) </td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla" colspan="2">Firma:</td>
		<td style="white-space: pre-wrap;" colspan="4"><img class="firma" src="{{ $previousStudy->firmLawyer }}"></td>
		<td class="negrilla" colspan="7"></td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla" colspan="2">Nombre:</td>
	
	
		<td style="white-space: pre-wrap;" colspan="4">{{ empty($previousStudy->usersLawyer->name) ? '': $previousStudy->usersLawyer->name }}</td>
		<td class="negrilla" colspan="7">Se revisan aspectos jurídicos del documento</td>
	
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla" colspan="2">Cargo:</td>
		<td style="white-space: pre-wrap;" colspan="4">{{ empty($previousStudy->cargoLawyer) ? '': $previousStudy->cargoLawyer }}</td>
		<td class="negrilla" colspan="7"></td>
	
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla" colspan="2">Firma:</td>
		<td style="white-space: pre-wrap;" colspan="2"><img class="firma" src="{{ $previousStudy->firmBoss }}"></td>
		<td style="white-space: pre-wrap;" colspan="3"><img class="firma" src="{{ $previousStudy->firmLeader }}"></td>
		<td style="white-space: pre-wrap;" colspan="6"><img class="firma" src="{{ $previousStudy->firmSubgerente}}"></td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla" colspan="2">Nombre:</td>
		<td style="white-space: pre-wrap;" colspan="2">{{ empty($previousStudy->usersBoss->name) ? '': $previousStudy->usersBoss->name }}</td>
		<td style="white-space: pre-wrap;" colspan="3">{{ empty($previousStudy->usersLeader->name) ? '': $previousStudy->usersLeader->name }}</td>
		<td style="white-space: pre-wrap;" colspan="6">{{ empty($previousStudy->usersSubgerente->name) ? '': $previousStudy->usersSubgerente->name }}</td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla" colspan="2">Proceso y/o Oficina:</td>
		<td style="white-space: pre-wrap;" colspan="2">{{ $previousStudy->dependencyBoss }} </td>
		<td style="white-space: pre-wrap;" colspan="3">{{ $previousStudy->dependencyLeader }} </td>
		<td style="white-space: pre-wrap;" colspan="6">{{ $previousStudy->dependencySubgerente }} </td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla" colspan="2">Cargo:</td>
		<td class="fondo_gris negrilla" colspan="2">Jefe de Oficina</td>
		<td class="fondo_gris negrilla" colspan="3">Líder del Proceso</td>
		<td class="fondo_gris negrilla" colspan="6">Subgerente y/o Director</td>
	  </tr>
	  <tr>
		<td class="fondo_gris negrilla" colspan="7">Elaboró y reviso</td>
		<td class="fondo_gris negrilla" colspan="6">Aprobó</td>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="13"></td>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="13">Notas: 1. Diligencie el espacio de Jefe de Oficina cuando aplique al proceso de lo contrario coloque NA (No Aplica)<br>2. Cuanto aplique el Jefe de Oficina será el responsable de la elaboración de los Estudios Previos y el Líder del Proceso de su revisión.</td>
	  </tr>
	  <tr>
		<td style="white-space: pre-wrap;" colspan="13">Nota 2:  Conforme al principio de Planeación contractual, se hace constar que con la aprobación de los estudios previos por parte del Subgerente y/o Director, se verificó el estudio de precios de los bienes, obras o servicios del presente proceso contractual, encontrándose  acorde a los costos del mercado</td>
	  </tr>
	</tbody>
	</table>