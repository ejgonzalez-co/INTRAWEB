<template>
    <div>
        <FullCalendar :options="calendarOptions"></FullCalendar>
    </div>
</template>
<style>
.fc .fc-event,
.fc a.fc-event {
    background-color: #3788d8;
    color: #ffffff;
}

.fc .fc-list-event:hover td {
    background-color: #3074b7;
    color: #ffffff;
}
</style>
<script lang="ts">
import { Component, Prop, Vue, Watch } from "vue-property-decorator";

import { Locale } from "v-calendar";

const locale = new Locale();

import esLocale from "@fullcalendar/core/locales/es";
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import bootstrapPlugin from "@fullcalendar/bootstrap";
import listPlugin from "@fullcalendar/list";

/**
 * Componente de fullcalendar
 *
 * @author Kleverman Salazar Florez. - Sep. 25 - 2023
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
     * @author Kleverman Salazar Florez. - Sep. 25 - 2023
     * @version 1.0.0
     */

    constructor() {
        super();

        this.calendarOptions = {
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
            },
            timeZone: "America/Bogota",
            themeSystem: "bootstrap",
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
            initialView: "",
            eventAfterAllRender: function(view) {
                if (view.type == "listWeek") {
                    console.log(view.type + " change colspan");
                    console.log(view);
                    var tableSubHeaders = jQuery("td.fc-widget-header");
                    console.log(tableSubHeaders);
                    var numberOfColumnsItem = jQuery("tr.fc-list-item");
                    var maxCol = 0;
                    var arrayLength = numberOfColumnsItem.length;
                    for (var i = 0; i < arrayLength; i++) {
                        maxCol = Math.max(
                            maxCol,
                            numberOfColumnsItem[i].children.length
                        );
                    }
                    console.log("number of items : " + maxCol);
                    tableSubHeaders.attr("colspan", maxCol);
                }
            },
            eventDidMount: function(info) {
                $(info.el).tooltip({
                    html: true,
                    title:
                        "<h5>" +
                        info.event.title +
                        "</h5><h6>" +
                        info.event.extendedProps.initial_date +
                        " - " +
                        info.event.extendedProps.end_hour +
                        "</h6><p>" +
                        info.event.extendedProps.description +
                        "</p>",
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
        const events = this.dataList.map(element => {
            let initial_date = locale.format(
                element.evaluation_start_date,
                "YYYY-MM-DD"
            );

            let event = {
                title: element.evaluation_name,
                start: initial_date + " " + element.evaluation_start_time,
                end: initial_date + " " + element.evaluation_end_time,
                description: element.objective_evaluation,
                extendedProps: {
                    initial_date: element.evaluation_start_time,
                    end_hour: element.evaluation_end_time,
                    status: element.state
                }
            };

            return event;
        });

        this.calendarOptions.initialView = events;
        this.calendarOptions.events = events;
    }
}
</script>
