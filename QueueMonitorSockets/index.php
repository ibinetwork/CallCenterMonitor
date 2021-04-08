<?php
//Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
//https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('ASTV', '13');
$protocol = ( $_SERVER['HTTPS'] == 'on' ) ? 'https://':'http://';
$directorio = explode('/', $_SERVER['REQUEST_URI']);

$dir_str = $directorio[1] . DS;

if(count($directorio >= 4)){
    if($directorio[2] == "QueueMonitorSockets"){
        $dir_str .= $directorio[2] . DS;
    }
}

/*
var_export($directorio);

= "";
for($i=1; $i<=2; $i++){
    $dir_str .= $directorio[$i] . DS;
}

*/

$url = $protocol . $_SERVER['SERVER_NAME'] . DS . $dir_str ; //returns the current URL

define('URL', $url);
define('BASE_URL', $_SERVER['SERVER_NAME']);
define('WEBSOCKET_PORT', 9002);

// var_export($dir_str);

// phpinfo();

require_once "Config/Autoload.php";
Config\Autoload::run();
//require_once "Views/template.php";
Config\Enrutador::run(new Config\Request());
//Controllers\colaController::run();
//require_once "Controllers/infocolas.php";
?>
