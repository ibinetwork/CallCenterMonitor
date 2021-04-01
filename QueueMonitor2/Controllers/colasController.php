<?php namespace Controllers;
//Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
//https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE
use Models\Cola as Cola;

class colasController{
	private $colas;
	private $lista;

	private $actual_id;
	
	public function __construct(){
		$this->colas = new Cola();
		$this->actual_id = 5003;
	}

	public function getActualIdView(){
		return $this->actual_id;
	}

	public function index(){
		//echo "<hr>Método INDEX<br>";
		$infocola = $this->colas->mcread('infocolas');
		// echo "<br><pre>" . var_export($infocola) . "</pre><br>";
		ksort($infocola);
		return $infocola;
	}

	public function ver($id){
		$this->actual_id = $id;
		return $id;
	}
	
	public function listacolas(){
		$infocola = $this->colas->mcread('infocolas');
		$this->lista = array_keys($infocola);			
		asort($this->lista);
		
		return $this->lista;
	}

	public function getQueues(){
		$infocola = $this->colas->mcread('infocolas');
		$this->lista = array_keys($infocola);			
		asort($this->lista);
		
		return $this->lista;
	}

	public function get_queues_states(){

		if(isset($_POST)){

			$info = $_POST["info"];
			$infocola = $this->colas->mcread('infocolas');
			ksort($infocola);

			echo "<JSON_DATA>" . json_encode($infocola) . "</JSON_DATA>";

		}
	}

	public function get_queue_state(){

		if(isset($_POST)){

			$info = $_POST["info"];
			$queue = $_POST["queue"];

			$infocola = $this->colas->mcread($info);

			echo "<JSON_DATA>" . json_encode($infocola[$queue]) . "</JSON_DATA>";

		}
	}
}

?>