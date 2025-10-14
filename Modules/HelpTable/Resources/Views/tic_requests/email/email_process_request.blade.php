@component('mail::layout')

{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        {{ config('app.name') }}
    @endcomponent
@endslot
# Estimado(a), {{ $data['user_created_name'] }},

Le informamos que la atención de su solicitud ha comenzado.
---

**Número de ticket:** {{ $data['id'] }}  
**Asunto:** {{ $data['affair'] }}  
**Fecha de inicio de atención :** {{ $data['date_attention'] }}  


---
Nuestro equipo técnico está trabajando en su requerimiento. 
Agradecemos su paciencia y comprensión. 

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        Este correo es de carácter informativo. Por favor, no responda a este mensaje.
    @endcomponent
@endslot

@endcomponent
