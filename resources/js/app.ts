/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// importa funcionalidades de toast de manera general
window.toastr = require('toastr');

import './bootstrap';

import Vue from "vue";
import VuePrintNB from 'vue-print-nb';

import { Cropper } from 'vue-advanced-cropper';
import vSelect from 'vue-select';
import { VueRecaptcha } from 'vue-recaptcha';
import { jwtDecode } from 'jwt-decode';

import VueSweetalert2 from 'vue-sweetalert2';
import VCalendar from 'v-calendar';
import Verte from 'verte';
// Libreria para el formato de moneda en los inputs
import VueCurrencyInput from 'vue-currency-input';
//libreria de tablas
import { TableComponent, TableColumn } from 'vue-table-component';
import  StarRating  from "vue-star-rating";
import wysiwyg from "vue-wysiwyg";

import VueMultiselect from 'vue-multiselect';
import BootstrapVue from 'bootstrap-vue';
// import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

// Estilos componente select2
import 'vue-select/dist/vue-select.css';
// Estilos componente sweetalert2
import 'sweetalert2/dist/sweetalert2.min.css';

import "vue-wysiwyg/dist/vueWysiwyg.css";

import 'verte/dist/verte.css';
import 'vue-multiselect/dist/vue-multiselect.min.css';
import VueFeedbackReaction from "vue-feedback-reaction";



/**
 * Importacion de componentes locales
 */
import ExampleComponent from './components/ExampleComponent.vue';
import AutoCompleteFieldComponent from './components/core/AutoCompleteFieldComponent.vue';
import InputDataComponent from "./components/core/InputDataComponent.vue";
import CrudComponent from './components/core/CrudComponent.vue';
import CropperImageComponent from './components/CropperImageComponent.vue';
import DatePickerComponent from './components/core/DatePickerComponent.vue';
import DynamicListComponent from './components/core/DynamicListComponent.vue';
import DynamicModalFormComponent from './components/core/DynamicModalFormComponent.vue';
import SelectCheckCrudFieldComponent from './components/core/SelectCheckCrudFieldComponent.vue';
import SelectCheckCrudFieldComponentDepend from './components/core/SelectCheckCrudFieldComponentDepend.vue';
import InputCrudFileComponent from './components/core/InputCrudFileComponent.vue';
import WidgetComponent from './components/WidgetComponent.vue';
import WidgetComponentV2 from './components/WidgetComponentV2.vue';
import EditProfilePage from './pages/EditProfilePage.vue';
import AddListAutoCompleteComponent from './components/core/AddListAutoCompleteComponent.vue';
import ChartComponent from './components/core/ChartComponent.vue';
import HolidayCalendarsComponent from './pages/HolidayCalendarsComponent.vue';
import ColourPickerComponent from './pages/ColourPickerComponent.vue';
import AddListOptionComponent from './components/core/AddListOptionComponent.vue';
import ExecutionFromActionComponent from './components/core/ExecutionFromActionComponent.vue';
import TextAreaEditorComponent from './components/core/TextAreaEditorComponent.vue';
import SendPetitionMessageComponent from './components/core/SendPetitionMessageComponent.vue';
import MultiselectComponent from './components/core/MultiselectComponent.vue';
import AlertConfirmationComponent from './components/core/AlertConfirmationComponent.vue';
import CapacityComponent from './components/core/CapacityComponent.vue';

import ViewerAtthachementComponent from './components/core/ViewerAtthachementComponent.vue';
import ChatGptComponent from './components/core/ChatGptComponent.vue';

import SignToImageComponent from './components/core/SignToImageComponent.vue';
import SignExternalComponent from './components/core/SignExternalComponent.vue';
import AnnotationsGeneralComponent from './components/core/AnnotationsGeneralComponent.vue';
import ExpedientesGeneralComponent from './pages/Expedientes/ExpedientesGeneralComponent.vue';
import DocumentosExpedienteValidatorComponent from  './pages/Expedientes/DocumentosExpedienteValidatorComponent.vue';

import ViewerPublic from './components/core/ViewerPublic.vue';

