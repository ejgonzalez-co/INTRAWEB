@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

Cordial saludo, {{ $data["provider_name"] }},

La solicitud de adición de actividades y/o repuestos asociada a la identificación de necesidad N.° {{ $data["consecutive"] }} ha sido registrada por el administrador y se encuentra en estado "Asignación de costos".

📌 Se requiere que usted ingrese los costos correspondientes a los ítems solicitados para continuar con el proceso.

🔗 Acceso al sistema (proveedores): <a href="{{ $data["url_register"] }}">Ir a Intraweb</a> <br>

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
        @endcomponent
    @endslot
@endcomponent
