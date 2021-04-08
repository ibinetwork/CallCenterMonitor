<!--Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE -->

<link rel="stylesheet" href="<?php echo URL; ?>Views/template/css/styles-colas.css" media="screen">
<script>
var ref_id = "<?php echo $metadata; ?>";
// console.log(ref_id)
</script>



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
	  <h5>Estado de los operadores:&nbsp;<span class="badge badge-success">Libre</span>&nbsp;<span class="badge badge-primary">En llamada</span>&nbsp;<span class="badge badge-secondary">Ocupado</span>&nbsp;<span class="badge badge-warning">Timbrando</span>&nbsp;<span class="badge badge-danger">Pausado</span>&nbsp;<span class="badge badge-info">Desconectado</span></h5>
		
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



<script>
console.log("VER-iniciando")
var interval = setInterval(function(){console.log("Conectando a socket...")}, 3000)
var websocket_server = new WebSocket("ws://localhost:15000/");
websocket_server.onopen = function(e) {
    websocket_server.send(
        JSON.stringify({
            'type':'socket',
            'user_id': -1
        })
    );

    clearInterval(interval);
    interval = setInterval(function(){
      websocket_server.send(
            JSON.stringify({
                'type':'update',
                'user_id': -1
            })
        );
    }, 1000);
    
};




websocket_server.onerror = function(e) {
    // Errorhandling
}
websocket_server.onmessage = function(e)
{
    var json = JSON.parse(e.data);
    switch(json.type) {
        case 'update':
          getSpecificQueueState(json.data[ref_id]);
          break;
        default:
          console.log("SOCKET RECEIVED DATA: ", json)
    }
}

$(window).on("unload", function(e) {
  clearInterval(interval)
    console.log("INTERVAL CLEARED");
  });


</script>