// Componentes del módulo de mesa de ayuda
import AssetsTics from './pages/help_table/AssetsTics.vue';
import AddTechnicalTicComponent from './pages/help_table/AddTechnicalTicComponent.vue';
import RequestTicHistories from  './pages/help_table/RequestTicHistories.vue';
import TicHTAssetMaintenance from  './pages/help_table/TicHTAssetMaintenance.vue';
import HtTicSatisfactionPoll from  './pages/help_table/HtTicSatisfactionPoll.vue';
import HtTicKnowledgeBase from  './pages/help_table/HtTicKnowledgeBase.vue';
import BtnCreateRequest from "./pages/help_table/BtnCreateRequest.vue";
import WidgetComponentFuntional from "./pages/help_table/WidgetComponentFunctional.vue";
import DatePickerNotHoursComponent from "./pages/help_table/DatePickerNotHoursComponent.vue";


//Intranet encuestas
import PollAnswers from  './pages/intranet/poll/PollAnswers.vue';
//Intranet calendario
import CalendarEventComponent from './pages/intranet/CalendarEventComponent.vue';

import SearchUniversal from './components/core/SearchUniversalComponent.vue';

import SearchResultUniversal from './components/core/SearchResultUniversalComponent.vue';

import InternalComponent from  './pages/correspondence/InternalComponent.vue';
import MetadatosComponent from  './pages/DocumentaryClassification/MetadatosComponent.vue';
import FormCriteriosBusqueda from './pages/DocumentaryClassification/FormCriteriosBusqueda.vue';
import CriteriosBusquedaSave from './pages/DocumentaryClassification/CriteriosBusquedaSave.vue'

import ExternalComponent from  './pages/correspondence/ExternalComponent.vue';
import AnnotationAndRead from  './pages/correspondence/AnnotationAndRead.vue';
import CorrespondenceValidatorComponent from  './pages/correspondence/CorrespondenceValidatorComponent.vue';
import DatePikerMultipleComponent from  './pages/correspondence/DatePikerMultipleComponent.vue';

import ShareCorrespondenceUser from  './pages/correspondence/ShareCorrespondenceUser.vue';

import VueDragResize from 'vue-drag-resize';

import ExternalReceived from './pages/correspondence/ExternalReceived.vue';

//PQRS
import PQRComponent from  './pages/pqr/PQRComponent.vue';
import axios from 'axios';

// Dashboard
import DashboardComponent from  './pages/dashboard/DashboardComponent.vue';

// Documentos electrónicos
import DocumentosElectronicosComponent from  './pages/DocumentosElectronicos/DocumentoComponent.vue';
import DocumentosValidatorComponent from  './pages/DocumentosElectronicos/DocumentosValidatorComponent.vue';
import RotuleComponent from  './pages/correspondence/RotuleComponent.vue';

//Componentes para el modulo de encuenstas
import SliderComponent from "./pages/citizen_poll/SliderComponent.vue";
import RequestNeed from "./pages/maintenance/RequestNeedComponent.vue";
import ExternalProviderRequestForm from "./pages/maintenance/ExternalProviderRequestForm.vue";
import DynamicListEditableComponent from "./pages/maintenance/DynamicListEditableComponent.vue";
import WidgetComponentFilter from "./pages/maintenance/WidgetComponentFilter.vue";


// Importa las paginas del modulo de proceso contractual
import ControlPanelPage from "./pages/contractual_process/ControlPanelPage.vue";
import AlternativeInvestmentBudget from "./pages/contractual_process/AlternativeInvestmentBudget.vue";
import NoveltiesPaa from "./pages/contractual_process/NoveltiesPaa.vue";
import AssessNeedsPaa from "./pages/contractual_process/AssessNeedsPaa.vue";
import ApproveCallPaa from "./pages/contractual_process/ApproveCallPaa.vue";
import EvaluateBudget from "./pages/contractual_process/EvaluateBudget.vue";
import ReportMissingProcesses from "./pages/contractual_process/ReportMissingProcesses.vue";
import RequestModificationPaa from "./pages/contractual_process/RequestModificationPaa.vue";
import ProcessModificationRequestPaa from "./pages/contractual_process/ProcessModificationRequestPaa.vue";
import ChangeVersionPaa from "./pages/contractual_process/ChangeVersionPaa.vue";
import PaaProcessAttachment from "./pages/contractual_process/PaaProcessAttachment.vue";
import PaaVersion from "./pages/contractual_process/PaaVersion.vue";

//Componentes para el modulo de hojas de vida
import Approve from "./pages/Workhistories/Approve.vue";

