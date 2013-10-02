<?php
ini_set("display_errors", 1); 
error_reporting(E_ALL); 
include_once('utils/db.php');
?>
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
    <?php
    if(filter_has_var(INPUT_GET, 'id') && is_numeric($_GET['id'])){
      $reqId = $_GET['id'];
      
      $dbh = new DB();
      $sql = "SELECT *
              FROM company
              WHERE id = :id";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':id' => $reqId));
      $result = $stmt->fetch(PDO::FETCH_OBJ);
      if($result!==false){
        $formValueName = $result->name;
        $formValueMail = $result->mail;
        $formValueOwn = $result->own;
        $formValueCif = $result->cif;
        $formValuePhone = $result->phone;
        $formValueFax = $result->fax;
        $formValueWebpage = $result->webpage;
        $formValueLogo = $result->logo;
        $formValueCountry = $result->country;
        $formValueProvince = $result->province;
        $formValueCity = $result->city;
        $formValuePostalCode = $result->postal_code;
        $formValueStreet = $result->street;
        $formValueNumber = $result->number;
        $formValueFloor = $result->floor;
        $formValueDoor = $result->door;
        
        if($formValueLogo!==''){
          $formValueLogo = explode('/img/', $formValueLogo);
          $formValueLogo = $formValueLogo[1];
        }
      
    ?>
        <div>
          <h2>Datos en detalle de cliente / empresa</h2>
          <p>
            En esta secci&oacute;n puede ver la informaci&oacute;n de una empresa
            o cliente especificado que se encuentre en el sistema.
          </p>
          <p>
            Como puede ver tambi&eacute;n puede acceder al editado de esta 
            informaci&oacute;n, presionando el bot&oacute;n "Editar".
          </p>
          <div class="right">
            <a class="btn btn-small btn-primary" href="edit-company.php?id=<?php echo $reqId; ?>">Editar <span class="icon icon-edit icon-white"></span></a>
          </div>
          <h3>Datos de contacto: </h3>
          <ul class="view-company-details">
            <li><strong>Nombre:</strong> <?php echo $formValueName; ?></li>
            <li><strong>Cif/Nif/Nie:</strong> <?php echo $formValueCif; ?></li>
            <li><strong>Direcci&oacute;n de correo electr&oacute;nico:</strong> <?php echo $formValueMail; ?></li>
            <li><strong>Tel&eacute;fono:</strong> <?php echo $formValuePhone; ?></li>
            <li><strong>Fax:</strong> <?php echo $formValueFax; ?></li>
            <li><strong>P&aacute;gina web:</strong> <?php echo $formValueWebpage; ?></li>
            <li><strong>Logotipo:</strong> <?php if($formValueLogo!=='') echo '<img alt="logotipo" title="logotipo" src="img/'.$formValueLogo.'"/>'; ?></li>
          </ul>
          <h3>Datos de postales:</h3>
          <ul class="view-company-details">
            <li><strong>Pa&iacute;s:</strong> <?php echo $formValueCountry; ?></li>
            <li><strong>Prov&iacute;ncia:</strong> <?php echo $formValueProvince; ?></li>
            <li><strong>Ciudad:</strong> <?php echo $formValueCity; ?></li>
            <li><strong>C&oacute;digo Postal:</strong> <?php echo $formValuePostalCode; ?></li>
            <li><strong>Calle:</strong> <?php echo $formValueStreet; ?></li>
            <li><strong>N&uacute;mero:</strong> <?php echo $formValueNumber; ?></li>
            <li><strong>Piso:</strong> <?php echo $formValueFloor; ?></li>
            <li><strong>Puerta:</strong> <?php echo $formValueDoor; ?></li>
          </ul>
          <div class="right">
            <a class="btn btn-small btn-primary" href="edit-company.php?id=<?php echo $reqId; ?>">Editar <span class="icon icon-edit icon-white"></span></a>
          </div>
        </div>
    <?php
      }else{
    ?>
        <div>
          <p class="data-ko-message">
            La empresa indicada no existe.
          </p>
          <p>
            Por favor, seleccione una empresa de la lista que puede encontrar en el
            apartado de listados de clientes o de empresas, pulsando el nombre de la
            empresa que quiera seleccionar. Puede ir a la p&aacute;gina principal
            mediante <a title="P&aacute;gina principal" href="index.php">ESTE</a>
            enlace.
          </p>
        </div>
    <?php
      }
    }
    else{
    ?>
      <div>
        <p class="data-ko-message">
          Para poder ver los datos de una empresa o cliente, se tiene que indicar
          cual desea consultar.
        </p>
        <p>
          Por favor, seleccione una empresa de la lista que puede encontrar en el
          apartado de listados de clientes o de empresas, pulsando el nombre de la
          empresa que quiera seleccionar. Puede ir a la p&aacute;gina principal
          mediante <a title="P&aacute;gina principal" href="index.php">ESTE</a>
          enlace.
        </p>
      </div>
    <?php
    }
    ?>
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
</body>
</html>
