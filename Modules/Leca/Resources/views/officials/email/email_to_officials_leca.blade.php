@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    Un cordial saludo, el funcionario {{ $data['name'] }} le ha sido asignado desde el aplicativo de gestión de estudios del laboratorio LECA de las empresas públicas de Armenia. Sus datos de ingreso son:

    PIN: {{ $data['pin'] }}
    Contraseña: {{ $data['password'] }}

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Por favor haga caso omiso a éste mensaje, ya que el sistema de gestion de estudios del laboratorio LECA se encuentra en etapa de implementacíon y pruebas.
            Para cualquier información comuníquese al Tel: 7499121
        @endcomponent
    @endslot
@endcomponent