// Componentes para el modulo de mantenimientos
import AssetsCreate from "./pages/maintenance/AssetsCreate.vue";
import ImportPartsProviderContracts from "./pages/maintenance/ImportPartsProviderContracts.vue";
import ImportActivitiesProviderContracts from "./pages/maintenance/ImportActivitiesProviderContracts.vue";
import TableCostItem from "./pages/maintenance/TableCostItem.vue";
import InputData from "./pages/maintenance/InputData.vue";
import InputDate from "./pages/maintenance/InputDate.vue";
import InputOperation from "./pages/maintenance/InputOperation.vue";
import MessageWarning from "./pages/maintenance/MessageWarning.vue";
import FormExecution from "./pages/maintenance/FormExecution.vue";
import InputDynamic from "./pages/maintenance/InputDynamic.vue";
import VehicleFuelsCreate from "./pages/maintenance/VehicleFuelsCreate.vue";
import OilsCreate from "./pages/maintenance/OilsCreate.vue";
import Auto from "./pages/maintenance/AutoCompleteVehicleFuel.vue";
import DynamicTableComponent from "./pages/maintenance/DynamicTableComponent.vue";

import HourMilitary from "./pages/maintenance/HourMilitary.vue";
import FormIndicator from "./pages/maintenance/FormIndicator.vue";
import FormDataAnalytics from "./pages/maintenance/FormDataAnalytics.vue";
import InputOperationChange from "./pages/maintenance/InputOperationChange.vue";
import InputSearch from "./pages/maintenance/InputSearch.vue";
import FormTireInformationComponent from "./pages/maintenance/FormTireInformationComponent.vue";
import FormTireWearsComponent from "./pages/maintenance/FormTireWearsComponent.vue";
import ChartComponentAnalytics from "./pages/maintenance/ChartComponentAnalytics.vue";
import DynamicListChangeComponent from "./components/core/DynamicListChangeComponent.vue";

//Componentes del modulo del leca
import SelectCheckLecaComponent from "./pages/Leca/SelectCheckLecaComponent.vue";
import DatePickerLecaComponent from "./pages/Leca/DatePickerLecaComponent.vue";
import ShowInformationComponent from "./pages/Leca/ShowInformationComponent.vue";

import AluminioEnsayoComponent from "./pages/LecaEnsayos/AluminioEnsayoComponent.vue";
import AluminioPanelComponent from "./pages/LecaEnsayos/AluminioPanelComponent.vue";
import FluoruroPanelComponent from "./pages/LecaEnsayos/FluoruroPanelComponent.vue";
import FluoruroEnsayoComponent from "./pages/LecaEnsayos/FluoruroEnsayoComponent.vue";
import SulfatoPanelComponent from "./pages/LecaEnsayos/SulfatoPanelComponent.vue";
import SulfatoEnsayoComponent from "./pages/LecaEnsayos/SulfatoEnsayoComponent.vue";
import SolidosDisPanelComponent from "./pages/LecaEnsayos/SolidosDisPanelComponent.vue";
import SolidosDisEnsayoComponent from "./pages/LecaEnsayos/SolidosDisEnsayoComponent.vue";
import SolidosSecosPanelComponent from "./pages/LecaEnsayos/SolidosSecosPanelComponent.vue";
import SolidosSecosEnsayoComponent from "./pages/LecaEnsayos/SolidosSecosEnsayoComponent.vue";
import AcidezPanelComponent from "./pages/LecaEnsayos/AcidezPanelComponent.vue";
import AlcalinidadEnsayoComponent from "./pages/LecaEnsayos/AlcalinidadEnsayoComponent.vue";
import AcidezEnsayoComponent from "./pages/LecaEnsayos/AcidezEnsayoComponent.vue";
import AlcalinidadPanelComponent from "./pages/LecaEnsayos/AlcalinidadPanelComponent.vue";

import MicrobiologicoEnsayoComponent from "./pages/LecaEnsayos/MicrobiologicoEnsayoComponent.vue";
import MicrobiologicoPanelComponent from "./pages/LecaEnsayos/MicrobiologicoPanelComponent.vue";
import HeterotroficasPanelComponent from "./pages/LecaEnsayos/HeterotroficasPanelComponent.vue";
import HeterotroficasEnsayoComponent from "./pages/LecaEnsayos/HeterotroficasEnsayoComponent.vue";

