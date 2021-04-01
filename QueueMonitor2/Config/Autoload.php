<?php namespace Config;
//Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
//https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE
class Autoload{
  public static function run(){
    spl_autoload_register(function ($class){
      $ruta = str_replace("\\","/",$class).".php";
      if(file_exists($ruta)){
        include_once $ruta;
        // echo "<br>La ruta cargada es: $ruta<br>";
      }
      else{
         echo "<br> No se pudo cargar: $ruta, no existe<br>";
      }
    });
  }
}
?>
