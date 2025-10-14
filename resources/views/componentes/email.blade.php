
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<style>

		.container{
		}

		table{
			border: 1px #dceaf5 solid;
			border-collapse: collapse;
			border-radius: 4px;
			width: 100%;
		}

		table td{
			border: 1px #dceaf5 solid;
			padding: 10px;
		}

		.avisoLegal{
			font-style: italic;
			text-align: justify;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<p style="font-size:16px;text-align:justify"><b>Por favor <span>no responda</span> a este correo electrónico ya que el <?php echo config('mail.username') ?> solo funciona para notificaciones y no para trámites.</b></p>
		</div>
		<div class="row">
			<table>
				<tr style="background-color: {{ $bgTitulo ?? ''}}; color: {{ $colorTitulo ?? ''}};">
					<td>{{ $titulo ?? '' }}</td>
				</tr>
				<tr>
					<td colspan="100%">{{ $slot }}</td>
				</tr>
			
			</table>
		</div>
		<br>
		<div class="row">
			<div class="avisoLegal">
				<small><b>AVISO LEGAL:</b> Este correo electrónico contiene información confidencial de {{ $empresaAvisoLegal ?? config('app.name') }}, si usted no es el destinatario, le informamos que no podrá usar, retener, imprimir, copiar, distribuir o hacer público su contenido; de hacerlo podría tener consecuencias legales. Si ha recibido este correo por error, por favor infórmenos y bórrelo. Si usted es el destinatario, le solicitamos mantener reserva sobre el contenido, los datos o información de contacto del remitente y en general sobre la información de este documento y/o archivos adjuntos, a no ser que exista una autorización explicita.</small>
			</div>
		</div>
	</div>
</body>
</html>