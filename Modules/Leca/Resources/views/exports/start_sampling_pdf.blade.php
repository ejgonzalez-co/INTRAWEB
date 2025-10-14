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
        margin: 5px 20px 140px 10px;
    }

    .firma {
        width: 150px;
        height: 30px;
    }

    .imgEpa {
        width: 120px;
        height: 40px;
    }

    .maintext {
        font-size: 7px;
    }

    .textcenter {
        font-size: 6px;
        text-align: center;
    }
    .textcenter {
        font-size: 6px;
        text-align: center;
    }

    .textleft {
        font-size: 7px;
        text-align: left;
    }

    .tabletotal {
        border-collapse: collapse;
        border: 1px solid;
        margin: 0 auto;
    }

    td {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        overflow: hidden;

    }

    .verticalorientation {
        transform: rotate(90deg);
        margin-left: 10px;
        font-size: 7px;
    }

</style>
{{-- 33 --}}

<body>
    <div>
        <div>
            <table class="tabletotal">
                <tr>
                    <td colspan="12" rowspan="4"><img
                            src="{{ public_path() . '/assets/img/components/epa.png' }}" class="imgEpa" />
                    </td>
                    <th rowspan="4" colspan="25" class="textcenter">Recepción de muestras</th>
                    <td colspan="6" class="maintext">Código: LECA-R-023</td>
                </tr>
                <tr>
                    <td colspan="6" class="maintext">Versión: 04</td>
                </tr>
                <tr>
                    <td colspan="6" class="maintext">Fecha de Emisión: 21-04-28</td>
                </tr>
                <tr>
                    <td colspan="6" class="maintext">Página: 1/2</td>
                </tr>
                <tr>
                    <td colspan="12" class="textcenter">Proceso</td>
                    <td colspan="25" class="textcenter"></td>
                    <td colspan="2" class="textcenter">Consecutivo</td>
                    <td colspan="4" class="textcenter"></td>
                </tr>
                <tr>
                    <td colspan="12" class="textcenter">Fecha y hora</td>
                    <td colspan="2" rowspan="3" class="textcenter"> Responsable de entrega de muestra (Código y
                        firma)</td>
                    <td colspan="2" rowspan="3" class="textcenter" style="padding: 10px"> Tipo de Agua(1)</td>
                    <td colspan="6" rowspan="2" class="textcenter">Parámetros determinados en campo (2)</td>
                    <td colspan="5" rowspan="2" class="textcenter">Recipientes con Muestra</td>
                    <td colspan="8" rowspan="2" class="textcenter">Estado de muestras recepcionadas</td>
                    <td colspan="2" rowspan="2" class="textcenter" style="padding: 5px">¿ Se aceptan ?</td>
                    <td colspan="2" rowspan="3" class="textcenter">Identificación asignada por LECA</td>
                    <td colspan="4" rowspan="2" class="textcenter">Responsable de Recepción de muestra</td>
                </tr>
                <tr>
                    <td colspan="6" class="textcenter">Recepción de muestras</td>
                    <td colspan="6" class="textcenter">Toma de muestras</td>
                </tr>
                {{-- //27 --}}
                <tr>
                    <td colspan="2" class="textcenter" style="padding: 15px">AA</td>
                    <td colspan="1" class="textcenter" style="padding: 10px">MM</td>
                    <td colspan="1" class="textcenter" style="padding: 10px">DD</td>
                    <td colspan="2" class="textcenter" style="padding: 15px">HH</td>
                    <td colspan="2" class="textcenter" style="padding: 15px">AA</td>
                    <td colspan="1" class="textcenter" style="padding: 10px">MM</td>
                    <td colspan="1" class="textcenter" style="padding: 10px">DD</td>
                    <td colspan="2" class="textcenter" style="padding: 15px">HH</td>
                    <td colspan="1" class="textcenter" style="padding: 12px">Cloro</td>
                    <td colspan="1" class="textcenter" style="padding: 10px">pH</td>
                    <td colspan="1" class="textcenter" style="padding: 12px">NTU</td>
                    <td colspan="1" class="textcenter" style="padding: 15px">µS/cm</td>
                    <td colspan="2" class="textcenter">Otro</td>
                    <td colspan="2" class="textcenter" style="padding: 15px">Tipo (3)</td>
                    <td colspan="1" class="textcenter">Volumen Litros</td>
                    <td colspan="1" class="textcenter">Parámetros solicitados</td>
                    <td colspan="1" class="textcenter">Adición de Preservante (5)</td>
                    <td colspan="1" class="textcenter">T ºC (Inicial) (6)</td>
                    <td colspan="1" class="textcenter">T ºC (final) (7)</td>
                    <td colspan="1" class="textcenter" style="padding: 10px">C (8)</td>
                    <td colspan="1" class="textcenter" style="padding: 10px">NC (9)</td>
                    <td colspan="4" class="textcenter" style="padding: 20px">Observaciones (10)</td>
                    <td colspan="1" class="textcenter">Si</td>
                    <td colspan="1" class="textcenter">No</td>
                    <td colspan="2" class="textcenter">Código</td>
                    <td colspan="2" class="textcenter">Firma</td>
                </tr>
                {{-- {!! html_entity_decode($previousStudy->subprogram) !!} --}}

                {{-- {!! $start_samplings->users->name !!} --}}


                @foreach ($sampleReception as $sample)
                    @if($sample['state_receipt']==1)
                    <tr>
                        @php
                            $array = explode(' ', $sample['reception_date']);
                            $hour = $array[1];
                            $array = explode('-', $array[0]);
                            $year = $array[0];
                            $month = $array[1];
                            $day = $array[2];
                            
                            $arrayc = explode(' ', $sample['created_at']);
                            
                            $hourc = explode(':', $arrayc[1]);
                            $hourc = $hourc[0] . ':' . $hourc[1];
                            $arrayc = explode('-', $arrayc[0]);
                            $yearc = $arrayc[0];
                            $monthc = $arrayc[1];
                            $dayc = $arrayc[2];
                            
                        @endphp
                        <td colspan="2" class="textcenter">{!! $year !!}</td>
                        <td colspan="1" class="textcenter">{!! $month !!}</td>
                        <td colspan="1" class="textcenter">{!! $day !!}</td>
                        <td colspan="2" class="textcenter">{!! $hour !!}</td>

                        <td colspan="2" class="textcenter">{!! $yearc !!}</td>
                        <td colspan="1" class="textcenter">{!! $monthc !!}</td>
                        <td colspan="1" class="textcenter">{!! $dayc !!}</td>
                        <td colspan="2" class="textcenter">{!! $hourc !!}</td>

                        
                        @php
                        
                        $user=DB::table('users')->where("id", $sample['users_id'])->first();
                        
                        @endphp
                        @if($user)
                        @if($user->url_digital_signature != null)
                        <td colspan="2" class="textcenter"><img class="firma" src="{{public_path().'/storage/'.$user->url_digital_signature}}"></td>
                        @else
                        <td colspan="2" class="textcenter"></td>
                        @endif
                        @endif
                        <td colspan="2" class="textcenter">{!! $sample['type_water'] !!}</td>

                        <td colspan="1" class="textcenter">{!! $sample['chlorine_reception'] !!}</td>
                        <td colspan="1" class="textcenter">{!! $sample['reception_ph'] !!}</td>
                        <td colspan="1" class="textcenter">{!! $sample['ntu_reception'] !!}</td>
                        <td colspan="1" class="textcenter">{!! $sample['conductivity_reception'] !!}</td>
                        <td colspan="2" class="textcenter">{!! $sample['other_reception'] !!}</td>
                        <td colspan="2" class="textcenter">{!! $sample['type_receipt'] !!}</td>
                        <td colspan="1" class="textcenter">{!! $sample['volume_liters'] !!}</td>
                        <td colspan="1" class="textleft">{!! $sample['requested_parameters'] !!}</td>
                        <td colspan="1" class="textleft">{!! $sample['persevering_addiction'] !!}</td>
                        <td colspan="1" class="textcenter">{!! $sample['t_initial_receipt'] !!}</td>
                        <td colspan="1" class="textcenter">{!! $sample['t_final_receipt'] !!}</td>
                        @php
                            $MR = '';
                            $S = '';
                            if ($sample['according_receipt'] == 'Si') {
                                $MR = 'X';
                            } else {
                                $MR = '';
                            }
                            if ($sample['according_receipt'] == 'No') {
                                $S = 'X';
                            } else {
                                $S = '';
                            }
                        @endphp
                        <td colspan="1" class="textcenter">{!! $MR !!}</td>
                        <td colspan="1" class="textcenter">{!! $S !!}</td>
                        <td colspan="4" class="textcenter">{!! $sample['observation_receipt'] !!}</td>
                        @php
                            $MR = '';
                            $S = '';
                            if ($sample['is_accepted'] == 'Si') {
                                $MR = 'X';
                            } else {
                                $MR = '';
                            }
                            if ($sample['is_accepted'] == 'No') {
                                $S = 'X';
                            } else {
                                $S = '';
                            }
                        @endphp
                        <td colspan="1" class="textcenter">{!! $MR !!}</td>
                        <td colspan="1" class="textcenter">{!! $S !!}</td>
                        <td colspan="2" class="textcenter">{!! $sample['sample_reception_code'] !!}</td>
                        <td colspan="2" class="textcenter"></td>
                        
                        @if($sample['url_receipt'] != null)
                        <td colspan="2" class="textcenter"><img class="firma" src="{{public_path().'/storage/'.$sample['url_receipt'] }}"></td>
                        @else
                        <td colspan="2" class="textcenter"></td>
                        @endif
                    </tr>
                    @endif
                @endforeach
                    <tr>
                        <td rowspan="2" colspan="43" class="textleft">Observaciones:</td>
                    </tr>
                    <tr></tr>
                    <tr>    
                        {{-- 43 --}}
                        <td colspan="32" class="textcenter">Notas</td>
                        <td rowspan="2" class="textcenter" colspan="5">Firma</td>
                        <td rowspan="2" class="textcenter" colspan="6"></td>
                    </tr>
                    <tr>
                        <td colspan="16" class="textleft"> (1) cruda ( C), tratada (T), de proceso (P)(aguas clarificadas o filtradas).</td>
                        <td colspan="16" class="textleft">(6) T inicial; aplica para muestras con cadena de frío</td>
                    </tr>
                    <tr>
                        <td colspan="16" class="textleft">(2) NTU = turbiedad, µS/cm= conductividad, otros= temperatura, OD.</td>
                        <td colspan="16" class="textleft">(7) T de llegada; aplica para muestras con cadena de frío y almacenadas.</td>
                        <td rowspan="2" class="textcenter" colspan="5">Nombre</td>
                        <td rowspan="2" class="textcenter" colspan="6">Luis Ancizar Arango Vallejo</td>
                    </tr>
                    <tr>
                        <td colspan="16" class="textleft"> (3) Vidrio ( V), Plástico (P).</td>
                        <td colspan="16" class="textleft">(8) Conforme. De acuerdo a criterios relacionados en el procedimiento gestión de muestras</td>
                    </tr>
                    <tr>
                        <td colspan="16" class="textleft">(4) Parámetros relacionados en listas.</td>
                        <td colspan="16" class="textleft">(9) No Conforme.  De acuerdo a criterios relacionados en el procedimiento gestión de muestras.</td>
                        <td rowspan="2" class="textcenter" colspan="5">Rol</td>
                        <td rowspan="2" class="textcenter" colspan="6">Vo.Bo Dirección Técnica LECA</td>
                    </tr>
                    <tr>
                        <td colspan="16" class="textleft">(5) Ácidos (A), bases (B), tiosulfato de sodio(TS), otros ( O) Ver listas.</td>
                        <td colspan="16" class="textleft">(10) Observaciones: todo evento que le suceda al ítem de muestreo, solicitudes de clientes, no contemplados R-047.</td>
                    </tr>
            </table>
        </div>
    </div>
</body>

</html>
