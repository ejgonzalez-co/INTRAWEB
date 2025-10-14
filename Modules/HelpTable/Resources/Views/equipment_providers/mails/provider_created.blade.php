@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

Cordial saludo {{ $data['fullname'] }},
La Dirección Ti de las Empresas Públicas de Armenia (EPA) le ha asignado las credenciales donde se realizan los registros de los mantenimientos de los equipos.


Pin: {{ $data['pin'] }}

Contraseña: {{ $data['decrypted_password'] }}

Acceder: <a href="https://intraepa.gov.co/">url de la vista</a>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
        @endcomponent
    @endslot
@endcomponent