import CloruroPanelComponent from "./pages/LecaEnsayos/CloruroPanelComponent.vue";
import CloruroEnsayoComponent from "./pages/LecaEnsayos/CloruroEnsayoComponent.vue";
import CalcioEnsayoComponent from "./pages/LecaEnsayos/CalcioEnsayoComponent.vue";
import CalcioPanelComponent from "./pages/LecaEnsayos/CalcioPanelComponent.vue";
import CloroEnsayoComponent from "./pages/LecaEnsayos/CloroEnsayoComponent.vue";
import CloroPanelComponent from "./pages/LecaEnsayos/CloroPanelComponent.vue";
import DurezaEnsayoComponent from "./pages/LecaEnsayos/DurezaEnsayoComponent.vue";
import DurezaPanelComponent from "./pages/LecaEnsayos/DurezaPanelComponent.vue";
import TurbidezEnsayoComponent from "./pages/LecaEnsayos/TurbidezEnsayoComponent.vue";
import TurbidezPanelComponent from "./pages/LecaEnsayos/TurbidezPanelComponent.vue";
import PhEnsayoComponent from "./pages/LecaEnsayos/PhEnsayoComponent.vue";
import PhPanelComponent from "./pages/LecaEnsayos/PhPanelComponent.vue";
import ConductividadEnsayoComponent from "./pages/LecaEnsayos/ConductividadEnsayoComponent.vue";
import ConductividadPanelComponent from "./pages/LecaEnsayos/ConductividadPanelComponent.vue";
import SustanciasEnsayoComponent from "./pages/LecaEnsayos/SustanciasEnsayoComponent.vue";
import SustanciasPanelComponent from "./pages/LecaEnsayos/SustanciasPanelComponent.vue";
import ColorEnsayoComponent from "./pages/LecaEnsayos/ColorEnsayoComponent.vue";
import ColorPanelComponent from "./pages/LecaEnsayos/ColorPanelComponent.vue";
import OlorEnsayoComponent from "./pages/LecaEnsayos/OlorEnsayoComponent.vue";
import OlorPanelComponent from "./pages/LecaEnsayos/OlorPanelComponent.vue";
import inputNewConsecutive from "./pages/Leca/reportManagement/inputNewConsecutive.vue";
import CreateReportComponent from "./pages/Leca/reportManagement/CreateReportComponent.vue";

import InputDisabledComponent from './components/core/InputDisabledComponente.vue';

import EspectroPanelComponent from "./pages/LecaEnsayos/EspectroPanelComponent.vue";
import SelectListComponent from "./pages/Leca/SelectListComponent.vue";
// Calidad
import DocumentoCalidadComponent from  './pages/Calidad/DocumentoCalidadComponent.vue';
import MapaProcesosComponent from  './pages/Calidad/MapaProcesosComponent.vue';
import MapaProcesosPublicoComponent from  './pages/Calidad/MapaProcesosPublicoComponent.vue';
// Register
import RegisterComponent from  './pages/register/RegisterComponent.vue';
// Planes de mejoramiento
import DynamicListSelectComponent from './pages/improvement_plans/DynamicListSelectComponent.vue';
import InputRandomComponent from './pages/improvement_plans/InputRandomComponent.vue';
import FormTimeMessageComponent from './pages/improvement_plans/FormTimeMessageComponent.vue';
import CalendarEvaluationComponent from './pages/improvement_plans/CalendarEvaluationComponent.vue';
import DynamicListNeedComponent from './pages/maintenance/DynamicListNeedComponent.vue';

import RememberMailCitizen from './pages/intranet/RememberMailCitizen.vue';

const Lang = require('lang.js');

// Configuracion de archivo de traduccion
let lang = new Lang({
    messages: {
        'es.trans': require('../lang/es.json')
    },
    fallback: 'es'
});

Vue.use(VueSweetalert2); // Componente sweetalert2
Vue.use(VCalendar);// Componente calendario de fechas
Vue.use(VueFeedbackReaction); // Componente de rating emojis
Vue.use(VueCurrencyInput); // Componente formato moneda
// Vue.use(VueApexCharts);
Vue.use(wysiwyg, {});
Vue.use(VuePrintNB);
Vue.use(BootstrapVue);

