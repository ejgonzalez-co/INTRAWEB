<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
</head>
<style>
	@page {
            margin: 1cm 2cm;
            font-family: Arial;
			font-size: 0.8rem;
        }
	.encabezado{
		width: 100%;
		text-align: left;
	}
	.enca{
		width: 80px;
		height: 100px;
	}
    .firma {
        width: 200px;
		height: 150px;
    }
	p{
		font-family: Arial, Helvetica, sans-serif;
	}
	.text-p{
		font-size: 0.8rem;
	}
	.center{
		text-align: center;
	}
	.contenedor{
		border:1px solid;
		border-radius: 10%;
	}
	.justificado{
		text-align: justify;
	}
	.conten-text{
		padding: 1rem;
	}
	.cursiva{
		font-style: italic;
	}
	.dicc{
		margin-top: -0.2rem;
		font-size: 0.6rem;
	}
	hr {
		page-break-after: always;
		border: 0;
		margin: 0;
		padding: 0;
	}
</style>
<body>
	<div class="encabezado">
		<img class="" src="{{ public_path() .'/assets/img/default/icon_epa.png' }}">
	</div>

	<div class="center">
		<h3><strong>Información general del funcionario: {{ $workHistorie->name }} {{ $workHistorie->surname }}</strong></h3>

		<p><strong>@lang('Type Document'):</strong> {{ $workHistorie->type_document }}</p>

		<p><strong>@lang('Number Document'):</strong> {{ $workHistorie->number_document }}</p>

		<p><strong>@lang('Date') de expedición del @lang('Document'):</strong> {{ $workHistorie->date_document }}</p>

		<p><strong>@lang('Birth Date'):</strong> {{ $workHistorie->birth_date }}</p>

		<p><strong>@lang('Name'):</strong> {{ $workHistorie->name }} {{ $workHistorie->surname }}</p>

		<p><strong>@lang('Gender'):{{ $workHistorie->gender }}</strong></p>

		<p><strong>@lang('Group Ethnic'):</strong> {{ $workHistorie->group_ethnic }}</p>

		<p><strong>@lang('RH'):</strong> {{ $workHistorie->rh }}</p>

		<p><strong>@lang('Level study'):</strong> {{ $workHistorie->level_study }} <span>{{ $workHistorie->level_study_other }}</span></p>
		

	</div>
	
	
	<div class="center">
		<h3><strong>Información de contacto del funcionario</strong></h3>

		<p><strong>@lang('Address'):</strong> {{ $workHistorie->address }}</p>

		<p><strong>@lang('Phone'):</strong> {{ $workHistorie->phone }}</p>

		<p><strong>@lang('Email'):</strong> {{ $workHistorie->email }}</p>

	</div>


	<div class="center">
		<h3><strong>Notificar en caso de evento</strong></h3>

		<p><strong>@lang('Name'):</strong> {{ $workHistorie->name_event }}</p>

		<p><strong>@lang('Phone'):</strong> {{ $workHistorie->phone_event }}</p>


	</div>
	<hr>


	@foreach ($workHistorie->workHistorieDocuments as $document)
	
	
		<p>{{ empty($document->workHistoriesConfigDocuments->name) ? '': $document->workHistoriesConfigDocuments->name }}</p>
		<p class="center"><img style="min-width:70%; max-width:100%;" src="storage/{{ $document->url_document }}"></p>
		<hr>
	@endforeach		
	


</body>
</html>
