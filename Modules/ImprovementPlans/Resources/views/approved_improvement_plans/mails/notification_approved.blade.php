
@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

<div class="container">
    <div class="header">
        @php
            $logo = DB::table('pm_gestion_content')
                ->select('archive')
                ->where('name', '=', 'logo y creditaciones')
                ->first();
    
            $publicPath = Storage::url($logo->archive);
        @endphp
        <img src="{{ url($publicPath) }}" alt="Logo">
    </div>
    

    <div class="content">
        <p>Cordial saludo, estimado/a {{ $data["evaluated_name"] ? $data["evaluated_name"] : 'funcionario' }},</p>

        <p>Nos complace informarle que el plan de mejoramiento titulado: {{ $data["name_improvement_plan"] ?? '' }} ha sido aprobado. A partir de este momento, puede ingresar al sistema y cargar las evidencias correspondientes para su seguimiento.
            <br>
        Quedamos atentos a cualquier consulta o apoyo que pueda necesitar en este proceso.</p>
        
    </div>
    @component('mail::button', ['url' => config('app.url')])
    Por favor, ingrese al sistema y comience a crear su plan de mejoramiento
    @endcomponent
    
</div>

  {{-- Footer --}}
  @slot('footer')
  @component('mail::footer')
      Este correo es de tipo informativo. Le pedimos que no responda a este mensaje.
  @endcomponent
@endslot
@endcomponent
