<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title></title>
  <meta name="description" content="P&aacute;gina que permite la creaci&oacute;n de facturas para empresas.">
  <meta name="author" content="Christian Amen&oacute;s Ja&eacute;n">

  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <style>
  body {
    padding-top: 60px;
    padding-bottom: 40px;
  }
  </style>
  <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
  <link rel="stylesheet" href="css/style.css">

  <script src="js/libs/modernizr-2.5.3-respond-1.1.0.min.js"></script>
</head>
<body>
<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
  <nav class="navbar navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <a class="brand" href="index.php">Factu Creator</a>
        <div class="nav-collapse">
          <ul class="nav">
            <li class="active"><a href="index.php">Facturas/Albaranes <span class="icon icon-th-list icon-white"></span></a></li>
            <li><a href="new-bill.php">Crear factura <span class="icon icon-plus icon-white"></span></a></li>
            <li><a href="contact.php">Contacto <span class="icon icon-envelope icon-white"></span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
  </nav>

  <div class="container">

    <div class="tabbable tabs-left">
      <ul class="nav nav-tabs">
        <li class="active">
          <a id="tab-bill-all" data-toggle="tab" href="#lA">Todas las facturas</a>
        </li>
        <li class="">
          <a id="tab-bill-pend" data-toggle="tab" href="#lP">Facturas pendientes</a>
        </li>
        <li class="">
          <a id="tab-bill-close" data-toggle="tab" href="#lC">Facturas cobradas</a>
        </li>
      </ul>
      <div class="tab-content">
        <div id="lA" class="tab-pane active">
          <h2>Secci&oacute;n de facutras</h2>
          <p>
            En esta secci&oacute;n se muestran las facturas de la empresa. 
            Tanto las que ya han sido cobradas (color verde) como las que 
            a&uacute;n est&aacute;n pendientes de ser cobradas (color rojo).
          </p>
          <p>
            Puede realizar las acciones de vista en detalle, edici&oacute;n, 
            borrado, marcar como cobrada o pendiente, entre otras mediante los
            botones de acci&oacute;n que puede encontrar a la derecha de cada
            factura.
          </p>
          <div id="bill-all">
            
          </div>
        </div>
        <div id="lP" class="tab-pane">
          <h2>Secci&oacute;n de facutras pendientes</h2>
          <p>
            En esta secci&oacute;n se muestran las facturas de la empresa que
            a&uacute;n est&aacute;n pendientes de ser cobradas.
          </p>
          <p>
            Puede realizar las acciones de vista en detalle, edici&oacute;n, 
            borrado, marcar como cobrada, entre otras mediante los
            botones de acci&oacute;n que puede encontrar a la derecha de cada
            factura.
          </p>
          <p class="advertise">
            <strong>Nota: </strong>Note que cuando marque una factura como 
            cobrada esta desaparecer&aacute;, y la podr&aacute; encontrar en las 
            secciones de &laquo;<em>todas las facturas</em>&raquo; y &laquo;<em>
            facturas cobradas</em>&raquo;.
          </p>
          <div id="bill-pend">
            
          </div>
        </div>
        <div id="lC" class="tab-pane">
          <h2>Secci&oacute;n de facutras cobradas</h2>
          <p>
            En esta secci&oacute;n se muestran las facturas de la empresa que
            para las que ya se ha recibido el pago.
          </p>
          <p>
            Puede realizar las acciones de vista en detalle, edici&oacute;n, 
            borrado, marcar como pendiente, entre otras mediante los
            botones de acci&oacute;n que puede encontrar a la derecha de cada
            factura.
          </p>
          <p class="advertise">
            <strong>Nota: </strong>Note que cuando marque una factura como 
            cobrada esta desaparecer&aacute;, y la podr&aacute; encontrar en las 
            secciones de &laquo;<em>todas las facturas</em>&raquo; y &laquo;<em>
            facturas pendientes</em>&raquo;.
          </p>
          <div id="bill-close">
            
          </div>
        </div>
      </div>
    </div>

    <hr>

    <footer>
      <p>&copy; Christian Amen&oacute;s 2012</p>
    </footer>

  </div> <!-- /container -->
  
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>

  <script src="js/libs/bootstrap/bootstrap.min.js"></script>

  <script src="js/plugins.js"></script>
  <script src="js/script.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#tab-bill-all').bind('click', function(){
        alert('click all!!');
      });
      
      $('#tab-bill-pend').bind('click', function(){
        alert('click pendientes!!');
      });
      
      $('#tab-bill-close').bind('click', function(){
        alert('click cerradas!!');
      });
    });
  </script>

</body>
</html>
