@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

Cordial saludo, {{ $data["admin_name"] }},

El proveedor {{ $data["provider_name"] }} ha completado la asignación de costos para la solicitud de adición asociada a la identificación de necesidad N.° {{ $data["consecutive"] }}. La solicitud se encuentra ahora en estado "En trámite" y está disponible para su revisión y gestión.

🔍 Por favor, ingrese al sistema para continuar con el proceso correspondiente.

🔗 Acceso al sistema: <a href="{{ $data["url_register"] }}">Ir a Intraweb</a>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
        @endcomponent
    @endslot
@endcomponent