Vue.component('crud', CrudComponent); // Componente de crud general
// Vue.component('v-paginate', Paginate) // Componente paginador para tablas
Vue.component('v-select', vSelect); // Componente selec2
Vue.component('vue-recaptcha', VueRecaptcha); // Componente reCaptcha de Google
Vue.component('example-component', ExampleComponent); // Componente de ejemplo
Vue.component('autocomplete', AutoCompleteFieldComponent); // Componente de campo autocompletado
Vue.component('cropper', Cropper); // Componente de recorte y subida de imagenes
Vue.component('cropper-image', CropperImageComponent); // Componente propio de recorte y subida de imagenes
Vue.component('date-picker', DatePickerComponent); // Componente de date picker general
Vue.component('dynamic-list', DynamicListComponent); // Componente de lista dinamica
Vue.component('dynamic-modal-form', DynamicModalFormComponent); // Componente de modal dinamico
Vue.component('select-check', SelectCheckCrudFieldComponent); // Componente de select o check general para crud
Vue.component('select-check-depend', SelectCheckCrudFieldComponentDepend); // Componente de select o check general para crud, dependiente de otro select
Vue.component('input-file', InputCrudFileComponent); // Componente para subir archivos al servidor
Vue.component('widget-counter', WidgetComponent); // Componente de select o check general para crud
Vue.component('widget-counter-v2', WidgetComponentV2); // Componente en V2 para mostrar las cards en el tablero de los trámites
Vue.component('add-list-autocomplete', AddListAutoCompleteComponent); // Componente para agregar a una lista desde un autocomplete
Vue.component('add-list-option', AddListOptionComponent); // Componente para agregar a una lista desde un autocomplete
Vue.component('execution-from-action', ExecutionFromActionComponent); // Componente para ejecutar una ruta desde una accion
Vue.component('text-area-editor', TextAreaEditorComponent); // Componente mostrar un textarea con texto enriquecido
Vue.component('multiselect', VueMultiselect);
Vue.component('multiselect-component', MultiselectComponent);
Vue.component('calendar-event', CalendarEventComponent); // Componente para agregar a una lista desde un autocomplete
Vue.component('send-petition-message', SendPetitionMessageComponent);//Envia una peticion al servidor y hace una confirmacion con sweetalert2 y actualiza el elemento que se afecte
Vue.component('table-component', TableComponent);
Vue.component('table-column', TableColumn);
Vue.component('star-rating', StarRating); // Componente de estrellas para crud general
Vue.component('alert-confirmation',AlertConfirmationComponent) // Componente para mostrar una alerta de confirmacion
Vue.component('storage-capacity',CapacityComponent) // Componente para mostrar el espacio y la cantidad de usuarios
Vue.component("dynamic-list-change", DynamicListChangeComponent)

Vue.component('viewer-attachement',ViewerAtthachementComponent) // Componente para mostrar una alerta de confirmacion
Vue.component("import-parts-provider-contracts", ImportPartsProviderContracts); // Componente importar los repuestos de los contratos de proveedores de mantenimientos
Vue.component(
    "import-activities-provider-contracts",
    ImportActivitiesProviderContracts
); // Componente importar los repuestos de los contratos de proveedores de mantenimientos


Vue.component('chat-gpt-component',ChatGptComponent) // Componente para integrar inteligencia artificial

Vue.component('sign-to-image',SignToImageComponent) // Componente para mostrar una alerta de confirmacion
Vue.component('sign-external-component',SignExternalComponent) // Componente para mostrar una alerta de confirmacion
Vue.component('annotations-general',AnnotationsGeneralComponent) // Componente de anotaciones
Vue.component('viewer-public',ViewerPublic) // Componente para mostrar una alerta de confirmacion
Vue.component('expedientes-general',ExpedientesGeneralComponent) // Componente de expedienetes
Vue.component('documentos-expediente-validator',DocumentosExpedienteValidatorComponent) // Componente de expedienetes

// Paginas
Vue.component('edit-profile-page', EditProfilePage); // Componente de select o check general para crud
// Vue.component('apexchart', VueApexCharts);
Vue.component('chart-component', ChartComponent); // Componente de select o check general para crud

Vue.component('verte', Verte); // Componente de select o check general para crud

Vue.component('holiday-calendar', HolidayCalendarsComponent); // Componente de select o check general para crud
Vue.component('colour-picker', ColourPickerComponent); // Componente de select o check general para crud

//Intranet encuestas
Vue.component('intranet-poll-answer', PollAnswers); // Componente de respuesta de encuestas


Vue.component('search-universal', SearchUniversal);
Vue.component('search-result-universal', SearchResultUniversal);
Vue.component('vue-drag-resize', VueDragResize);


Vue.component('correspondence-internal', InternalComponent);
Vue.component('correspondence-external', ExternalComponent);
Vue.component('annotation-and-read', AnnotationAndRead);
Vue.component('correspondence-validator', CorrespondenceValidatorComponent);
Vue.component('date-piker-multiple', DatePikerMultipleComponent);

