<br><br>
<div class="table-responsive registros" v-cloak>
   <table class="table table-bordered table-hover table-sm">
      <thead class="thead-light text-center">
         <tr>
            <th colspan="100%">
               <h5>
                  <strong>
                  Total de registros de {{ $tituloTabla ?? ''}}: <span  v-text="{{ $totalRegistros ?? 'n_a' }}">
                  </strong>
               </h5>
            </th>
         </tr>
         {{ $slot}}
      </thead>
      <tbody>
         {{ $body }}
      </tbody>
   </table>
</div>