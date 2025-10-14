<?php
    $parametro = request()->c;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suscripción cancelada</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
</body>
<script>
    var appName = @json($appName);

    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '¡Alerta!',
            text: `¿Está seguro de que desea cancelar las notificaciones por correo electrónico enviadas por ${appName} a través del sistema Intraweb?`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No, mantener'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar swal de cargando
                Swal.fire({
                    title: 'Cargando...',
                    html: 'Por favor, espere un momento.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                // Petición a una ruta si se confirma la acción
                fetch('/cancel-subscription-process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: '{{ $parametro }}' })
                })
                .then(response => response.json())
                .then(data => {
                    // Cerrar swal de cargando
                    Swal.close();
                    // Manejo de la respuesta
                    if (data.success) {
                        Swal.fire({
                            title: 'Cancelado',
                            text: 'Las notificaciones han sido canceladas.',
                            icon: 'success'
                        }).then(() => {
                            window.close();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al cancelar las notificaciones.',
                            icon: 'error'
                        }).then(() => {
                            window.close();
                        });
                    }
                })
                .catch(error => {
                    // Cerrar swal de cargando
                    Swal.close();

                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al cancelar las notificaciones.',
                        icon: 'error'
                    }).then(() => {
                        window.close();
                    });
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Cierra la pestaña si se cancela la acción
                window.close();
            }
        });
    });
</script>
</html>
