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
		$this->actual_id = 5000;
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
		/*
		$infocola = $this->colas->mcread('infocolas');
		$infocola = $this->colas->mcreadTest();
		*/
		$infocola = $this->colas->mcread('infocolas');
		$this->lista = array_keys($infocola);			
		asort($this->lista);
		
		return $this->lista;
	}

	public function get_queues_states(){

		if(isset($_POST)){

			$info = $_POST["info"];
			/*
			$infocola = $this->colas->mcread($info);
			$infocola = $this->colas->mcreadTest();
			*/
			$infocola = $this->colas->mcread($info);
			ksort($infocola);
			$resumencolas = $this->colas->mcread("resumencolas");
			$global_summary = $resumencolas["global_summary"];
			$queue_detailed = $resumencolas["queue_detailed"];

			echo "<JSON_DATA>" . json_encode(
				array(
					"infocolas" => $infocola,
					"global_summary" => $global_summary,
					"queue_detailed" => $queue_detailed
				)
			) . "</JSON_DATA>";

		}
	}

	public function get_queue_state(){

		if(isset($_POST)){

			$info = $_POST["info"];
			$queue = $_POST["queue"];
			/*
			$infocola = $this->colas->mcread($info);
			$infocola = $this->colas->mcreadTest();
			*/
			$infocola = $this->colas->mcread($info);
			
			$resumencolas = $this->colas->mcread("resumencolas");
			$queue_detaileds = $resumencolas["queue_detailed"];
			$queue_detailed = null;

			// var_export($queue_detaileds);

			if(array_key_exists($queue, $queue_detaileds)){
				$queue_detailed = $queue_detaileds[$queue];
			}
			

			echo "<JSON_DATA>" . json_encode(
				array(
					"infocola" => $infocola[$queue],
					"queue_detailed" => $queue_detailed
				)
			) . "</JSON_DATA>";
			

		}
	}
}

?>