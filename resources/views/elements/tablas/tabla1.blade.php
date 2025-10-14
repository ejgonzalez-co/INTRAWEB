<div class="table-responsive">
   <table class="table table-bordered table-hover {{ $clase_table ?? ''}}">
      <thead class="thead-light">
         {{ $slot}}
      </thead>
      <tbody>
         {{ $body }}
      </tbody>
   </table>
</div>