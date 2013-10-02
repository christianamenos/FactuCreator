<?php
include_once('utils/db.php');
include_once('libs/fpdf/fpdf.php');
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>Detalles del albarán</title>
  <meta name="description" content="P&aacute;gina que permite ver los detalles de los albaranes emitidos por las empresas.">
  <meta name="author" content="Christian Amen&oacute;s Ja&eacute;n">

  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <style>
  body {
    padding-top: 60px;
    padding-bottom: 40px;
  }
  </style>
  <!--CSS file (default YUI Sam Skin) -->
  <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/calendar/assets/skins/sam/calendar.css">
  <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
  <link rel="stylesheet" href="css/style.css">
  

  <script src="js/libs/modernizr-2.5.3-respond-1.1.0.min.js"></script>
</head>
<body class="yui-skin-sam">
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
            <li><a href="business.php">Clientes/Empresas <span class="icon icon-user icon-white"></span></a></li>
            <li><a href="contact.php">Contacto <span class="icon icon-envelope icon-white"></span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
  </nav>

  <div class="container">
    <?php
    $info = $items = $language = null;
    
    if(filter_has_var(INPUT_GET, 'id')
    && ctype_digit($_GET['id'])){
      $billId = $_GET['id'];
        
      $dbh = new DB();
      $b = $dbh->beginTransaction();
      $ret = array();
      if($b){
        $pdoStmt = $dbh->prepare('SELECT b.id, b.creation_date, b.expiration_date, b.payed, b.pay_mode, b.language_id, b.reference,
                                         cs.name AS sender_name, cs.cif AS sender_cif, cs.mail AS sender_mail, cs.phone AS sender_phone, cs.fax AS sender_fax,  cs.webpage AS sender_webpage, cs.logo AS sender_logo, cs.country AS sender_country, cs.province AS sender_province, cs.city AS sender_city, cs.postal_code AS sender_postal_code, cs.street AS sender_street, cs.number AS sender_number, cs.floor as sender_floor, cs.door as sender_door, cs.bank_account as bank_account,
                                         ca.name AS addressee_name, ca.cif AS addressee_cif, ca.mail AS addressee_mail, ca.phone AS addressee_phone, ca.fax AS addressee_fax,  ca.webpage AS addressee_webpage, ca.logo AS addressee_logo, ca.country AS addressee_country, ca.province AS addressee_province, ca.city AS addressee_city, ca.postal_code AS addressee_postal_code, ca.street AS addressee_street, ca.number AS addressee_number, ca.floor as addressee_floor, ca.door as addressee_door
                                  FROM bill AS b
                                  LEFT JOIN company AS cs ON b.sender_id = cs.id
                                  LEFT JOIN company AS ca ON b.addressee_id = ca.id
                                  LEFT JOIN vat AS v ON b.vat_id = v.id
                                  LEFT JOIN language AS l ON b.language_id = l.id
                                  WHERE b.id = :bill_id');
        $res = $pdoStmt->execute(array(':bill_id' => $billId));
        if($res){
          $info = $pdoStmt->fetch(PDO::FETCH_OBJ);
          $pdoStmt = $dbh->prepare('SELECT *
                                    FROM item
                                    WHERE bill_id = :bill_id');
          $res = $pdoStmt->execute(array(':bill_id' => $billId));
          if($res){
            $items = $pdoStmt->fetchAll(PDO::FETCH_OBJ);
          }
          else{
            $formErrorId = true;
          }
          
          $language = $pdoStmt->fetch(PDO::FETCH_OBJ);
          $pdoStmt = $dbh->prepare('SELECT *
                                    FROM language
                                    WHERE id = :id');
          $res = $pdoStmt->execute(array(':id' => $info->language_id));
          if($res){
            $language = $pdoStmt->fetch(PDO::FETCH_OBJ);
          }
          else{
            $formErrorId = true;
          }
        }
        else{
          $formErrorId = true;
        }
      }
      else{
        $formErrorId = true;
      }
    }
    ?>
     
    <?php
      if(isset($formErrorId)){
      ?>
        <div>
          <p class="data-ko-message">
            Error, la factura indicada no es v&aacute;lida. Si cree que no deber&iacute;a
            estar viendo este mensaje, contacte con el administrador de la aplicaci&oacute;n.
          </p>
          <p>
            Puede volver a la pantalla principal mediante <a title="P&aacute;gina
            principal" href="index.php">ESTE</a> enlace y seleccionar una factura
            desde all&iacute; si no lo ha hecho.
          </p>
        </div>
      <?php
      }else{
        $lang = $language->shortening;
        //imprimir los datos de la factura; generar un pdf para que lo pueda descargar tbn, botón de enviar por correo electrónico
        $translate = array(
            'es' => array('download_pdf' => 'Descargar PDF',
                          'enterprise_logo' => 'Logotipo de empresa',
                          'bill_number' => 'Albarán n&uacute;mero',
                          'now_date' => 'Fecha actual a d&iacute;a',
                          'expiration_date' => 'Fecha de vencimiento a d&iacute;a',
                          'reference' => 'Referencia',
                          'sender_data' => 'Datos de la empresa emisora del albarán',
                          'addressee_data' => 'Datos de la empresa receptora del albarán',
                          'name' => 'Nombre',
                          'nameFrom' => 'Nombre emisor',
                          'nameTo' => 'Nombre receptor',
                          'nif' => 'N.I.F.',
                          'street' => 'Calle',
                          'number' => 'N&uacute;mero',
                          'floor' => 'Planta',
                          'door' => 'Puerta',
                          'postal_code' => 'C&oacute;digo postal',
                          'city' => 'Localidad',
                          'province' => 'Provincia',
                          'country' => 'Pa&iacute;s',
                          'phone' => 'Tel&eacute;fono',
                          'fax' => 'Fax',
                          'mail' => 'Correo electr&oacute;nico',
                          'webpage' => 'P&aacute;gina web',
                          'item_description' => 'Descripci&oacute;n del producto o servicio',
                          'unit_price' => 'Precio unitario o por hora',
                          'amount' => 'Cantidad',
                          'discount' => 'Descuento aplicado',
                          'final_price' => 'Precio final',
                          'final_price_vat' => 'Precio final con I.V.A.',
                          'total' => 'Total',
                          'total_vat' => 'I.V.A.',
                          'total_with_vat' => 'Total con I.V.A.',
                          'bank_transfer' => 'Transferencia bancaria',
                          'cash_payment' => 'Pago al contado',
                          'check' => 'Cheque',
                          'pay_mode' => 'M&eacute;todo de pago',
                          'bank_account' => 'N&uacute;mero de cuenta',
                          'signature' => 'Firma del receptor'
                ),
            'ca' => array('download_pdf' => 'Desc&agrave;rrega del PDF',
                          'enterprise_logo' => 'Logotip de l\'empresa',
                          'bill_number' => 'N&uacute;mero de l\'albarà',
                          'now_date' => 'Data actual a dia',
                          'expiration_date' => 'Data de venciment a dia',
                          'reference' => 'Referència',
                          'sender_data' => 'Dades de l\'empresa emisora de l\'albarà',
                          'addressee_data' => 'Dades de l\'empresa receptora de l\'albarà',
                          'name' => 'Nom',
                          'nameFrom' => 'Nom emisor',
                          'nameTo' => 'Nom receptor',
                          'nif' => 'N.I.F.',
                          'street' => 'Carrer',
                          'number' => 'N&uacute;mero',
                          'floor' => 'Planta',
                          'door' => 'Porta',
                          'postal_code' => 'Codi postal',
                          'city' => 'Localitat',
                          'province' => 'Prov&iacute;ncia',
                          'country' => 'Pa&iacute;s',
                          'phone' => 'Tel&egrave;fon',
                          'fax' => 'Fax',
                          'mail' => 'Correu electr&ograve;nic',
                          'webpage' => 'P&agrave;gina web',
                          'item_description' => 'Descripci&oacute; del producte o servei',
                          'unit_price' => 'Preu unitari o per hora',
                          'amount' => 'Quantitat',
                          'discount' => 'Descompte aplicat',
                          'final_price' => 'Preu final',
                          'final_price_vat' => 'Preu final amb I.V.A.',
                          'total' => 'Total',
                          'total_vat' => 'I.V.A.',
                          'total_with_vat' => 'Total con I.V.A.',
                          'bank_transfer' => 'Transfer&egrave;ncia banc&agrave;ria',
                          'cash_payment' => 'Pagament al comptat',
                          'check' => 'Xec',
                          'pay_mode' => 'M&egrave;tode de pagament',
                          'bank_account' => 'N&uacute;mero de compte',
                          'signature' => 'Firma del receptor'
                )
        );
      ?>
        <div id="show-bill">
          <div id="bill-header">
              <!-- apareixeran les dades de les dues empreses -->
              <a class="right btn btn-danger" href="pdf-delivery-note.php?id=<?php echo $_GET['id']; ?>"><?php echo $translate[$lang]['download_pdf']; ?> <span class="icon-download-alt icon-white"></span></a>
              <div id="bill-sender-logo">
                <?php 
					$relativePath = explode('/',$info->sender_logo);
					$relativePath = 'img/logos/'.$relativePath[count($relativePath)-1];
					if(strlen($info->sender_logo)>0) echo '<img alt="'.$translate[$lang]['enterprise_logo'].'" title="'.$translate[$lang]['enterprise_logo'].'" src="'.$relativePath.'"/>';
				?>
              </div>
              <h2><?php echo $translate[$lang]['bill_number']; ?>: <?php echo $billId; ?></h2>
              <?php
                $creationDate = explode('-', $info->creation_date);
                $creationDate = $creationDate[2].'-'.$creationDate[1].'-'.$creationDate[0];
              ?>
              <h3><?php echo $translate[$lang]['now_date']; ?> <?php echo $creationDate; ?></h3>
              <?php
              if(strlen($info->reference)>0){
              ?>
                  <h3><?php echo $translate[$lang]['reference'];?>: <?php echo $info->reference; ?></h3>
              <?php
              }
              ?>
              <div id="bill-sender">
                  <h4><?php echo $translate[$lang]['sender_data']; ?>:</h4>
                  <ul>
                      <li><strong><?php echo $translate[$lang]['nameFrom']; ?>:</strong> <?php echo $info->sender_name; ?></li>
                  </ul>
              </div>
              <div id="bill-addressee">
                  <h4><?php echo $translate[$lang]['addressee_data']; ?>:</h4>
                  <ul>
                      <li><strong><?php echo $translate[$lang]['nameTo']; ?>:</strong> <?php echo $info->addressee_name; ?></li>
                  </ul>
              </div>
          </div>
          <div id="bill-body">
              <!-- apareixeran els items-->
              <table id="bill-items">
                <tr>
                  <th><?php echo $translate[$lang]['item_description']; ?></th>
                </tr>
              <?php
                $total = 0;
                $replacePatterns   = array("\r\n", "\n", "\r");
                $replaceString = '<br />';
                foreach($items as $i){
                  echo '<tr>';
                    echo '<td class="left-align">'.str_replace($replacePatterns, $replaceString, $i->description).'</td>';
                  echo '</tr>';
                  $total = $total + ($i->price*$i->amount-$i->discount);
                }
              ?>
              </table>
          </div>    
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
  <script src="js/json2.js"></script>

  <script src="js/libs/bootstrap/bootstrap.min.js"></script>

  <script src="js/plugins.js"></script>
  <script src="js/libs/bootstrap/datepicker.js"></script>
  <script src="js/script.js"></script>
</body>
</html>
