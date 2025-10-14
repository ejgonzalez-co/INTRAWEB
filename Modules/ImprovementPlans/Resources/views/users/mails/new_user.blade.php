@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

Cordial saludo {{ $data["name"] ? $data["name"] : 'funcionario' }},

Le ha sido asignado un perfil en el sistema de Planes de mejoramiento. Por temas de seguridad y privacidad de la
información se requiere dar clic en el Botón <a href="{{ url('verify-account/' . $data['encrypted_mail']) }}">Crear contraseña</a>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
        @endcomponent
    @endslot
@endcomponent
