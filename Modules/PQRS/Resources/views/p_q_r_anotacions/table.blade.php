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
            <span style="width: 80%;">@{{ anotacion.nombre_usuario }}</span>
        </h2>
        
      <p>
          <strong>Fecha y hora:</strong> @{{ anotacion.date_format.day }} de @{{ anotacion.date_format.monthcompleto }} de @{{ anotacion.date_format.year }} @{{ anotacion.date_format.hour }}<br>
          <strong>Anotación:</strong> @{{ anotacion.anotacion }}<br>
          {{-- <strong>Documento:</strong>
            <span v-if="anotacion.attached">
                <span v-for="attached in anotacion.attached.split(',')" style="margin-left: -15px;"><a class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+attached" target="_blank">Ver adjunto</a><br/></span>
            </span>
            <span v-else>
                <span>No tiene adjuntos</span>
            </span>
            <br> --}}

      </p>
    </div>
  </article>
</section>
