@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

Cordial saludo, {{ $data["provider_name"] }},

El supervisor del contrato ha devuelto la solicitud de adición de actividades y/o repuestos relacionada con la identificación de necesidad N.° {{ $data["consecutive"] }}.
Por favor, ingrese al sistema para consultar y validar los ítems aprobados.

📝 Observación del administrador: {{ $data["observation"] }} <br>

🔗 Acceso al sistema (proveedores): <a href="{{ $data["url_register"] }}">Ir a Intraweb</a> <br>

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
        @endcomponent
    @endslot
@endcomponent
