<div class="table-responsive">
   <div class="alert alert-primary text-center text-dark" role="alert">
      <strong>Total de registros: <span  v-text="pagination.total"></span></strong>
   </div>
   <table class="table table-bordered table-hover">
      <thead class="thead-light">
         {{ $slot}}
      </thead>
      <tbody>
         {{ $body }}
      </tbody>
   </table>
</div>