Vue.component('external-received', ExternalReceived);

Vue.component('share-correspondence-user', ShareCorrespondenceUser);

Vue.component('pqr-component', PQRComponent); // Componente de PQRS
Vue.component('metadatos-component', MetadatosComponent);
Vue.component('form-criterios-busqueda', FormCriteriosBusqueda);
Vue.component('criterios-busqueda-save', CriteriosBusquedaSave);


// Componentes de mesa de ayuda
Vue.component('assets-tics', AssetsTics); // Componente de select o check general para crud
Vue.component('add-technical-tic', AddTechnicalTicComponent); // Componente de select o check general para crud
Vue.component('tic-ht-request-histories', RequestTicHistories); // Componente de select o check general para crud
Vue.component('tic-ht-asset-maintenance', TicHTAssetMaintenance); // Componente de select o check general para crud
Vue.component('ht-tic-satisfaction-poll', HtTicSatisfactionPoll); // Componente de select o check general para crud
Vue.component('ht-tic-knowledge-base', HtTicKnowledgeBase); // Componente de select o check general para crud

// Dashboard
Vue.component('dashboard', DashboardComponent);

// Documentos electrónicos
Vue.component('documentos-electronicos', DocumentosElectronicosComponent);
Vue.component('documentos-validator', DocumentosValidatorComponent);
Vue.component('rotule-component', RotuleComponent);

Vue.component("widget-counter-filter-maitenance", WidgetComponentFilter); // Componente de select o check general para crud
Vue.component("date-picker-not-hours", DatePickerNotHoursComponent);

// Componentes de modulo de proceso contractual
Vue.component("control-panel-page", ControlPanelPage); // Componente de select o check general para crud
Vue.component("alternative-investment-budget", AlternativeInvestmentBudget); // Componente de la parte de presupuesto de fichas de inversion de paa
Vue.component("novelties-paa", NoveltiesPaa); // Componente para visuarlizar las necesidades de paa
Vue.component("assess-needs-paa", AssessNeedsPaa); // Componente para evaluar las necesidades de paa
Vue.component("approve-call-paa", ApproveCallPaa); // Componente para aprobar la convocatoria paa
Vue.component("pc-evaluate-budget", EvaluateBudget); // Componente para aprobar la convocatoria paa
Vue.component("report-missing-processes", ReportMissingProcesses);
Vue.component("request-modification-paa", RequestModificationPaa);
Vue.component(
    "process-modification-request-paa",
    ProcessModificationRequestPaa
);
Vue.component("change-version-paa", ChangeVersionPaa);
Vue.component("paa-process-attachment", PaaProcessAttachment);
Vue.component("paa-version", PaaVersion);

//Componente hoja de vida
Vue.component("approve-request", Approve);

Vue.component("assets-create", AssetsCreate); // Componente para el proceso de creación de activos, del módulo de mantenimientos
Vue.component("chart-component-analytics", ChartComponentAnalytics);

Vue.component("assets-create", AssetsCreate); // Componente para el proceso de creación de activos, del módulo de mantenimientos
Vue.component("vehicle-fuels-create", VehicleFuelsCreate); // Componente para el proceso de creación de gestion de combustibles de vehiculos, del módulo de mantenimientos
Vue.component("auto", Auto); // Componente de campo autocompletado para el componente de gestion de combustibles
Vue.component("input-data", InputData);
Vue.component("input-data-date", InputDate);
Vue.component("table-cost-item", TableCostItem);

Vue.component("form-execution", FormExecution);
Vue.component("input-operation", InputOperation);
Vue.component("message-warning", MessageWarning);
Vue.component("input-dynamic", InputDynamic);
Vue.component("hour-military", HourMilitary);
Vue.component("form-indicator", FormIndicator);
Vue.component("form-data-analytics", FormDataAnalytics);
Vue.component("input-operation-change", InputOperationChange);
Vue.component("input-search", InputSearch);
Vue.component("form-tire-information", FormTireInformationComponent);
Vue.component("form-tire-wears-information", FormTireWearsComponent);
Vue.component("external-provider-form", ExternalProviderRequestForm);
Vue.component("oils-create", OilsCreate);
Vue.component("dynamic-table", DynamicTableComponent);
//Componente de slider para las encuestras
Vue.component("slider-show", SliderComponent);

