<?php namespace Views;
//Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
//https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE


use Controllers\colasController;

$template = new Template();


class Template{
	public function __construct(){	  
    $listado = (new \Controllers\colasController())->getQueues();
?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Asterisk | Monitor ACD </title>
        <link rel="stylesheet" href="<?php echo URL; ?>Views/template/css/bootstrap.min.css" media="screen">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="<?php echo URL; ?>Views/template/js/jquery.min.js"></script>
        <script src="<?php echo URL; ?>Views/template/js/bootstrap.min.js"></script>
        <script>
        const ref_url = "<?php echo URL; ?>";
        </script>
        <script src="<?php echo URL; ?>Views/template/js/monitor.js"></script>         

    </head>
    <body>
      <div class="navbar navbar-expand-lg navbar-light bg-light">
          
        <a href="<?php echo URL;?>" target="_blank" class="navbar-brand">Ver:</a>       
        <ul class="navbar-nav">
          <div class="container">
            <a class="badge badge-dark" href="<?php echo URL;?>" role="button">TODAS</a>
            <!--a class="btn btn-outline-secondary btn-sm" href="<?php echo URL;?>" role="button">TODAS</a-->
            <?php
            foreach($listado as $cola) {
            ?>
              <small>&nbsp;</small>
              <a class="badge badge-dark" href="<?php echo URL . '?url=colas/ver/' . $cola;?>" role="button"><?php echo $cola?></a>				
            <?php
            }
            ?>
			    </div>
        </ul>   
      </div>
      <div class="container"></div>
  <?php
  }
  public function __destruct(){
  ?>
    <script>
    

    </script>

    </body>
    </html>
<?php
  }
}
?>


