<!--Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE -->

<link rel="stylesheet" href="<?php echo URL; ?>Views/template/css/styles-colas.css" media="screen">
        
<script>

$(function (ready){

	getQueueStates();
	
	var interval = setInterval(function(){
		// console.log("Consultando en index")
		getQueueStates();
	}, 2000);
	$(window).on("unload", function(e) {
	  clearInterval(interval)
      console.log("INTERVAL CLEARED");
    });

});




</script>


<!--
<button onclick="testPut()">Put</button>
<button onclick="testClean()">Clean</button>
<button onclick="testRefresh()">Refresh</button>
-->

<div class="container-call-info">
  <div class="container-left">
    <div class="call-state">
      <h5>Estado de colas con llamadas</h5>
      <table class="table table-hover table-sm col-xs-1 text-center" id="table-queue-state">
        <thead>
          <tr class="table-active col-xs-1 text-center">
            <th>Cola</th>
            <th>Agentes</th>
            <th>Atendiendo</th>
            <th>En Cola</th>
            <th>Completadas</th>
            <th>Abandonadas</th>		
            <th>SL</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
    <div class="agents-in-call">
      <h5>Operadores en llamada:</h5>
      <div class="row" id="operators_in_call">
        
      </div>
    </div>
  </div>
  <div class="container-right">
	<div class="entry-calls">
		<h5>Llamadas entrantes</h5>
		<table class="table table-hover table-sm col-xs-1 text-center" id="table-entry-calls">
		<thead>
			<tr class="table-active col-xs-1 text-center">
			<th>Número</th>
			<th>Canal</th>
			<th>Tiempo</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
		</table>
	</div>
  </div>
</div>