// Vue.component('delete-vehicle', DeleteVehicle);
Vue.component("star-rating", StarRating); // Componente de estrellas para crud general

//Componentes del modulo del leca


// componentes de administración de informes.
Vue.component("input-new-consecutive", inputNewConsecutive); // Componente de date picker para el leca
Vue.component("create-report-form", CreateReportComponent); // Componente de date picker para el leca

Vue.component("date-picker-leca", DatePickerLecaComponent); // Componente de date picker para el leca

Vue.component("information-component", ShowInformationComponent);

//ENSAYOS LECA
//Ensayos de espectrofotometricos
Vue.component("select-check-leca", SelectCheckLecaComponent); // Componente de select o check general para crud
Vue.component("aluminio-ensayo", AluminioEnsayoComponent);
Vue.component("aluminio-panel", AluminioPanelComponent);
Vue.component("espectro-panel", EspectroPanelComponent);
Vue.component("fluoruro-panel", FluoruroPanelComponent);
Vue.component("fluoruro-ensayo", FluoruroEnsayoComponent);
Vue.component("sulfatos-panel", SulfatoPanelComponent);
Vue.component("sulfatos-ensayo", SulfatoEnsayoComponent);
Vue.component("disueltos-panel", SolidosDisPanelComponent);
Vue.component("disueltos-ensayo", SolidosDisEnsayoComponent);
Vue.component("secos-panel", SolidosSecosPanelComponent);
Vue.component("secos-ensayo", SolidosSecosEnsayoComponent);

//Ensayos de microbiologicos
Vue.component("microbiologicos-ensayo", MicrobiologicoEnsayoComponent);
Vue.component("microbiologicos-panel", MicrobiologicoPanelComponent);
Vue.component("heterotroficas-panel", HeterotroficasPanelComponent);
Vue.component("heterotroficas-ensayo", HeterotroficasEnsayoComponent);

Vue.component("select-list-check", SelectListComponent);

//Ensayo alcalinidad
Vue.component("alcalinidad-ensayo", AlcalinidadEnsayoComponent);
//Ensayo acidez
Vue.component("acidez-ensayo", AcidezEnsayoComponent);
Vue.component("acidez-panel", AcidezPanelComponent);
//Ensayo cloruro
Vue.component("cloruro-ensayo", CloruroEnsayoComponent);
Vue.component("cloruro-panel", CloruroPanelComponent);
//Ensayo calcio
Vue.component("calcio-ensayo", CalcioEnsayoComponent);
Vue.component("calcio-panel", CalcioPanelComponent);

//Ensayo cloro
Vue.component("cloro-ensayo", CloroEnsayoComponent);
Vue.component("cloro-panel", CloroPanelComponent);

//Ensayo dureza
Vue.component("dureza-ensayo", DurezaEnsayoComponent);
Vue.component("dureza-panel", DurezaPanelComponent);

//Ensayo dureza
Vue.component("turbidez-ensayo", TurbidezEnsayoComponent);
Vue.component("turbidez-panel", TurbidezPanelComponent);

Vue.component("ph-ensayo", PhEnsayoComponent);
Vue.component("ph-panel", PhPanelComponent);

Vue.component("conductividad-ensayo", ConductividadEnsayoComponent);
Vue.component("conductividad-panel", ConductividadPanelComponent);

Vue.component("sustancias-ensayo", SustanciasEnsayoComponent);
Vue.component("sustancias-panel", SustanciasPanelComponent);

//Ensayo lectura directa
Vue.component("color-ensayo", ColorEnsayoComponent);
Vue.component("color-panel", ColorPanelComponent);

Vue.component("olor-ensayo", OlorEnsayoComponent);
Vue.component("olor-panel", OlorPanelComponent);

Vue.component("alcalinidad-panel", AlcalinidadPanelComponent);

Vue.component("table-component", TableComponent);
Vue.component("table-column", TableColumn);
Vue.component("request-need", RequestNeed);
Vue.component("dynamic-list-editable", DynamicListEditableComponent);
Vue.component("dynamic-list-needs", DynamicListNeedComponent);

