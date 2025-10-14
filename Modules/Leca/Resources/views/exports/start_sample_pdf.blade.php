<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Toma de muestra</title>
</head>
<style>
    	@page {
		/* arri der abj izq */
        margin: 5px 20px 140px 80px;
    }
.firma{
    width: 150px;
    height: 30px;
}
.imgEpa{
    width: 120px;
    height: 40px;
}
.maintext{
    font-size: 7px;
}
.textcenter{
    font-size: 7px;
    text-align: center;
}
.tabletotal{
    border-collapse: collapse;
    border: 1px solid;
}
td{
    border-color:black;
    border-style:solid;
    border-width:1px;
    font-family:Arial, sans-serif;
    font-size:14px;
    overflow:hidden;

}
.verticalorientation {
    transform: rotate(90deg);
    margin-left: 10px;
    font-size: 7px;
}
</style>
<body>
    <div>
        <div>
            <table class="tabletotal">
                <tr>
                    <td colspan="6" rowspan="4"><img src="{{ public_path() .'/assets/img/components/epa.png'}}" class="imgEpa"/></td>
                    <th rowspan="4" colspan="22" class="textcenter">Toma de muestras: <br>
                    agua cruda, agua tratada y/o agua de origen desconocido</th>
                    <td colspan="6" class="maintext">Código: LECA-R-023</td>
                </tr>
                <tr>
                    <td colspan="6" class="maintext">Versión: 16</td>
                </tr>
                <tr>
                    <td colspan="6" class="maintext">Fecha de Emisión: 20-11-04</td>
                </tr>
                <tr>
                    <td colspan="6" class="maintext">Página: 1/2</td>
                </tr>
                <tr>
                    <td colspan="6" class="textcenter">Proceso</td>
                    <td colspan="28" class="textcenter">Laboratorio de Ensayo de calidad de Agua</td>
                </tr>
                <tr>
                    <td rowspan="2" class="textcenter">Fecha</td>
                    <td class="textcenter">AA</td>
                    <td colspan="4" class="textcenter">MM</td>
                    <td colspan="4" class="textcenter">DD</td>
                    <td rowspan="2" colspan="2" class="textcenter">Hora de</td>
                    <td colspan="4" class="textcenter">Llegada del Vehículo:</td>
                    <td colspan="2" class="textcenter">{!! $start_samplings->vehicle_arrival_time !!}</td>
                    <td rowspan="2" colspan="1" class="textcenter">Acuerdo de servicio</td>
                    <td rowspan="2" colspan="1" class="textcenter">{!! $start_samplings->service_agreement !!}</td>
                    <td rowspan="2" colspan="3" class="textcenter">Solicitud de toma de muestras</td>
                    <td rowspan="2" colspan="2" class="textcenter">{!! $start_samplings->sample_request !!}</td>
                    <td rowspan="2" colspan="2" class="textcenter">Hora</td>
                    <td rowspan="2" class="textcenter">{!! $start_samplings->time !!}</td>
                    <td rowspan="2" colspan="2" class="textcenter">Nombre</td>
                    <td rowspan="2" colspan="4" class="textcenter">{!! $start_samplings->users->name !!}</td>
                </tr>
                <tr>
                    <td class="textcenter">{!! $date['year'] !!}</td>
                    <td colspan="4" class="textcenter">{!! $date['month'] !!}</td>
                    <td colspan="4" class="textcenter">{!! $date['day'] !!}</td>
                    <td colspan="4" class="textcenter">Culminación de toma de muestras:</td>
                    <td colspan="2" class="textcenter">{!! $start_samplings->time_sample_completion !!}</td>
                </tr>
                <tr>
                    <td rowspan="3" colspan="2" class="textcenter">Equipos:</td>
                    <td colspan="4" class="textcenter">(Condiciones ambientales)</td>
                    <td colspan="13" class="textcenter">pH</td>
                    <td colspan="4" class="textcenter">Cloro residual:</td>
                    <td colspan="6" class="textcenter">Turbidez</td>
                    <td colspan="5" class="maintext">Otro ensayo: {!! $start_samplings->another_test !!}</td>
                </tr>
                <tr>
                    <td colspan="4" class="textcenter">Termohigrometro</td>
                    <td colspan="13" class="textcenter">Potenciómetro <br>
                    y/o multiparametro</td>
                    <td colspan="4" class="textcenter">Fotómetro <br>
                    y/o Bureta Digital:</td>
                    <td colspan="6" class="textcenter">Turbidimetro</td>
                    <td colspan="5" class="maintext">Otro equipo: {!! $start_samplings->other_equipment !!}</td>
                </tr>
                <tr>
                    <td colspan="4" class="textcenter">{!! $start_samplings->reference_thermohygrometer !!}</td>
                    <td colspan="13" class="textcenter">{!! $start_samplings->reference_multiparameter !!}</td>
                    <td colspan="4" class="textcenter">{!! $start_samplings->reference_photometer !!}</td>
                    <td colspan="6" class="textcenter">{!! $start_samplings->reference_turbidimeter !!}</td>
                    {{-- <td colspan="5" class="textcenter">EPA-LAB-EQ</td> --}}
                    <td colspan="5" class="textcenter"></td>
                </tr>
                <tr>
                    <td colspan="2" class="textcenter">Fórmula</td>
                    <td colspan="10" class="maintext">Cálculo de concentración de cloro Residual <br>
                        <div class="eq-c">
                            <p class="textcenter">(A * N * 35450) <br>
                            mgCl/L = ________________ <br>
                            V</p>
                        </td>
                    <td colspan="7" class="maintext"><strong>A = </strong>mL de la solución de FAS gastado en la valoración <br>
                    <strong>V = </strong>mL muestra tomada <br>
                    <strong>N = </strong>normalidad del FAS</td>
                    <td colspan="4" class="textcenter">Ensayo de Cloro: Concentración <br>
                    FAS <br>
                    N(Eq-g/L)</td>
                    <td colspan="6" class="textcenter">{!! $start_samplings->chlorine_test !!}</td>
                    <td colspan="3" class="textcenter">Factor/cálculo:</td>
                    <td colspan="2" class="textcenter">{!! $start_samplings->factor !!}</td>
                </tr>
                <tr>
                    <td colspan="8" class="textcenter">Documento Referencia en la determinación de:</td>
                    <td colspan="12" class="textcenter">Edicion {!! $start_samplings->edition ?? '23'!!}</td>
                    <td colspan="4" class="textcenter"></td>
                    <td colspan="10" class="textcenter"></td>
                </tr>
                <tr>
                    <td rowspan="3" class="textcenter">No.</td>
                    <td colspan="2" class="textcenter">Código</td>
                    <td colspan="5" rowspan="3" class="textcenter">Dirección</td>
                    <td rowspan="3" class="textcenter">Tipo de Agua (1)</td>
                    <td colspan="2" rowspan="2" class="textcenter">pH (X1)</td>
                    <td colspan="2" rowspan="2" class="textcenter">pH (X2)</td>
                    <td colspan="2" rowspan="2" class="textcenter">pH (X)</td>
                    <td colspan="3" rowspan="2" class="textcenter">Cloro Residual</td>
                    <td class="textcenter" rowspan="2">Turbidez</td>
                    <td colspan="2" class="textcenter">Condiciones
                    Ambientales de la 
                    toma de muestras</td>
                    <td rowspan="3" class="textcenter">Hora de <br>
                    0 a 24 <br>
                    Horas</td>
                    <td rowspan="3" width="10" class="textcenter"><p class="verticalorientation">Características climáticas predominantes</p></td>
                    <td colspan="3" class="textcenter">Ensayos a Realizar</td>
                    <td rowspan="3" class="textcenter">Número de envases</td>
                    <td colspan="3" class="textcenter">Recepción de muestras</td>
                    <td colspan="3" class="textcenter">Características de la muestra</td>
                    <td rowspan="3" class="textcenter">Observaciones</td>
                </tr>
                <tr>
                    <td rowspan="2" width="10" class="textcenter"><p class="verticalorientation">Punto de toma de muestra</p></td>
                    <td rowspan="2" width="10" class="textcenter"><p class="verticalorientation">Recepción de muestra</p></td>
                    <td rowspan="2" width="10" class="textcenter"><p class="verticalorientation">% Humedad</p></td>
                    <td rowspan="2" width="10" class="textcenter"><p class="verticalorientation">Temperatura</p></td>
                    <td rowspan="2" class="textcenter">F</td>
                    <td rowspan="2" class="textcenter">Q</td>
                    <td rowspan="2" class="textcenter">B</td>
                    <td rowspan="2" class="textcenter">Hora</td>
                    <td colspan="2" class="textcenter">Conforme</td>
                    <td rowspan="2" class="textcenter">MR</td>
                    <td rowspan="2" class="textcenter">S</td>
                    <td rowspan="2" class="textcenter">ME</td>
                </tr>
                <tr>
                    <td class="textcenter">Unidades de pH</td>
                    <td class="textcenter">T1 (°C)</td>
                    <td class="textcenter">Unidades de pH</td>
                    <td class="textcenter">T2 (°C)</td>
                    <td class="textcenter">Unidades de pH</td>
                    <td class="textcenter">Rango de Temperatura del agua en °C</td>
                    <td class="textcenter">V muestra (mL)</td>
                    <td class="textcenter">V FAS gastado Ensayo Cloro Residual (mL)</td>
                    <td class="textcenter">mg Cl<span>2</span>/L</td>
                    <td class="textcenter">NTU</td>
                    <td class="textcenter">SI</td>
                    <td class="textcenter">NO</td>
                </tr>
                @foreach ($samplesTaking as $samples)
                <tr>
                    <td class="textcenter">{{ $samples['id'] }}</td>
                    @php
                        $sammplePoints = DB::table('lc_sample_points')->where("id", $samples['lc_sample_points_id'])->first();
                    @endphp
                    <td class="textcenter">{!! $sammplePoints->point_location !!}</td>
                    <td class="textcenter">{{ $samples['sample_reception_code'] }}</td>
                    <td colspan="5" class="textcenter">{{ $samples['address'] }}</td>
                    <td class="textcenter">{{ $samples['type_water'] }}</td>
                    @php
                        $pHOneList = DB::table('lc_dynamic_ph_one_list')->select('ph_unit')->where("lc_sample_taking_id", $samples['id'])->where("deleted_at", null)->get()->toArray();
                        //Inicializa el array
                        $arrayPhUnit = [];
                        //Inicializa el array
                        $pHUnit = [];
                        //Recorre el cloro residual que tiene la muestra
                        foreach($pHOneList as $data){
                            //Convierte el array en una cadena
                            $oneUnitPh = json_encode($data);            
                            //Separa la cadena por los dos puntos
                            $twoUnitPh = explode(":", $oneUnitPh);
                            //Separa la cadena por el corchete en la posicion 1
                            $threeUnitPh = explode("}", $twoUnitPh[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourUnitPh = $threeUnitPh[0];
                            //Le asgina el valor a una variable que lo va a convertir en unn array
                            $fiveUnitPh = json_decode($fourUnitPh);
                            //Agrega un elemento a una posicion del array
                            array_push($arrayPhUnit, $fiveUnitPh);
                        }
                        //Recorre solo los ensayosque tiene relacionados la muestra
                        foreach($arrayPhUnit as $validate){
                            //Agrega un nuevo elemento a una posicion del array
                            array_push($pHUnit, $validate);
                        }
                        if($pHUnit == []){
                            $finalUnitPh = "N/A";
                        } else {
                            //Convierte el resultado en una cadena
                            $finalUnitPh = json_encode($pHUnit);
                        }
                        $pHOneListT1 = DB::table('lc_dynamic_ph_one_list')->select('t1')->where("lc_sample_taking_id", $samples['id'])->where("deleted_at", null)->get()->toArray();
                        //Inicializa el array
                        $arrayT1 = [];
                        //Inicializa el array
                        $t1 = [];
                        //Recorre el cloro residual que tiene la muestra
                        foreach($pHOneListT1 as $data){
                            //Convierte el array en una cadena
                            $oneT1 = json_encode($data);            
                            //Separa la cadena por los dos puntos
                            $twoT1 = explode(":", $oneT1);
                            //Separa la cadena por el corchete en la posicion 1
                            $threeT1 = explode("}", $twoT1[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourT1 = $threeT1[0];
                            //Le asgina el valor a una variable que lo va a convertir en unn array
                            $fiveT1 = json_decode($fourT1);
                            //Agrega un elemento a una posicion del array
                            array_push($arrayT1, $fiveT1);
                        }
                        //Recorre solo los ensayosque tiene relacionados la muestra
                        foreach($arrayT1 as $validate){
                            //Agrega un nuevo elemento a una posicion del array
                            array_push($t1, $validate);
                        }
                        if($t1 == []){
                            $finalT1 = "N/A";
                        } else {
                            //Convierte el resultado en una cadena
                            $finalT1 = json_encode($t1);
                        }
                    @endphp
                    <td class="textcenter">{!! $finalUnitPh !!}</td>
                    <td class="textcenter">{!! $finalT1 !!}</td>
                    @php
                        $phTwoList = DB::table('lc_dynamic_ph_two_list')->select('ph_unit')->where("lc_sample_taking_id", $samples['id'])->where("deleted_at", null)->get()->toArray();
                        //Inicializa el array
                        $arrayTwoPhUnit = [];
                        //Inicializa el array
                        $twoPhUnit = [];
                        //Recorre el cloro residual que tiene la muestra
                        foreach($phTwoList as $data){
                            //Convierte el array en una cadena
                            $oneUnitPh2 = json_encode($data);            
                            //Separa la cadena por los dos puntos
                            $twoUnitPh2 = explode(":", $oneUnitPh2);
                            //Separa la cadena por el corchete en la posicion 1
                            $threeUnitPh2 = explode("}", $twoUnitPh2[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourUnitPh2 = $threeUnitPh2[0];
                            //Le asgina el valor a una variable que lo va a convertir en unn array
                            $fiveUnitPh2 = json_decode($fourUnitPh2);
                            //Agrega un elemento a una posicion del array
                            array_push($arrayTwoPhUnit, $fiveUnitPh2);
                        }
                        //Recorre solo los ensayosque tiene relacionados la muestra
                        foreach($arrayTwoPhUnit as $validate){
                            //Agrega un nuevo elemento a una posicion del array
                            array_push($twoPhUnit, $validate);
                        }
                        if($twoPhUnit == []){
                            $finalPhUnitTwo = "N/A";
                        } else {
                            //Convierte el resultado en una cadena
                            $finalPhUnitTwo = json_encode($twoPhUnit);
                        }
                        $pHTwoListT2 = DB::table('lc_dynamic_ph_two_list')->select('t2')->where("lc_sample_taking_id", $samples['id'])->where("deleted_at", null)->get()->toArray();
                        //Inicializa el array
                        $arrayT2 = [];
                        //Inicializa el array
                        $t2 = [];
                        //Recorre el cloro residual que tiene la muestra
                        foreach($pHTwoListT2 as $data){
                            //Convierte el array en una cadena
                            $oneT2 = json_encode($data);            
                            //Separa la cadena por los dos puntos
                            $twoT2 = explode(":", $oneT2);
                            //Separa la cadena por el corchete en la posicion 1
                            $threeT2 = explode("}", $twoT2[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourT2 = $threeT2[0];
                            //Le asgina el valor a una variable que lo va a convertir en unn array
                            $fiveT2 = json_decode($fourT2);
                            //Agrega un elemento a una posicion del array
                            array_push($arrayT2, $fiveT2);
                        }
                        //Recorre solo los ensayosque tiene relacionados la muestra
                        foreach($arrayT2 as $validate){
                            //Agrega un nuevo elemento a una posicion del array
                            array_push($t2, $validate);
                        }
                        if($t2 == []){
                            $finalT2 = "N/A";
                        } else {
                            //Convierte el resultado en una cadena
                            $finalT2 = json_encode($t2);
                        }
                    @endphp
                    <td class="textcenter">{!! $finalPhUnitTwo !!}</td>
                    <td class="textcenter">{!! $finalT2 !!}</td>
                    @php
                        $phList = DB::table('lc_dynamic_ph_list')->select('ph_unit')->where("lc_sample_taking_id", $samples['id'])->where("deleted_at", null)->get()->toArray();
                        //Inicializa el array
                        $arrayPhDynamic = [];
                        //Inicializa el array
                        $phUnitDynamic = [];
                        //Recorre el cloro residual que tiene la muestra
                        foreach($phList as $data){
                            //Convierte el array en una cadena
                            $onePhUnitDynamic = json_encode($data);            
                            //Separa la cadena por los dos puntos
                            $twoPhUnitDynamic = explode(":", $onePhUnitDynamic);
                            //Separa la cadena por el corchete en la posicion 1
                            $threePhUnitDynamic = explode("}", $twoPhUnitDynamic[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourPhUnitDynamic = $threePhUnitDynamic[0];
                            //Le asgina el valor a una variable que lo va a convertir en unn array
                            $fivePhUnitDynamic = json_decode($fourPhUnitDynamic);
                            //Agrega un elemento a una posicion del array
                            array_push($arrayPhDynamic, $fivePhUnitDynamic);
                        }
                        //Recorre solo los ensayosque tiene relacionados la muestra
                        foreach($arrayPhDynamic as $validate){
                            //Agrega un nuevo elemento a una posicion del array
                            array_push($phUnitDynamic, $validate);
                        }
                        if($phUnitDynamic == []){
                            $finalphUnitDynamic = "N/A";
                        } else {
                            //Convierte el resultado en una cadena
                            $finalphUnitDynamic = json_encode($phUnitDynamic);
                        }
                        $rangeTemperature = DB::table('lc_dynamic_ph_list')->select('temperature_range')->where("lc_sample_taking_id", $samples['id'])->where("deleted_at", null)->get()->toArray();
                        //Inicializa el array
                        $arrayTemperature = [];
                        //Inicializa el array
                        $temperature = [];
                        //Recorre el cloro residual que tiene la muestra
                        foreach($rangeTemperature as $data){
                            //Convierte el array en una cadena
                            $oneTemperature = json_encode($data);            
                            //Separa la cadena por los dos puntos
                            $twoTemperature = explode(":", $oneTemperature);
                            //Separa la cadena por el corchete en la posicion 1
                            $threeTemperature = explode("}", $twoTemperature[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourTemperature = $threeTemperature[0];
                            //Le asgina el valor a una variable que lo va a convertir en unn array
                            $fiveTemperature = json_decode($fourTemperature);
                            //Agrega un elemento a una posicion del array
                            array_push($arrayTemperature, $fiveTemperature);
                        }
                        //Recorre solo los ensayosque tiene relacionados la muestra
                        foreach($arrayTemperature as $validate){
                            //Agrega un nuevo elemento a una posicion del array
                            array_push($temperature, $validate);
                        }
                        if($temperature == []){
                            $finalTemperature = "N/A";
                        } else {
                            //Convierte el resultado en una cadena
                            $finalTemperature = json_encode($temperature);
                        }
                    @endphp
                    <td class="textcenter">{!! $finalphUnitDynamic !!}</td>
                    <td class="textcenter">{!! $finalTemperature !!}</td>
                    @php
                        $vSampleList = DB::table('lc_residual_chlorine_list')->select('v_sample')->where("lc_sample_taking_id", $samples['id'])->where("deleted_at", null)->get()->toArray();
                        //Inicializa el array
                        $arrayvSample = [];
                        //Inicializa el array
                        $vSample = [];
                        //Recorre el cloro residual que tiene la muestra
                        foreach($vSampleList as $data){
                            //Convierte el array en una cadena
                            $onevSample = json_encode($data);            
                            //Separa la cadena por los dos puntos
                            $twovSample = explode(":", $onevSample);
                            //Separa la cadena por el corchete en la posicion 1
                            $threevSample = explode("}", $twovSample[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourvSample = $threevSample[0];
                            //Le asgina el valor a una variable que lo va a convertir en unn array
                            $fivevSample = json_decode($fourvSample);
                            //Agrega un elemento a una posicion del array
                            array_push($arrayvSample, $fivevSample);
                        }
                        //Recorre solo los ensayosque tiene relacionados la muestra
                        foreach($arrayvSample as $validate){
                            //Agrega un nuevo elemento a una posicion del array
                            array_push($vSample, $validate);
                        }
                        if($vSample == []){
                            $finalvSample = "N/A";
                        } else {
                            //Convierte el resultado en una cadena
                            $finalvSample = json_encode($vSample);
                        }
                        $clorineResidualList = DB::table('lc_residual_chlorine_list')->select('chlorine_residual_test')->where("lc_sample_taking_id", $samples['id'])->where("deleted_at", null)->get()->toArray();
                        //Inicializa el array
                        $arrayClorineResidual = [];
                        //Inicializa el array
                        $ResidualClorine = [];
                        //Recorre el cloro residual que tiene la muestra
                        foreach($clorineResidualList as $data){
                            //Convierte el array en una cadena
                            $oneClorineResidual = json_encode($data);            
                            //Separa la cadena por los dos puntos
                            $twoClorineResidual = explode(":", $oneClorineResidual);
                            //Separa la cadena por el corchete en la posicion 1
                            $threeClorineResidual = explode("}", $twoClorineResidual[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourClorineResidual = $threeClorineResidual[0];
                            //Le asgina el valor a una variable que lo va a convertir en unn array
                            $fiveClorineResidual = json_decode($fourClorineResidual);
                            //Agrega un elemento a una posicion del array
                            array_push($arrayClorineResidual, $fiveClorineResidual);
                        }
                        //Recorre solo los ensayosque tiene relacionados la muestra
                        foreach($arrayClorineResidual as $validate){
                            //Agrega un nuevo elemento a una posicion del array
                            array_push($ResidualClorine, $validate);
                        }
                        if($ResidualClorine == []){
                            $finalResidualClorine = "N/A";
                        } else {
                            //Convierte el resultado en una cadena
                            $finalResidualClorine = json_encode($ResidualClorine);
                        }
                        $mgCl2List = DB::table('lc_residual_chlorine_list')->select('mg_cl2')->where("lc_sample_taking_id", $samples['id'])->where("deleted_at", null)->get()->toArray();
                        //Inicializa el array
                        $arraymgCl2 = [];
                        //Inicializa el array
                        $mgCl2 = [];
                        //Recorre el cloro residual que tiene la muestra
                        foreach($mgCl2List as $data){
                            //Convierte el array en una cadena
                            $oneMgCl2 = json_encode($data);            
                            //Separa la cadena por los dos puntos
                            $twoMgCl2 = explode(":", $oneMgCl2);
                            //Separa la cadena por el corchete en la posicion 1
                            $threeMgCl2 = explode("}", $twoMgCl2[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourMgCl2 = $threeMgCl2[0];
                            //Le asgina el valor a una variable que lo va a convertir en unn array
                            $fiveMgCl2 = json_decode($fourMgCl2);
                            //Agrega un elemento a una posicion del array
                            array_push($arraymgCl2, $fiveMgCl2);
                        }
                        //Recorre solo los ensayosque tiene relacionados la muestra
                        foreach($arraymgCl2 as $validate){
                            //Agrega un nuevo elemento a una posicion del array
                            array_push($mgCl2, $validate);
                        }
                        if($mgCl2 == []){
                            $finalMgCl2 = "N/A";
                        } else {
                            //Convierte el resultado en una cadena
                            $finalMgCl2 = json_encode($mgCl2);
                        }
                        // $turbidityList = DB::table('lc_residual_chlorine_list')->select('turbidity')->where("lc_sample_taking_id", $samples['id'])->where("deleted_at", null)->get()->toArray();
                        // //Inicializa el array
                        // $turbidity = [];
                        // //Inicializa el array
                        // $turbidity = [];
                        // //Recorre el cloro residual que tiene la muestra
                        // foreach($turbidityList as $data){
                        //     //Convierte el array en una cadena
                        //     $oneTurbidity = json_encode($data);            
                        //     //Separa la cadena por los dos puntos
                        //     $twoTurbidity = explode(":", $oneTurbidity);
                        //     //Separa la cadena por el corchete en la posicion 1
                        //     $threeTurbidity = explode("}", $twoTurbidity[1]);
                        //     //Solo toma el resultado de la posicion 0
                        //     $fourTurbidity = $threeTurbidity[0];
                        //     //Le asgina el valor a una variable que lo va a convertir en unn array
                        //     $fiveTurbidity = json_decode($fourTurbidity);
                        //     //Agrega un elemento a una posicion del array
                        //     array_push($turbidity, $fiveTurbidity);
                        // }
                        // //Recorre solo los ensayosque tiene relacionados la muestra
                        // foreach($turbidity as $validate){
                        //     //Agrega un nuevo elemento a una posicion del array
                        //     array_push($turbidity, $validate);
                        // }
                        // if($turbidity == []){
                        //     $finalTurbidity = "N/A";
                        // } else {
                        //     //Convierte el resultado en una cadena
                        //     $finalTurbidity = json_encode($turbidity);
                        // }
                    @endphp
                    <td class="textcenter">{!! $finalvSample !!}</td>
                    <td class="textcenter">{!! $finalResidualClorine !!}</td>
                    <td class="textcenter">{!! $finalMgCl2 !!}</td>
                    {{-- <td class="textcenter">{!! $finalTurbidity !!}</td> --}}
                    <td class="textcenter">{{ $samples['humidity'] }}</td>
                    <td class="textcenter">{{ $samples['temperature'] }}</td>
                    <td class="textcenter">{{ $samples['hour_from_to'] }}</td>
                    <td class="textcenter">{{ $samples['prevailing_climatic_characteristics'] }}</td>
                    @php
                        $physicalTests = DB::table('lc_list_trials')->select('id')->where('type', 'Fisico')->get()->toArray();
                        //Consulta los ensayos que solo sean fisicos
                        $physicalTests = DB::table('lc_list_trials')->select('id')->where('type', 'Fisico')->get()->toArray();
                        //Consulta los ensayos que solo sean quimicos
                        $chemicalTesting = DB::table('lc_list_trials')->select('id')->where('type', 'Químicos')->get()->toArray();
                        //Consulta los ensayos que solo sean microbiologicos
                        $microbiologicalTesting = DB::table('lc_list_trials')->select('id')->where('type', 'Microbiológicos')->get()->toArray();
                        //Consulta los ensayos que tiene relaiconados la muestra
                        $sampleTests = DB::table('lc_sample_taking_has_lc_list_trials')->select('lc_list_trials_id')->where('lc_sample_taking_id', $samples['id'])->get()->toArray();
                        //Inicializa el array de los ensayos fisicos
                        $arrayPhysical = [];
                        //Inicializa el array de los ensayos quimicos
                        $arrayChemical = [];
                        //Inicializa el array para los ensayos fisicos que tiene la muestra
                        $arraySamplePhysical = [];
                        //Inicializa el array para los ensayos microbiologicos
                        $arrayMicrobiological = [];
                        //Inicializa el array para los ensayos fisicos finales
                        $physicals = [];
                        //Inicialoza el array para los ensayos quimicos finales
                        $chemicals = [];
                        //Inicializa el array para los ensayos microbilogicos finales
                        $microbiological = [];
                        //Recorre los ensayos que tiene la muestra
                        foreach($sampleTests as $data){
                            //Convierte el array en una cadena
                            $onePhysicalSample = json_encode($data);
                            //Separa la cadena por los dos puntos
                            $twoPhysicalSample = explode(":", $onePhysicalSample);
                            //Separa la cadena por el corchete en la posicion 1
                            $threePhysicalSample = explode("}", $twoPhysicalSample[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourPhysicalSample = $threePhysicalSample[0];
                            //Le asgina el valor a una variable que lo va a convertir en unn array
                            $fivePhysicalSample = json_decode($fourPhysicalSample);
                            //Agrega un elemento a una posicion del array
                            array_push($arraySamplePhysical, $fivePhysicalSample);
                        }
                        
                        //Recorre todos los ensayos fisicos que existen
                        foreach($physicalTests as $option){
                            //Convierte el array en una cadena
                            $onePhysical = json_encode($option);
                            //Separa la cedena por los dos puntos
                            $twoPhysical = explode(":", $onePhysical);
                            //Separa la cadena por el corchete en la posicion 1
                            $threePhysical = explode("}", $twoPhysical[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourPhysical = $threePhysical[0];
                            //Le asigna el valor a una variable que lo va a convertir en un array
                            $fivePhysical = json_decode($fourPhysical);
                            //Agrega un elemento a una posicion del array
                            array_push($arrayPhysical, $fivePhysical);
                        }

                        //Recorre solo los ensayosque tiene relacionados la muestra
                        foreach($arraySamplePhysical as $validate){
                            //Valida si el id de los estados fisicos estan dentro del array de los estados fisicos que existen
                            if (in_array($validate, Arr::pluck($physicalTests, 'id')) == true) {
                                //Agrega un nuevo elemento a una posicion del array
                                array_push($physicals, $validate);
                            }
                        }
                        if($physicals == []){
                            $finalPhysicals = "N/A";
                        } else {
                            //Convierte el resultado en una cadena
                            $finalPhysicals = json_encode($physicals);
                        }

                        //Recorre todos los ensayos fisicos que existen
                        foreach($chemicalTesting as $option){
                            //Convierte el array en una cadena
                            $oneChemical = json_encode($option);
                            //Separa la cedena por los dos puntos
                            $twoChemical = explode(":", $oneChemical);
                            //Separa la cadena por el corchete en la posicion 1
                            $threeChemical = explode("}", $twoChemical[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourChemical = $threeChemical[0];
                            //Le asigna el valor a una variable que lo va a convertir en un array
                            $fiveChemical = json_decode($fourChemical);
                            //Agrega un elemento a una posicion del array
                            array_push($arrayChemical, $fiveChemical);
                        }
                        //Recorre solo los ensayosque tiene relacionados la muestra
                        foreach($arraySamplePhysical as $validate){
                            //Valida si el id de los estados quimicos estan dentro del array de los estados quimicos que existen
                            if (in_array($validate, Arr::pluck($chemicalTesting, 'id')) == true) {
                                //Agrega un nuevo elemento a una posicion del array
                                array_push($chemicals, $validate);
                            }
                        }
                        if($chemicals == []){
                            $finalChemicals = "N/A";
                        } else {
                            //Convierte el resultado en una cadena
                            $finalChemicals = json_encode($chemicals);
                        }

                        //Recorre todos los ensayos fisicos que existen
                        foreach($microbiologicalTesting as $option){
                            //Convierte el array en una cadena
                            $oneMicrobiological = json_encode($option);
                            //Separa la cedena por los dos puntos
                            $twoMicrobiological = explode(":", $oneMicrobiological);
                            //Separa la cadena por el corchete en la posicion 1
                            $threeMicrobiological = explode("}", $twoMicrobiological[1]);
                            //Solo toma el resultado de la posicion 0
                            $fourMicrobiological = $threeMicrobiological[0];
                            //Le asigna el valor a una variable que lo va a convertir en un array
                            $fiveMicrobiological = json_decode($fourMicrobiological);
                            //Agrega un elemento a una posicion del array
                            array_push($arrayMicrobiological, $fiveMicrobiological);
                        }
                        //Recorre solo los ensayosque tiene relacionados la muestra
                        foreach($arraySamplePhysical as $validate){
                            //Valida si el id de los estados quimicos estan dentro del array de los estados quimicos que existen
                            if (in_array($validate, Arr::pluck($microbiologicalTesting, 'id')) == true) {
                                //Agrega un nuevo elemento a una posicion del array
                                array_push($microbiological, $validate);
                            }
                        }
                        if($microbiological == []){
                            $finalMicrobiological = "N/A";
                        } else {
                            //Convierte el resultado en una cadena
                            $finalMicrobiological = json_encode($microbiological);
                        }
                    @endphp
                    <td class="textcenter">{!! $finalPhysicals !!}</td>
                    <td class="textcenter">{!! $finalChemicals !!}</td>
                    <td class="textcenter">{!! $finalMicrobiological !!}</td>
                    <td class="textcenter">{{ $samples['container_number'] }}</td>
                    <td class="textcenter">{{ $samples['hour'] }}</td>
                    <td class="textcenter">{{ $samples['according'] }}</td>
                    <td class="textcenter">{{ $samples['according'] }}</td>
                    @php
                    if($samples['sample_characteristics'] == 'MR'){
                        $MR = 'X';
                    } else {
                        $MR = '';
                    }
                    if($samples['sample_characteristics'] == 'S'){
                        $S = 'X';
                    } else {
                        $S = '';
                    }
                    if ($samples['sample_characteristics'] == 'ME'){
                        $ME = 'X';
                    } else {
                        $ME = '';
                    }
                    @endphp
                    <td class="textcenter">{!! $MR !!}</td>
                    <td class="textcenter">{!! $S !!}</td>
                    <td class="textcenter">{!! $ME !!}</td>
                    <td class="textcenter">{{ $samples['observations'] }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="34"></td>
                </tr>
                <tr>
                    <td colspan="11" rowspan="4" class="textcenter">Media y DPR (Diferencia procentual Relativa) reportado de la muestra código</td>
                    <td colspan="4" rowspan="4" class="textcenter">{!! $start_samplings->media_and_DPR !!}</td>
                    <td colspan="2" rowspan="2" class="textcenter">Valor de la media de Cloro Residual</td>
                    <td colspan="2" rowspan="2" class="textcenter">{!! $start_samplings->mean_chlorine_value !!}</td>
                    <td colspan="3" rowspan="2" class="textcenter">Fecha de último ajuste de pH</td>
                    <td colspan="5" rowspan="2" class="textcenter">Pendiente</td>
                    <td colspan="7" rowspan="2" class="textcenter">Asimetria (Cuando aplique)</td>
                </tr>
                <tr>
                </tr>
                <tr>
                    <td colspan="2" rowspan="2" class="textcenter">(DPR) Cloro residual</td>
                    <td colspan="2" rowspan="2" class="textcenter">{!! $start_samplings->DPR_chlorine_residual !!}</td>
                    <td class="textcenter">AA</td>
                    <td class="textcenter">MM</td>
                    <td class="textcenter">DD</td>
                    <td rowspan="2" colspan="5" class="textcenter">{!! $start_samplings->pending !!}</td>
                    <td rowspan="2" colspan="7" class="textcenter">{!! $start_samplings->asymmetry !!}</td>
                </tr>
                <tr>
                    <td class="textcenter">{!! $datePHFinish[0] !!}</td>
                    <td class="textcenter">{!! $datePHFinish[1] !!}</td>
                    @if (empty($dateDay))
                    <td class="textcenter">N/A</td>
                    @else
                    <td class="textcenter">{!! $dateDay[0] !!}</td>
                    @endif
                    
                </tr>
                <tr>
                    <td colspan="2" rowspan="2" class="textcenter">Termómetro digital: {!! $digitalThermometer !!}</td>
                    <td colspan="6" rowspan="4" class="textcenter">Temperatura en °C, del control de transporte de:</td>
                    <td colspan="3" rowspan="2" class="textcenter">Salida LECA</td>
                    <td colspan="2" rowspan="2" class="textcenter">{!! $start_samplings->leca_outlet !!}</td>
                    <td colspan="21" class="textcenter">Notas</td>
                </tr>
                <tr>
                    <td colspan="21" rowspan="3" class="maintext"><strong>Nota(1)</strong>: para la toma de muestras Físico y químicas tomar un volumen de 1 Litro por frasco(lleno completo) y microbiológicas 250 mL a 400 mL por frasco,, además, debe contener tiosulfato de sodio(agua tratada) y dejar una cámara <br>
                    (espacio) de aire. <br>
                    <strong>Nota(2)</strong>:SM: Standard Methods for the Examination of Water and Wastewater <br>
                    <strong>Nota(3)</strong>: Para el procedimiento de pH la medición de cada ítem de ensayo examinado se realizara por duplicado y la diferencia entre las dos mediciones no puede superar 0,1 unidades de pH y se reportará el valor de la media (ẋ) <br>
                    (1) natural (N), tratada (T ), de proceso  aguas clarificadas o filtradas (P)</td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="2" class="textcenter">EPA-LAB-EQ</td>
                    <td colspan="3" rowspan="2" class="textcenter">Llegada de LECA</td>
                    <td colspan="2" rowspan="2" class="textcenter">{!! $start_samplings->arrival_LECA !!}</td>
                </tr>
                <tr>
                </tr>
                <tr>
                    <td colspan="34"></td>
                </tr>
                <tr>
                    <td rowspan="3" colspan="3" class="textcenter">Observaciones:</td>
                    <td colspan="17" rowspan="3" class="textcenter">{!! $start_samplings->observations !!}</td>
                    <td class="textcenter">Firma</td>
                    @if (empty($signatureUsers))
                    <td colspan="6" class="textcenter">N/A</td>
                    @else
                    <td colspan="6" class="textcenter"><img class="firma" src="{{public_path().'/storage/'.$signatureUsers}}"></td>
                    @endif
                    <td colspan="7" class="textcenter">vacio</td>
                </tr>
                <tr>
                    {{-- <td colspan="17" class="textcenter">buenas</td> --}}
                    <td rowspan="2" class="textcenter">Nombre</td>
                    <td colspan="6" class="textcenter">{!! $usersResposible['name'] !!}</td>
                    <td colspan="7" class="textcenter">{!! $start_samplings->witness !!}</td>
                </tr>
                <tr>
                    {{-- <td colspan="17" class="textcenter">buenas</td> --}}
                    <td colspan="6" class="textcenter">Responsable</td>
                    <td colspan="7" class="textcenter">Testigo</td>
                </tr>
                <tr>
                    <td colspan="34"></td>
                </tr>
                <tr>
                    <td colspan="8" rowspan="5" class="textcenter"><strong>Convención:</strong>F= físico , Q = químico , B = bacteriológico, MR= <br> muestra regular , S= seguimiento , ME= muestra especial , TM= toma <br> de muestras DPR (Diferencia porcentual relativa)</td>
                    <td colspan="2" rowspan="5" class="textcenter">Verificación de Controles</td>
                    <td colspan="2" rowspan="2" class="textcenter">Controles</td>
                    <td colspan="2" rowspan="2" class="textcenter">Estándar de pH (TM)</td>
                    <td rowspan="2" class="textcenter">{!! $start_samplings->standard_ph !!}</td>
                    <td colspan="3" rowspan="2" class="textcenter">Blanco de Cloro Residual</td>
                    <td colspan="2" rowspan="2" class="textcenter">"Patrón de Cloro residual (mg Cl<sub>2</sub>/L) (TM )"</td>
                    <td colspan="2" rowspan="2" class="textcenter">"Patrón (NTU) (TM )"</td>
                    <td colspan="2" rowspan="5" class="textcenter">Entrega de la Muestra a conformidad a:</td>
                    <td colspan="2" rowspan="3" class="textcenter">Firma</td>
                    @if (empty($usersConformi))
                    <td colspan="8" rowspan="3" class="textcenter">N/A</td>
                    @else
                    <td colspan="8" rowspan="3" class="textcenter"><img class="firma" src="{{public_path().'/storage/'.$usersConformi['url_digital_signature']}} ?? 'N/A'"></td>
                    @endif
                </tr>
                <tr>
                </tr>
                <tr>
                    <td colspan="2" class="textcenter">Inicial:</td>
                    <td colspan="3" class="textcenter">{!! $start_samplings->initial !!}:</td>
                    <td colspan="3" rowspan="3" class="textcenter">{!! $start_samplings->chlorine_residual_target !!}</td>
                    <td colspan="2" rowspan="3" class="textcenter">{!! $finalClorineResidual !!}</td>
                    <td rowspan="3" class="textcenter">Inicial</td>
                    <td rowspan="3" class="textcenter">{!! $start_samplings->initial_pattern !!}</td>
                </tr>
                <tr>
                    <td colspan="2" class="textcenter">Intermedia:</td>
                    <td colspan="3" class="textcenter">{!! $start_samplings->intermediate !!}</td>
                    {{-- <td colspan="2" class="textcenter">este</td> --}}
                    <td colspan="2" rowspan="2" class="textcenter">Nombre</td>
                    @if (empty($usersConformi))
                    <td colspan="8" rowspan="2" class="textcenter">N/A</td>
                    @else
                    <td colspan="8" rowspan="2" class="textcenter">{!! $usersConformi['name'] !!}</td>
                    @endif
                </tr>
                <tr>
                    <td colspan="2" class="textcenter">Final:</td>
                    <td colspan="3" class="textcenter">{!! $start_samplings->end !!}</td>
                    {{-- <td colspan="2" class="textcenter">este</td> --}}
                </tr>
            </table>
        </div>
    </div>
</body>
</html>