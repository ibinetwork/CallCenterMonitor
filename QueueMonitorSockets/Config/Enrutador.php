<?php namespace Config;
//Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
//https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE
class Enrutador{
  public static function run(Request $request){
    $controlador = $request->getControlador() . "Controller";
    $ruta = ROOT . "Controllers" . DS . $controlador . ".php";
    // echo "<br>REQUEST ENRUTADOR RUTA: " . var_export($ruta) . "<br>";
    $metodo = $request->getMetodo();
    // echo "<br>REQUEST ENRUTADOR METODO: $metodo<br>";

    if($metodo == "index.php"){
      $metodo = "index";
    }
    $argumento = $request->getArgumento();
    if(is_readable($ruta)){
      require_once $ruta;
      // load the controller specified
      $mostrar = "Controllers\\" . $controlador;
      $controlador = new $mostrar;
      // create a new "instance" like Controllers\colasController without ()
      if(!isset($argumento)){
        // echo "<br>CALL METHOD FROM CONTROLLER WITH OUT ARGUMENT<br>";
        $datos = call_user_func(array($controlador, $metodo));
        // echo "<br>datos loaded from call: <pre>" . var_export($datos) . "</pre><br>";
      }else{
        $metadata = call_user_func_array(array($controlador, $metodo), $argumento);
      }
    }
    //Cargar Vista
    $ruta = ROOT . "Views" . DS . $request->getControlador() . DS . $request->getMetodo() . ".php";

    // echo "<br>Cargando TEMPLATE<br>";
    require_once "Views/template.php";
    // uncomment in production  && !array_key_exists('error',$datos)
    
    if(is_readable($ruta)){
      // echo "<br>VISTA: $ruta, cargando<br>";
      require_once $ruta;
    }else{
      // print "No se encontró la ruta: $ruta";
    }
  }
}
?>
