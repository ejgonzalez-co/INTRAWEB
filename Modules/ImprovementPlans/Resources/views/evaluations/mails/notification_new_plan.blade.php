
@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot
    
    @slot('htmlContent')
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
                <p>Cordial saludo {{ $data["name"] ? $data["name"] : 'funcionario' }},</p>
                
                <p>El día {{ date('Y-m-d', strtotime($data["evaluation_end_date"] ?? '')) }} finalizó la evaluación {{ $data["type_evaluation"] ?? '' }} - {{ $data["evaluation_name"] ?? '' }}. 
                    <br>A partir de los resultados de la evaluación, se ha creado el plan de mejoramiento número: {{ $data["no_improvement_plan"] ?? '' }} con las siguientes oportunidades de mejora:</p>
                
                {!! $data["mensaje"] ?? '' !!}
            </div>
            @component('mail::button', ['url' => config('app.url')])
            Por favor, ingrese al sistema y comience a crear su plan de mejoramiento
            @endcomponent
            
        </div>
    @endslot
    
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo. Le pedimos que no responda a este mensaje.
        @endcomponent
    @endslot
@endcomponent
