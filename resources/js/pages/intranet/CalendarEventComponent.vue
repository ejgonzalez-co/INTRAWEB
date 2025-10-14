<template>
   <div>
      <FullCalendar :options="calendarOptions"></FullCalendar>
   </div>
</template>
<style>
.fc .fc-event, .fc a.fc-event{
   background-color: #3788d8;
   color: #FFFFFF;
}

.fc .fc-list-event:hover td{
   background-color: #3074b7;
   color: #FFFFFF;
}
</style>
<script lang="ts">


   import { Component, Prop, Vue, Watch } from "vue-property-decorator";

   import * as bootstrap from 'bootstrap';

   import utility from '../../utility';

   import { Locale } from "v-calendar";

   const locale = new Locale();
   
   import esLocale from '@fullcalendar/core/locales/es';
   import FullCalendar from '@fullcalendar/vue'
   import dayGridPlugin from '@fullcalendar/daygrid'
   import timeGridPlugin from '@fullcalendar/timegrid'
   import interactionPlugin from '@fullcalendar/interaction'
   import bootstrapPlugin from '@fullcalendar/bootstrap';
   import listPlugin from '@fullcalendar/list';

   /**
    * Componente de fullcalendar
    *
    * @author Carlos Moisés Garcia T. - Sep. 04 - 2020
    * @version 1.0.0
    */
   @Component({
      components: {
         FullCalendar // make the <FullCalendar> tag available
      }
   })
   export default class CalendarEventComponent extends Vue {

      /**
       * Nombre del campo
       */
      // @Prop({type: String, required: true })
      // public nameField: string;

      @Prop()
      public dataList: any;

      public calendarOptions;


      public events;

      /**
       * Constructor de la clase
       *
       * @author Carlos Moisés Garcia T. - Sep. 04 - 2020
       * @version 1.0.0
       */

      constructor() {
         super();         

         this.calendarOptions = {
            headerToolbar: {
               left: 'prev,next today',
               center: 'title',
               right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            timeZone: 'America/Bogota',
            themeSystem: 'bootstrap',
            locale: esLocale,
            plugins: [
               dayGridPlugin,
               timeGridPlugin,
               interactionPlugin, // needed for dateClick
               bootstrapPlugin,
               listPlugin
            ],
            buttonIcons: true, // show the prev/next text
            // weekNumbers: true,
            // navLinks: true, // can click day/week names to navigate views
            // editable: true,
            // dayMaxEvents: true, // allow "more" link when too many events

            events: [],
            initialView: '',
            eventAfterAllRender : function( view ) {
               if (view.type == 'listWeek') {
                  var tableSubHeaders = jQuery("td.fc-widget-header");
                  var numberOfColumnsItem = jQuery('tr.fc-list-item');
                  var maxCol = 0;
                  var arrayLength = numberOfColumnsItem.length;
                  for (var i = 0; i < arrayLength; i++) {
                     maxCol = Math.max(maxCol,numberOfColumnsItem[i].children.length);
                  }
                  tableSubHeaders.attr("colspan",maxCol);
               }
            },
            eventDidMount: function(info) {
               $(info.el).tooltip({ 
                  html: true,
                  title: '<h5>'+info.event.title+'</h5><h6>'+info.event.extendedProps.initial_date +' - '+ info.event.extendedProps.end_hour+'</h6><p>'+info.event.extendedProps.description+'</p>',
                  placement: "top",
                  trigger: "hover",
                  container: "body"
               });
            }
      
            /* you can update a remote database when these fire:
            eventAdd:
            eventChange:
            eventRemove:
            */
         };
      }

      @Watch("dataList")
      public listEvents() {
         const events = this.dataList.map((element) => {

            let initial_date =  locale.format(element.initial_date, "YYYY-MM-DD");

            let event = {
               title: element.title,
               start: initial_date+" "+element.initial_hour,
               end: initial_date+" "+element.end_hour,
               description: element.description,
               extendedProps: {
                  initial_date: element.initial_hour,
                  end_hour: element.end_hour,
                  status: element.state
               },
            }

            return event;
         });

         this.calendarOptions.initialView = events;
         this.calendarOptions.events = events;
      }
   }
</script>