<br><br>
<div class="table-responsive registros" v-cloak>
   <table class="table table-bordered  table-sm">
      <thead class="thead-light text-center">
         <tr>
            <th colspan="100%">
               <h5>
                  <strong>
                  Consolidado de {{ $tituloConsolidado ?? ''}}: 
                  </strong>
               </h5>
               <h3>
               <span  v-text="{{ $totalRegistros ?? 'n_a' }}">
               </h3>
            </th>
         </tr>
         {{ $slot}}
      </thead>
      <tbody>
         {{ $body }}
      </tbody>
   </table>
</div>