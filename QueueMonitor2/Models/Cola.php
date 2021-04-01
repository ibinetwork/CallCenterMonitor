<?php namespace Models;
//Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en => 
//https => //github.com/neovoice/CallCenterMonitor/blob/master/LICENSE
use Memcached;

class Cola{
	private $mc;

	public function __construct(){
		$this->mc = new Memcached();
		$this->mc->addServer('localhost', 11211) or die ("Unable to connect");
	}
	//leer infocolas o infocanales
	public function mcread($info){
		$datos = ( $this->mc->get($info) ) ? $this->mc->get($info):  array();
		if(!empty($datos)){
			return $datos;
		}else{
			return $datos = array("error" => "error");
		}
	}

	public function mcreadTest(){
		// this function is being read two times


		$datos = [
			"5000" => [
				"cola" => "5000 has 0 calls (max unlimited) in 'random' strategy (0s holdtime, 0s talktime), W => 0, C => 0, A => 0, SL => 0.0% within 60s",
				"nomcola" => "5000",
				"llamencola" => "0",
				"llamcontestadas" => "0",
				"llamabandonadas" => "0",
				"niveldeservicio" => "0.0% within 60s",
				"agentes" => [
				   [
					  "linea" => "Agente 1 (Local\/401@from-internal) (ringinuse enabled) (dynamic) (Not in use) has taken no calls yet",
					  "miembro" => "5000",
					  "llamcontestadas" => 0,
					  "nombre" => "Agente 1 ",
					  "canal" => "Local\/401@from-internal",
					  "estado" => "Libre"
				   ]
				],
				"llamadas" => [
				   
				],
				"llamactuales" => 0
			 ],
			 "5001" => [
				"cola" => "5001 has 0 calls (max unlimited) in 'ringall' strategy (9s holdtime, 211s talktime), W => 0, C => 2, A => 0, SL => 100.0% within 30s",
				"nomcola" => "5001",
				"llamencola" => "19",
				"llamcontestadas" => "2",
				"llamabandonadas" => "0",
				"niveldeservicio" => "100.0% within 30s",
				"agentes" => [
				   [
					  "linea" => "Stalin (SIP\/303 from hint => 303@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
					  "miembro" => "5001",
					  "llamcontestadas" => 0,
					  "nombre" => "Stalin ",
					  "canal" => "SIP\/303",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "Eliecer (SIP\/302 from hint => 302@ext-local) (ringinuse disabled) (Not in use) has taken 3 calls (last was 7761 secs ago)",
					  "miembro" => "5001",
					  "llamcontestadas" => "3",
					  "nombre" => "Eliecer ",
					  "canal" => "SIP\/302",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "Andres (SIP\/301 from hint => 301@ext-local) (ringinuse disabled) (Not in use) has taken 1 calls (last was 894 secs ago)",
					  "miembro" => "5001",
					  "llamcontestadas" => "1",
					  "nombre" => "Andres ",
					  "canal" => "SIP\/301",
					  "estado" => "Conectado",
					  "callerid" => 12315534,
					  "duracion" => rand(1, 60)
				   ],
				   [
					  "linea" => "CompuStalin (IAX2\/504 from hint => 504@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5001",
					  "llamcontestadas" => 0,
					  "nombre" => "CompuStalin ",
					  "canal" => "IAX2\/504",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "Eliecer IAX (IAX2\/502 from hint => 502@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5001",
					  "llamcontestadas" => 0,
					  "nombre" => "Eliecer IAX ",
					  "canal" => "IAX2\/502",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "Stalin IAX (IAX2\/501 from hint => 501@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5001",
					  "llamcontestadas" => 0,
					  "nombre" => "Stalin IAX ",
					  "canal" => "IAX2\/501",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "Andres IAX (IAX2\/500 from hint => 500@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5001",
					  "llamcontestadas" => 0,
					  "nombre" => "Andres IAX ",
					  "canal" => "IAX2\/500",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "SA Tel (SIP\/313 from hint => 313@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
					  "miembro" => "5001",
					  "llamcontestadas" => 0,
					  "nombre" => "SA Tel ",
					  "canal" => "SIP\/313",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "ET Tel (SIP\/312 from hint => 312@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
					  "miembro" => "5001",
					  "llamcontestadas" => 0,
					  "nombre" => "ET Tel ",
					  "canal" => "SIP\/312",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "AG Tel (SIP\/311 from hint => 311@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5001",
					  "llamcontestadas" => 0,
					  "nombre" => "AG Tel ",
					  "canal" => "SIP\/311",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "Eliecer Tates (SIP\/304 from hint => 304@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5001",
					  "llamcontestadas" => 0,
					  "nombre" => "Eliecer Tates ",
					  "canal" => "SIP\/304",
					  "estado" => "Offline"
				   ]
				],
				"llamadas" => [
					[
						"callerid" => 1231425,
						"canal" => "SIP\/304",
						"tespera" => rand(1, 100),

					]
				],
				"llamactuales" => 1
			 ],
			 "5002" => [
				"cola" => "5002 has 0 calls (max unlimited) in 'ringall' strategy (0s holdtime, 0s talktime), W => 0, C => 0, A => 0, SL => 0.0% within 60s",
				"nomcola" => "5002",
				"llamencola" => "0",
				"llamcontestadas" => "0",
				"llamabandonadas" => "0",
				"niveldeservicio" => "0.0% within 60s",
				"agentes" => [
				   [
					  "linea" => "Stalin (SIP\/303 from hint => 303@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
					  "miembro" => "5002",
					  "llamcontestadas" => 0,
					  "nombre" => "Stalin ",
					  "canal" => "SIP\/303",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "Eliecer (SIP\/302 from hint => 302@ext-local) (ringinuse disabled) (Not in use) has taken 3 calls (last was 7761 secs ago)",
					  "miembro" => "5002",
					  "llamcontestadas" => "3",
					  "nombre" => "Eliecer ",
					  "canal" => "SIP\/302",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "Andres (SIP\/301 from hint => 301@ext-local) (ringinuse disabled) (Not in use) has taken 1 calls (last was 894 secs ago)",
					  "miembro" => "5002",
					  "llamcontestadas" => "1",
					  "nombre" => "Andres ",
					  "canal" => "SIP\/301",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "CompuStalin (IAX2\/504 from hint => 504@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5002",
					  "llamcontestadas" => 0,
					  "nombre" => "CompuStalin ",
					  "canal" => "IAX2\/504",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "Eliecer IAX (IAX2\/502 from hint => 502@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5002",
					  "llamcontestadas" => 0,
					  "nombre" => "Eliecer IAX ",
					  "canal" => "IAX2\/502",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "Stalin IAX (IAX2\/501 from hint => 501@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5002",
					  "llamcontestadas" => 0,
					  "nombre" => "Stalin IAX ",
					  "canal" => "IAX2\/501",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "Andres IAX (IAX2\/500 from hint => 500@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5002",
					  "llamcontestadas" => 0,
					  "nombre" => "Andres IAX ",
					  "canal" => "IAX2\/500",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "SA Tel (SIP\/313 from hint => 313@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
					  "miembro" => "5002",
					  "llamcontestadas" => 0,
					  "nombre" => "SA Tel ",
					  "canal" => "SIP\/313",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "ET Tel (SIP\/312 from hint => 312@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
					  "miembro" => "5002",
					  "llamcontestadas" => 0,
					  "nombre" => "ET Tel ",
					  "canal" => "SIP\/312",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "AG Tel (SIP\/311 from hint => 311@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5002",
					  "llamcontestadas" => 0,
					  "nombre" => "AG Tel ",
					  "canal" => "SIP\/311",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "Eliecer Tates (SIP\/304 from hint => 304@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5002",
					  "llamcontestadas" => 0,
					  "nombre" => "Eliecer Tates ",
					  "canal" => "SIP\/304",
					  "estado" => "Offline"
				   ]
				],
				"llamadas" => [
				   
				],
				"llamactuales" => 0
			 ],
			 "5003" => [
				"cola" => "5003 has 0 calls (max unlimited) in 'ringall' strategy (6s holdtime, 565s talktime), W => 0, C => 2, A => 0, SL => 100.0% within 60s",
				"nomcola" => "5003",
				"llamencola" => "0",
				"llamcontestadas" => "2",
				"llamabandonadas" => "0",
				"niveldeservicio" => "100.0% within 60s",
				"agentes" => [
				   [
					  "linea" => "Stalin (SIP\/303 from hint => 303@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
					  "miembro" => "5003",
					  "llamcontestadas" => 0,
					  "nombre" => "Stalin ",
					  "canal" => "SIP\/303",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "Eliecer (SIP\/302 from hint => 302@ext-local) (ringinuse disabled) (Not in use) has taken 3 calls (last was 7761 secs ago)",
					  "miembro" => "5003",
					  "llamcontestadas" => "3",
					  "nombre" => "Eliecer ",
					  "canal" => "SIP\/302",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "Andres (SIP\/301 from hint => 301@ext-local) (ringinuse disabled) (Not in use) has taken 1 calls (last was 894 secs ago)",
					  "miembro" => "5003",
					  "llamcontestadas" => "1",
					  "nombre" => "Andres ",
					  "canal" => "SIP\/301",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "CompuStalin (IAX2\/504 from hint => 504@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5003",
					  "llamcontestadas" => 0,
					  "nombre" => "CompuStalin ",
					  "canal" => "IAX2\/504",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "Eliecer IAX (IAX2\/502 from hint => 502@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5003",
					  "llamcontestadas" => 0,
					  "nombre" => "Eliecer IAX ",
					  "canal" => "IAX2\/502",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "Stalin IAX (IAX2\/501 from hint => 501@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5003",
					  "llamcontestadas" => 0,
					  "nombre" => "Stalin IAX ",
					  "canal" => "IAX2\/501",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "Andres IAX (IAX2\/500 from hint => 500@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5003",
					  "llamcontestadas" => 0,
					  "nombre" => "Andres IAX ",
					  "canal" => "IAX2\/500",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "SA Tel (SIP\/313 from hint => 313@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
					  "miembro" => "5003",
					  "llamcontestadas" => 0,
					  "nombre" => "SA Tel ",
					  "canal" => "SIP\/313",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "ET Tel (SIP\/312 from hint => 312@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
					  "miembro" => "5003",
					  "llamcontestadas" => 0,
					  "nombre" => "ET Tel ",
					  "canal" => "SIP\/312",
					  "estado" => "Libre"
				   ],
				   [
					  "linea" => "AG Tel (SIP\/311 from hint => 311@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5003",
					  "llamcontestadas" => 0,
					  "nombre" => "AG Tel ",
					  "canal" => "SIP\/311",
					  "estado" => "Offline"
				   ],
				   [
					  "linea" => "Eliecer Tates (SIP\/304 from hint => 304@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "5003",
					  "llamcontestadas" => 0,
					  "nombre" => "Eliecer Tates ",
					  "canal" => "SIP\/304",
					  "estado" => "Offline"
				   ]
				],
				"llamadas" => [
				   
				],
				"llamactuales" => 0
			 ],
			 "10001" => [
				"cola" => "10001 has 0 calls (max unlimited) in 'ringall' strategy (0s holdtime, 0s talktime), W => 0, C => 0, A => 0, SL => 0.0% within 60s",
				"nomcola" => "10001",
				"llamencola" => "0",
				"llamcontestadas" => "0",
				"llamabandonadas" => "0",
				"niveldeservicio" => "0.0% within 60s",
				"agentes" => [
				   [
					  "linea" => "Eliecer Tates (SIP\/304 from hint => 304@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
					  "miembro" => "10001",
					  "llamcontestadas" => 0,
					  "nombre" => "Eliecer Tates ",
					  "canal" => "SIP\/304",
					  "estado" => "Offline"
				   ]
				],
				"llamadas" => [
				   
				],
				"llamactuales" => 0
			 ]

		];


		return $datos;
			

	}


	
	
	public function __destruct(){
		$this->mc->quit();
	}
}
?>