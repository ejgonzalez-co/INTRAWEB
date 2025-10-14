<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirección Google OAuth</title>
</head>
<body>

    <script>
        // Capturar el fragmento de la URL (todo lo que está después de #)
        const hash = window.location.hash.substring(1);
        const urlParams = new URLSearchParams(hash);
        const token = urlParams.get('access_token');
        // Si window.opener existe, significa que la ventana fue abierta por otra ventana o pestaña
        if (window.opener) {
            window.opener.postMessage(
                { action: 'refreshIframe', token: token },
                "{{ $appUrl }}"  // Usa la variable del env
            );
        }
        // Cierra la ventana actual
        window.close();
    </script>

</body>
</html>
