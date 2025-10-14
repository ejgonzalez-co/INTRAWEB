@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
# Estimado(a), {{ $data['name'] }}

Su solicitud ha sido marcada como cerrada.

---

**Número de ticket:** {{ $data['request']['id'] }}  
**Asunto:** {{ $data['request']['affair'] }}  
**Fecha de cierre:** {{ $data['request']['closing_date'] }}  

---

Para ayudarnos a mejorar, le invitamos a completar una breve encuesta de 
satisfacción:

@component('mail::button', ['url' => config('app.url')])
Acceder al sistema
@endcomponent

Agradecemos su confianza y quedamos atentos a cualquier nueva solicitud. 

{{-- El caso con número {{ $data['request']['id'] }}, fue atendido por el funcionario {{ $data['request']['assigned_by_name'] }}, con la siguiente observación:  "<strong>{{ strip_tags($data['tracing']) }}</strong>". Recuerde ingresar y completar la encuesta de satisfacción para poder crear nuevos casos de soporte.

   @component('mail::button', ['url' => config('app.url')])
      Ingrese al sistema de Mesa de ayuda para mayor información.
   @endcomponent --}}

  

   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent
