@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Fuel Consumption History Minors
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($fuelConsumptionHistoryMinors, ['route' => ['fuelConsumptionHistoryMinors.update', $fuelConsumptionHistoryMinors->id], 'method' => 'patch']) !!}

                        @include('fuel_consumption_history_minors.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection