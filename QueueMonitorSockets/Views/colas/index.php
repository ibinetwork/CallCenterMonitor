<!--Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE -->

<link rel="stylesheet" href="<?php echo URL; ?>Views/template/css/styles-colas.css" media="screen">



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




<script>

var websocket_server = new WebSocket("ws://<?php echo BASE_URL; ?>:<?php echo WEBSOCKET_PORT; ?>");
websocket_server.onopen = function(e) {
  
  console.log("Connected, websocket open")

  

  var request_update = JSON.stringify({
          'type':'update',
          'msg': "Open",
          'data': []
      });

  console.log("enviando", request_update)
  websocket_server.send(request_update);
    


    /*
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
    */
};




websocket_server.onerror = function(e) {
    // Errorhandling
    console.log("ERROR", e)
}
websocket_server.onmessage = function(e)
{
  console.log("ONMESSAGE", e)
  /*
    var json = JSON.parse(e.data);
    switch(json.type) {
        case 'update':
          getQueueStates(json.data)
          break;
        default:
          console.log("SOCKET RECEIVED DATA: ", json)
    }
    */
}



$(window).on("unload", function(e) {
  clearInterval(interval)
    console.log("INTERVAL CLEARED");
  });


</script>


<!--



-->