@component('mail::layout')

{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        {{ config('app.name') }}
    @endcomponent
@endslot
{{-- @php dd($data); @endphp --}}
# Buen día, {{ $data['request']['assigned_user_name'] }},

Se le ha asignado una nueva solicitud desde la mesa de ayuda con la siguiente información:

---

**Número de solicitud:** {{ $data['request']['id'] }}  
**Asunto:** {{ $data['request']['affair'] }}  
**Descripción:** {{ $data['request']['description'] }}  
**Prioridad:** {{ $data['request']['priority_request_name'] }}  
**Fecha de creación:** {{ $data['request']['created_at'] }}  
**Usuario solicitante:** {{ $data['request']['assigned_by_name'] }}  
**Dependencia:** {{ $data['request']['dependency_name'] ?? 'No especificada' }}  
**Sede:** {{ $data['request']['location_name'] ?? 'No especificada' }}

---
Por favor, ingrese al sistema para gestionar esta solicitud a la mayor brevedad posible.


@component('mail::button', ['url' => config('app.url')])
Acceder al sistema
@endcomponent
Gracias por su atención y compromiso.

Saludos, {{ $data['request']['assigned_user_name'] }} Mesa de Ayuda – Soporte Técnico

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        Este correo es de carácter informativo. Por favor, no responda a este mensaje.
    @endcomponent
@endslot

@endcomponent
