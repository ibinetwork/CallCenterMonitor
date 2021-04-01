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

		//echo "<hr>Método VER: ARG: $id<br>";
		return $id;
		/*
		$infocola = $this->colas->mcread('infocolas');
		if(array_key_exists('error',$infocola)){
			return $infocola;
		}else{
			$cola = $infocola[$id];		
			return $cola;
		}*/
	}
	
	public function listacolas(){
		//echo "<hr>Método listacolas<br>";
		$infocola = $this->colas->mcread('infocolas');
		$this->lista = array_keys($infocola);			
		asort($this->lista);
		
		return $this->lista;
	}

	public function getQueues(){
		//echo "<hr>Método listacolas<br>";
		$infocola = $this->colas->mcread('infocolas');
		$this->lista = array_keys($infocola);			
		asort($this->lista);
		
		return $this->lista;
	}

	public function get_queues_states(){
		//echo "<br>GETSTATES NORMAL<bR>";

		if(isset($_POST)){
			//echo "<br>GET STATES POST<br>";

			$info = $_POST["info"];

			/*

			$infocola = $this->colas->mcread('infocolas');
			$infocola = $this->colas->mcreadTest();
			*/

			$infocola = $this->colas->mcread('infocolas');
			

			ksort($infocola);

			echo "<JSON_DATA>" . json_encode($infocola) . "</JSON_DATA>";

		}
	}

	public function get_queue_state(){
		//echo "<br>GETSTATES NORMAL<bR>";

		if(isset($_POST)){
			//echo "<br>GET STATE POST<br>";

			$info = $_POST["info"];
			$queue = $_POST["queue"];

			/*

			$infocola = $this->colas->mcread('infocolas');
			$infocola = $this->colas->mcreadTest();
			*/

			$infocola = $this->colas->mcread('infocolas');

			
			echo "<JSON_DATA>" . json_encode($infocola[$queue]) . "</JSON_DATA>";

		}
	}
}

?>