<div><strong>Anotaciones: </strong></div>
        
<section id="timeline">
<article  v-for="(anotacion,key) in dataList">
    <div class="inner">
        <span class="date">
            <span class="day"> @{{ anotacion.date_format.day }}</span>
            <span class="month"> @{{ anotacion.date_format.month }}</span>
            <span class="year"> @{{ anotacion.date_format.year }}</span>
          </span>
          
          <h2 style="display: flex; align-items: center;">
            {{-- <strong style="background: rgba(0, 0, 0, 0.17); padding: 10px;">@{{ key+1 }}</strong> --}}
            <div style="width: 40px; height: 40px; overflow: hidden; border-radius: 50%; margin-right: 10px;">
                <img v-if="anotacion.users.url_img_profile !== '' && anotacion.users.url_img_profile !== 'users/avatar/default.png'" :src="'{{ asset('storage') }}/'+anotacion.users.url_img_profile" alt="" style="width: 100%; height: auto;">
                <img v-else src="{{ asset('assets/img/user/profile.png') }}" alt="" style="width: 100%; height: auto;">
            </div>
            <span style="width: 80%;">@{{ anotacion.users_name }}</span>
        </h2>
        
      <p>
          <strong>Fecha y hora:</strong> @{{ anotacion.date_format.day }} de @{{ anotacion.date_format.monthcompleto }} de @{{ anotacion.date_format.year }} @{{ anotacion.date_format.hour }}<br>
          <strong>Anotación:</strong> @{{ anotacion.content }}<br>
          <strong>Documento:</strong>
            <span v-if="anotacion.attached">
                <span v-for="attached in anotacion.attached.split(',')" style="margin-left: -15px;"><a class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+attached" target="_blank">Ver adjunto</a><br/></span>
            </span>
            <span v-else>
                <span>No tiene adjuntos</span>
            </span>
            <br>

      </p>
    </div>
  </article>
</section>

{{-- 
<div class="table-responsive">
    <table-component
        id="externalAnnotations-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="externalAnnotations"
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
        <table-column show="users_name" label="Funcionario"></table-column>

        <table-column show="content" label="Contenido"></table-column>
       
        <table-column show="attached" label="Adjuntos" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">

                <div v-if="row.attached">
                    <span v-for="attached in row.attached.split(',')" style="margin-left: -15px;"><a class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+attached" target="_blank">Ver adjunto</a><br/></span>
                 </div>
           </template>
        </table-column>

    </table-component>
</div> --}}