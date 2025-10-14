@php
    $ultima_configuracion = DB::table('configuration_general')->latest()->first();
    $storageUrl = asset('storage');
    $localUrls = ['http://127.0.0.1:8000/storage', 'http://localhost/storage']; // Lista de URLs locales
    $defaultLogoUrl = 'https://intraweb.seven.com.co/assets/img/default/intraweb_default.png'; // URL de la imagen predeterminada
@endphp

<tr>
    <td class="header" style="text-align: left;">
        <a href="{{ $url }}" style="text-decoration: none; color: #000000;">
            {{ $slot }}
        </a>
    </td>
    <td style="text-align: right;">
        {{-- <img src="{{ in_array($storageUrl, $localUrls) ? $defaultLogoUrl : $storageUrl.'/'.$ultima_configuracion->logo }}" style="max-width: none;width: 90px !important; margin-left: 10px;" alt="" /> --}}
    </td>
</tr>
