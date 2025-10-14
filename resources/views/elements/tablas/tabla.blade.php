<div class="table-responsive" v-cloak>
   <table class="table table-bordered table-hover {{ $clase_table ?? ''}}">
      <thead class="thead-light">
         {{ $slot}}
      </thead>
      <tbody>
         {{ $body }}
      </tbody>
   </table>
</div>