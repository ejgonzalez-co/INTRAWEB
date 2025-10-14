<template>
	<div id="sandbox-container">
		<div class="calendario"></div>
	</div>
	<div class="form-group row col-md-12">
		<div class="col-sm-12">
			<input type="text" name="fechasCalendarioFeriado" id="fechasCalendarioFeriado" value="" class="form-control" readonly>
		</div>
	</div>
</template>

<div id="sandbox-container">
   <div class="calendario"></div>
</div>
<input type="text" id="fechasCalendario">
<script>

    jQuery(document).ready(function(){
      $('#sandbox-container .calendario').datepicker({
			multidate: true,
			format: "yyyy-mm-dd",
			multidateSeparator: ",",
			weekStart: 1,
			language: "es",
			todayHighlight: true,
			todayBtn: true,
      }).on('changeDate', function(e) {
      	jQuery("#fechasCalendarioFeriado").val($('.calendario').datepicker('getFormattedDate'));
      });
   });
</script>
<style>
   .datepicker table {
   	box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
   	border-radius: 5px;
	}
</style>
