<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style>
    @media only screen and (max-width: 600px) {
        .inner-body {
            width: 100% !important;
        }

        .footer {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 500px) {
        .button {
            width: 100% !important;
        }
    }

    .content-cell {
        text-align: justify;
    }
</style>
</head>
<body>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                {{ $header ?? '' }}

                <!-- Email Body -->
                <tr>
                    <td style="border-collapse:collapse;border-spacing:0;margin:0;padding:0" align="center" valign="top"  bgcolor="#F0F0F0">
                        <table style="border-collapse:collapse;border-spacing:0;padding:0;width:inherit;max-width:560px" border="0" width="560" cellspacing="0"  cellpadding="0" align="center">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell">
                                    <div style="background-color: white; margin: 20px auto; padding: 35px 50px; width: 800px; max-width: 800px; min-height: 400px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                                        {!! $htmlContent ?? Illuminate\Mail\Markdown::parse($slot) !!}

                                        {{ $subcopy ?? '' }}
                                    </div>

                                    {{ $footer ?? '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <td>
                       <div >
                       <p class="avisoLegal"> {{ $avisoLegal ?? 'Aviso Legal y Políticas de Privacidad:  Este correo y sus archivos adjuntos son confidenciales y para uso exclusivo del destinatario. Si ha recibido este mensaje por error, notifíquelo al remitente y elimínelo de su sistema. esta entidad protege su información personal conforme a las leyes de privacidad vigentes. No compartimos sus datos con terceros sin su consentimiento. Para más información sobre nuestras políticas de privacidad, visite nuestro sitio web. Cualquier uso no autorizado de este correo está prohibido y puede ser ilegal.' }}</p>
                       </div>
                 </td>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
