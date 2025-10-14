@component('mail::layout')

{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        {{ config('app.name') }}
    @endcomponent
@endslot

# Estimado(a), {{ $data['request']['user_created_name'] }}
Su requerimiento ha sido asignado a un técnico para su atención.

---

**Número de ticket:** {{ $data['request']['id'] }}  
**Asunto:** {{ $data['request']['affair'] }}  
**Descripción:** {{ $data['request']['description'] }}  
**Técnico asignado:** {{ $data['request']['assigned_user']['name'] }}  

---

En breve se iniciará el proceso de atención.  
Gracias por utilizar la Mesa de Ayuda.

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        Este correo es de carácter informativo. Por favor, no responda a este mensaje.
    @endcomponent
@endslot

@endcomponent