Vue.component("btn-create-request", BtnCreateRequest); // Componente de select o check general para crud
Vue.component("widget-counter-funtional", WidgetComponentFuntional); // Componente de select o check general para crud
// Calidad
Vue.component('documentos-calidad', DocumentoCalidadComponent);
Vue.component('mapa-procesos-calidad', MapaProcesosComponent);
Vue.component('mapa-procesos-calidad-publico', MapaProcesosPublicoComponent);
// Register
Vue.component('register-component', RegisterComponent);
// Planes de mejoramiento
Vue.component('dynamic-list-select', DynamicListSelectComponent);
Vue.component('input-random', InputRandomComponent);
Vue.component('form-time-messages', FormTimeMessageComponent);
Vue.component('calendar-evaluation', CalendarEvaluationComponent);

Vue.component('remember-citizen-mail',RememberMailCitizen) // Componente para consultar data y mostrarla en un input

// Filtro para convertir texto a mayuscula
Vue.filter('uppercase', (value: string) => {
	return value.toUpperCase();
});
// Filtro para convertir texto a minuscula
Vue.filter('lowercase', (value: string) => {
	return value.toLowerCase();
});
// Filtro para convertir la primera letra en mayuscula y el resto en minuscula
Vue.filter('capitalize', (value: string) => {
    return value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
});
// Filtro para la traduccion
Vue.filter('trans', (...value: string[]) => {
    return lang.get(...value);
});

// Necesidades Mantenimiento
Vue.component("input-disabled", InputDisabledComponent);
Vue.component("input-check", InputDataComponent); // Componente de inputo check general para crud




Vue.prototype.$log = console.log;

new Vue({
    el: '#app',
    data() {
        return {
            lang: lang,
            query: '',
            dataForm: {},
            dataShow: {},
            dataList: [],
            searchFields: {},
            // Controla si la sesión del usuario esta activa o no
            sessionExpired: false,
            // Controla e informa si la ventana de imprimir esta abierta o no
            printOpened: false
        };
    },
    created() {
        // Obtiene la URL actual de la página
        let url = window.location.href;

        setInterval( function(){
            /**
             * Valida la url de la página, si es la indicada en la condición, no valida la sesión;
             * si la variable sessionExpired es true, la sesión a expirado o es inactiva.
             */


            if(url.indexOf("firmar") == -1 && url.indexOf("validate-correspondence-received-email") == -1 && url.indexOf("validate-correspondence-external-email") == -1  && url.indexOf("validar-codigo") == -1 && url.indexOf("register") == -1 && url.indexOf("p-q-r-s-ciudadano-anonimo") == -1 && url.indexOf("password") == -1 && !this.sessionExpired && url.indexOf("validar-correspondence-external") == -1 && url.indexOf("validar-correspondence-internal") == -1 && url.indexOf("search-pqrs-ciudadano") == -1 && url.indexOf("validar-documento-electronico") == -1  && url.indexOf("survey-satisfaction-pqrs") == -1 && url.indexOf("maintenance/request-need-orders?rn=MsQs==") == -1 && url.indexOf("watch-archives") == -1 && url.indexOf("usuarios-externos") == -1 && url.indexOf("documentos-expedientes-usuario-externo") == -1 && url.indexOf("addition-spare-part-activities") == -1) {
                this.checkAuth();
            }
        }.bind(this), 60000);
    },
    methods: {
        // Valida el estado de la sesión del usuario
        checkAuth() {
            // Realiza la petición para validar la sesión
            axios.get('/check-session')
            .then((res) => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                // Si data = true, la sesión esta activa, de lo contrario, esta inactiva
                if(!dataDecrypted["data"]) {
                    this.sessionExpired = true;
                    // Muestra un mensaje al usuario indicando que la sesión ha expirado
                    this.$swal({
                        icon: "info",
                        html: "Tu sesión ha expirado. Por favor, inicia sesión de nuevo",
                        allowOutsideClick: false,
                        confirmButtonText: this.lang.get('trans.Accept')
                    }).then(() => {
                        // Redirecciona al usuario a la página de login, para iniciar sesión nuevamente
                        window.location.href = "/login";
                    });
                }
            })
            .catch((err) => {
                console.log("Error validando la sesión del usuario: "+err);
            });
        },

        /**
         * Callback que se ejecuta antes de abrir la ventana de impresión.
         * Marca el indicador `printOpened` como verdadero.
         */
        beforeOpenCallback() {
            this.printOpened = true;
        },

        /**
         * Callback que se ejecuta cuando se abre la ventana de impresión.
         */
        openCallback() {
        },

        /**
         * Callback que se ejecuta al cerrar la ventana de impresión.
         * Marca el indicador `printOpened` como falso.
         */
        closeCallback() {
            this.printOpened = false;
        }
    }
});
