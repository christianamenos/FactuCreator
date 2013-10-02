<?php
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

    <title>Edición de factura</title>
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
              <li><a href="business.php">Clientes/Empresas <span class="icon icon-user icon-white"></span></a></li>
              <li><a href="contact.php">Contacto <span class="icon icon-envelope icon-white"></span></a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </nav>

    <div class="container">
      <?php
      $info = array();
      $items = array();
      if (filter_has_var(INPUT_GET, 'id')
              && ctype_digit($_GET['id'])) {
        //imprimir formulario i tal, sino mensaje de error
        $billId = $_GET['id'];

        $formDataSent = false;
        if (filter_has_var(INPUT_POST, 'submit')) {
          //pillar les dades del que han enviat y guardar si son correctes
          $formDataSent = true;
        } else {
          //mostrar formulari amb les dades de la base de dades
          $dbh = new DB();
          $b = $dbh->beginTransaction();
          $ret = array();
          if ($b) {
            $pdoStmt = $dbh->prepare('SELECT id, expiration_date, pay_mode, sender_id, addressee_id, vat_id, language_id, reference
                                    FROM bill
                                    WHERE id = :bill_id');
            $res = $pdoStmt->execute(array(':bill_id' => $billId));
            if ($res) {
              $info = $pdoStmt->fetch(PDO::FETCH_OBJ);
              $pdoStmt = $dbh->prepare('SELECT *
                                      FROM item
                                      WHERE bill_id = :bill_id');
              $res = $pdoStmt->execute(array(':bill_id' => $billId));
              if ($res) {
                $items = $pdoStmt->fetchAll(PDO::FETCH_OBJ);
              } else {
                $formErrorId = true;
              }
            } else {
              $formErrorId = true;
            }
          } else {
            $formErrorId = true;
          }
        }
      }

      //preparando datos para el formulario (los selects)
      $dbh = new DB();
      $b = $dbh->beginTransaction();

      $ownCompanyList = array();
      if ($b) {
        $pdoStmt = $dbh->prepare('SELECT id, name 
                                FROM company
                                WHERE own = TRUE
                                ORDER BY name ASC');
        $res = $pdoStmt->execute();
        if ($res) {
          $ownCompanyList = $pdoStmt->fetchAll(PDO::FETCH_OBJ);
        }
      }

      $clientCompanyList = array();
      if ($b) {
        $pdoStmt = $dbh->prepare('SELECT id, name 
                                FROM company
                                WHERE own = FALSE
                                ORDER BY name ASC');
        $res = $pdoStmt->execute();
        if ($res) {
          $clientCompanyList = $pdoStmt->fetchAll(PDO::FETCH_OBJ);
        }
      }

      $vatList = array();
      if ($b) {
        $pdoStmt = $dbh->prepare('SELECT * 
                                FROM vat');
        $res = $pdoStmt->execute();
        if ($res) {
          $vatList = $pdoStmt->fetchAll(PDO::FETCH_OBJ);
        }
      }

      $languageList = array();
      if ($b) {
        $pdoStmt = $dbh->prepare('SELECT * 
                                FROM language');
        $res = $pdoStmt->execute();
        if ($res) {
          $languageList = $pdoStmt->fetchAll(PDO::FETCH_OBJ);
        }
      }

      $dataSentAndCorrect = false;
      $insertedDataOk = false;

      $formErrorSenderMessage = '';
      $formErrorAddresseeMessage = '';
      $formErrorPayModeMessage = '';
      $formErrorVatMessage = '';
      $formErrorLanguageMessage = '';
      $formErrorExpirationDateMessage = '';
      $formErrorItemsMessage = '';
      $formErrorReferenceMessage = '';

      if (filter_has_var(INPUT_POST, 'submit')) {

        if (filter_has_var(INPUT_POST, 'sender')
                && ctype_digit($_POST['sender'])) {
          $foundId = false;
          foreach ($ownCompanyList as $own) {
            if ($_POST['sender'] == $own->id) {
              $foundId = true;
              break;
            }
          }
          if ($foundId) {
            $formValueSender = $_POST['sender'];
          } else {
            $formErrorSender = true;
            $formErrorSenderMessage = "Error, ha indicado un valor inexistente. Seleccione uno de la lista.";
          }
        } else {
          $formErrorSender = true;
          $formErrorSenderMessage = "Error, ha indicado un valor inexistente. Seleccione uno de la lista.";
        }

        if (filter_has_var(INPUT_POST, 'addressee')
                && ctype_digit($_POST['addressee'])) {
          $foundId = false;
          foreach ($clientCompanyList as $cli) {
            if ($_POST['addressee'] == $cli->id) {
              $foundId = true;
              break;
            }
          }
          if ($foundId) {
            $formValueAddressee = $_POST['addressee'];
          } else {
            $formErrorAddressee = true;
            $formErrorAddresseeMessage = "Error, ha indicado un valor inexistente. Seleccione uno de la lista.";
          }
        } else {
          $formErrorAddressee = true;
          $formErrorAddresseeMessage = "Error, ha indicado un valor inexistente. Seleccione uno de la lista.";
        }

        if (filter_has_var(INPUT_POST, 'pay_mode')
                && ctype_digit($_POST['pay_mode'])
                && $_POST['pay_mode'] >= 0
                && $_POST['pay_mode'] <= 2) {
          $formValuePayMode = $_POST['pay_mode'];
        } else {
          $formErrorPayMode = true;
          $formErrorPayModeMessage = "Error, ha indicado un valor inexistente. Seleccione uno de la lista.";
        }

        if (filter_has_var(INPUT_POST, 'vat')
                && ctype_digit($_POST['vat'])) {
          $foundId = false;
          foreach ($vatList as $v) {
            if ($_POST['vat'] == $v->id) {
              $foundId = true;
              break;
            }
          }
          if ($foundId) {
            $formValueVat = $_POST['vat'];
          } else {
            $formErrorVat = true;
            $formErrorVatMessage = "Error, ha indicado un valor inexistente. Seleccione uno de la lista.";
          }
        } else {
          $formErrorVat = true;
          $formErrorVatMessage = "Error, ha indicado un valor inexistente. Seleccione uno de la lista.";
        }

        if (filter_has_var(INPUT_POST, 'expiration_date')
                && preg_match('/^[0-9]{2}[\-\/][0-9]{2}[\-\/][0-9]{4}$/', $_POST['expiration_date'])
                && checkdate(substr($_POST['expiration_date'], 3, 2), substr($_POST['expiration_date'], 0, 2), substr($_POST['expiration_date'], 6, 4))) {
          if ($foundId) {
            $formValueExpirationDate = substr($_POST['expiration_date'], 6, 4) . '-' . substr($_POST['expiration_date'], 3, 2) . '-' . substr($_POST['expiration_date'], 0, 2);
          } else {
            $formErrorExpirationDate = true;
            $formErrorExpirationDateMessage = "Error, ha indicado una fecha inv$aacute;lida.Indique una en el formato mostrado, con tantos d&iacute;gitos como se indica y separada por guiones. Nota: D = d&iacute;a, M = mes, A = a&ntilde;o";
          }
        } else {
          $formErrorExpirationDate = true;
          $formErrorExpirationDateMessage = "Error, ha indicado una fecha inv$aacute;lida.Indique una en el formato mostrado, con tantos d&iacute;gitos como se indica y separada por guiones. <br/><strong>Nota:</strong> D = D&iacute;a, M = Mes, A = A&ntilde;o.";
        }

        if (filter_has_var(INPUT_POST, 'reference')
                && strlen($_POST['reference']) <= 255) {
          if ($foundId) {
            $formValueReference = $_POST['reference'];
          } else {
            $formErrorReference = true;
            $formErrorReferenceMessage = "Error, la referencia indicada es inv$aacute;lida. El texto no puede sobrepasar los 255 caracteres.";
          }
        } else {
          $formErrorReference = true;
          $formErrorReferenceMessage = "Error, la referencia indicada es inv$aacute;lida. El texto no puede sobrepasar los 255 caracteres.";
        }

        if (filter_has_var(INPUT_POST, 'language')
                && ctype_digit($_POST['language'])) {
          $foundId = false;
          foreach ($languageList as $l) {
            if ($_POST['language'] == $l->id) {
              $foundId = true;
              break;
            }
          }
          if ($foundId) {
            $formValueLanguage = $_POST['language'];
          } else {
            $formErrorLanguage = true;
            $formErrorLanguageMessage = "Error, ha indicado un valor inexistente. Seleccione uno de la lista.";
          }
        } else {
          $formErrorLanguage = true;
          $formErrorLanguageMessage = "Error, ha indicado un valor inexistente. Seleccione uno de la lista.";
        }

        if (filter_has_var(INPUT_POST, 'items') && strlen($_POST['items']) > 0) {
          $itemList = json_decode($_POST['items']);
          if (is_array($itemList)) {
            foreach ($itemList as $i) {
              if (!isset($i->description)
                      || !isset($i->amount)
                      || !is_numeric($i->amount)
                      || !isset($i->price)
                      || !is_numeric($i->price)
                      || !isset($i->discount)
                      || !is_numeric($i->discount)) {
                $formErrorItems = true;
                $formErrorItemsMessage = "Error, los datos de los elementos de la factura no s&oacute;n correctos.";
                break;
              }
            }
            if (!isset($formErrorItems)) {
              $formValueItems = $itemList;
            }
          }
        } else {
          $formErrorItems = true;
          $formErrorItemsMessage = "Error, no se han a&ntilde;adido elementos a la factura.";
        }

        $dataSentAndCorrect = !isset($formErrorSender) && !isset($formErrorAddressee)
                && !isset($formErrorPayMode) && !isset($formErrorVat)
                && !isset($formErrorLanguage) && !isset($formErrorExpirationDate)
                && !isset($formErrorItems) && !isset($formErrorReference);

        if ($dataSentAndCorrect) {
          $dbh = new DB();
          $dbh->beginTransaction();
          $sql = 'INSERT INTO bill (id, sender_id, addressee_id, creation_date, expiration_date, payed, pay_mode, vat_id, language_id, reference, bank_data)
                VALUES (NULL, :sender_id, :addressee_id, :creation_date, :expiration_date, :payed, :pay_mode, :vat_id, :language_id, :reference, :bank_data)';
          $stmt = $dbh->prepare($sql);
          $res = $stmt->execute(array(':sender_id' => $formValueSender,
              ':addressee_id' => $formValueAddressee,
              ':creation_date' => date('Y-m-d', time()),
              ':expiration_date' => $formValueExpirationDate,
              ':payed' => false,
              ':pay_mode' => $formValuePayMode,
              ':vat_id' => $formValueVat,
              ':language_id' => $formValueLanguage,
              ':reference' => $formValueReference,
              ':bank_data' => ''));
          if ($res) {
            //recuperar el id, y guardar los items...
            $billId = $dbh->lastInsertId();
            $itemsInseredOk = true;
            foreach ($formValueItems as $item) {
              $sql = 'INSERT INTO item (id, description, amount, price, discount, photo, bill_id)
                      VALUES (NULL, :description, :amount, :price, :discount, :photo, :bill_id)';
              $stmt = $dbh->prepare($sql);
              $res = $stmt->execute(array(':description' => $item->description,
                  ':amount' => $item->amount,
                  ':price' => $item->price,
                  ':discount' => $item->discount,
                  ':photo' => '',
                  ':bill_id' => $billId));
              if (!$res) {
                $itemsInseredOk = false;
                break;
              }
            }
            if ($itemsInseredOk) {
              $insertedDataOk = true;
              $dbh->commit();
            } else {
              $dbh->rollback();
            }
          } else {
            $dbh->rollback();
          }
        }
      }
      if (!$dataSentAndCorrect) {
        //if(isset($formValueLogo) && file_exists($formValueLogo)) unlink($formValueLogo);
        ?>
        <div>
          <h2>A&ntilde;adir empresa o cliente</h2>
          <p>
            En esta secci&oacute;n puede a&ntilde;adir al sistema una nueva factura,
            seleccionando la empresa desde la que se desea facturar, la empresa o
            cliente destinataria de la factura, y a&ntilde;adiendo los diferentes
            productos o servicios que se quieran adjuntar a la factura.
          </p>
        </div>
        <div id="bill-form">
          <form id="bill-data-input" method="post" action="new-bill.php" class="well">
            <legend>Formulario de factura:</legend>
            <div class="row">
              <div class="span5">
                <h3>Datos generales de la factura:</h3>
                <div class="control-group <?php if (isset($formErrorSender)) echo "error"; else if (isset($formValueSender)) echo "success"; ?>">
                  <label class="required" for="sender">Seleccione la empresa emisora de la factura:</label>
                  <div class="controls">
                    <select name="sender" type="text">
                      <?php
                      $selected = '';
                      if (!isset($formValueSender))
                        $selected = 'selected="selected"';
                      foreach ($ownCompanyList as $company) {
                        if (isset($formValueSender) && ($company->id == $formValueSender))
                          $selected = 'selected="selected"';
                        echo '<option ' . $selected . ' value="' . $company->id . '">' . $company->name . '</option>';
                        $selected = '';
                      }
                      ?>
                    </select>
                    <!--input name="sender" <?php if (isset($formValueSender)) echo "value=\"{$formValueSender}\""; ?> type="text" placeholder="Nombre..."-->
                    <?php
                    if ($formErrorSenderMessage !== '') {
                      echo '<p class="help-block">' . $formErrorSenderMessage . '</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if (isset($formErrorAddressee)) echo "error"; else if (isset($formValueAddressee)) echo "success"; ?>">
                  <label class="required" for="addressee">Seleccione la empresa destinataria de la factura:</label>
                  <div class="controls">
                    <select name="addressee" type="text">
                      <?php
                      $selected = '';
                      if (!isset($formValueAddressee))
                        $selected = 'selected="selected"';
                      foreach ($clientCompanyList as $client) {
                        if (isset($formValueAddressee) && ($client->id == $formValueAddressee))
                          $selected = 'selected="selected"';
                        echo '<option ' . $selected . ' value="' . $client->id . '">' . $client->name . '</option>';
                        $selected = '';
                      }
                      ?>
                    </select>
                    <?php
                    if ($formErrorAddresseeMessage !== '') {
                      echo '<p class="help-block">' . $formErrorAddresseeMessage . '</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if (isset($formErrorPayMode)) echo "error"; else if (isset($formValuePayMode)) echo "success"; ?>">
                  <label class="required" for="pay_mode">Seleccione la forma en que se realizar&aacute; el pago:</label>
                  <div class="controls">
                    <select name="pay_mode">
                      <option <?php if (!isset($formValuePayMode) || (isset($formValuePayMode) && $formValuePayMode == 0)) echo 'selected="selected"'; ?> value="0">Transferencia bancaria</option>
                      <option <?php if (isset($formValuePayMode) && $formValuePayMode == 1) echo 'selected="selected"'; ?> value="1">Pago al contado</option>
                      <option <?php if (isset($formValuePayMode) && $formValuePayMode == 2) echo 'selected="selected"'; ?> value="2">Cheque</option>
                    </select>
                    <?php
                    if ($formErrorPayModeMessage !== '') {
                      echo '<p class="help-block">' . $formErrorPayModeMessage . '</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if (isset($formErrorVat)) echo "error"; else if (isset($formValueVat)) echo "success"; ?>">
                  <label class="required" for="vat">Seleccione el tipo de IVA a aplicar:</label>
                  <div class="controls">
                    <select name="vat" type="text">
                      <?php
                      $selected = '';
                      if (!isset($formValueVat))
                        $selected = 'selected="selected"';
                      foreach ($vatList as $vat) {
                        if (isset($formValueVat) && ($vat->id == $formValueVat))
                          $selected = 'selected="selected"';
                        echo '<option ' . $selected . ' value="' . $vat->id . '">' . $vat->category . ' (' . $vat->percentage . '%)</option>';
                        $selected = '';
                      }
                      ?>
                    </select>
                    <?php
                    if ($formErrorVatMessage !== '') {
                      echo '<p class="help-block">' . $formErrorVatMessage . '</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if (isset($formErrorExpirationDate)) echo "error"; else if (isset($formValueExpirationDate)) echo "success"; ?>">
                  <label class="required" for="expiration_date">Seleccione fecha de vencimiento:</label>
                  <div class="controls">
                    <input name="expiration_date" type="text" id="datepicker" placeholder="En fromato DD-MM-AAAA">
                    <div id="calContainer1" class="calContainer"></div>
                    <?php
                    if ($formErrorExpirationDateMessage !== '') {
                      echo '<p class="help-block">' . $formErrorExpirationDateMessage . '</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if (isset($formErrorReference)) echo "error"; else if (isset($formValueReference)) echo "success"; ?>">
                  <label for="reference">Escriba una referencia:</label>
                  <div class="controls">
                    <input name="reference" type="text" id="reference" placeholder="Referencia">
                    <?php
                    if ($formErrorReferenceMessage !== '') {
                      echo '<p class="help-block">' . $formErrorReferenceMessage . '</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if (isset($formErrorLanguage)) echo "error"; else if (isset($formValueLanguage)) echo "success"; ?>">
                  <label class="required" for="language">Seleccione idioma de la factura:</label>
                  <div class="controls">
                    <select name="language">
                      <?php
                      $selected = '';
                      if (!isset($formValueLanguage))
                        $selected = 'selected="selected"';
                      foreach ($languageList as $lang) {
                        if (isset($formValueLanguage) && ($lang->id == $formValueLanguage))
                          $selected = 'selected="selected"';
                        echo '<option ' . $selected . ' value="' . $lang->id . '">' . $lang->name . '</option>';
                        $selected = '';
                      }
                      ?>
                    </select>
                    <?php
                    if ($formErrorLanguageMessage !== '') {
                      echo '<p class="help-block">' . $formErrorLanguageMessage . '</p>';
                    }
                    ?>
                  </div>
                </div>
              </div>

              <div class="span5">
                <h3>Productos y/o servicios a facturar:</h3>
                <div id="product_service_container">
                  <table>
                    <tr id="item-table-header">
                      <th>Descripci&oacute;n</th>
                      <th>Cantidad</th>
                      <th>Precio</th>
                      <th>Descuento</th>
                      <th>Acciones</th>
                    </tr>
                    <tr id="non-products-info">
                      <td colspan="5">Actualmente no hay ning&uacute;n producto o servicio. Pulse en el bot&oacute;n inferior para a&ntilde;adir uno.</td>
                    </tr>
                    <tr id="add-item-to-bill">
                      <td class="add-elem-form" colspan="5"><button id="add-item-button" class="btn btn-success"><span class="icon icon-plus icon-white"></span> A&ntilde;adir otro producto o servicio</button></td>
                    </tr>
                    <tr id="add-item-to-bill-alert">
                      <td class="add-elem-form" colspan="5"></td>
                    </tr>
                  </table>
                </div>
                <div class="hidden">
                  <textarea name="items" id="product-textarea"></textarea>
                </div>
              </div>
            </div>

            <div class="form-actions">
              <p class="warning-form">
                <strong>Nota: </strong><em>todos los campos con el s&iacute;mbolo 
                  (*) son necesarios. </em>
              </p>
              <button name="submit" class="btn btn-primary" type="submit">Enviar</button>
              <button class="btn" type="reset">Cancelar</button>
            </div>
          </form>
        </div>
        <?php
      } else {
        if ($insertedDataOk) {
          ?>
          <div>
            <p class="data-ok-message">
              Los datos se han guardado correctamente.
            </p>
            <p>
              Puede volver a la pantalla principal mediante <a title="P&aacute;gina
                                                               principal" href="index.php">ESTE</a> enlace.  puede a&ntilde;adir
              una nueva factura mediante <a title="A&ntildeadir nueva factura" 
                                            href="new-company.php">ESTE</a> otro enlace.
            </p>
          </div>
          <?php
        } else {
          ?>
          <div>
            <p class="data-ko-message">
              Ha habido un problema al guardar los datos en el sistema. Pruebe de 
              nuevo, y si el problema persiste, p&oacute;ngase en contacto con el 
              administrador de la aplicaci&oacute;n.
            </p>
            <p>
              Puede volver a la pantalla principal mediante <a title="P&aacute;gina
                                                               principal" href="index.php">ESTE</a> enlace. O puede a&ntilde;adir
              una nueva factura mediante <a title="A&ntildeadir nueva factura" 
                                            href="new-bill.php">ESTE</a> otro enlace.
            </p>
          </div>
          <?php
        }
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
    <script src="js/script.js"></script>
    <script>
      var itemList = new Array();   
      var numItems = 0;
      var tempId = 1;
    
      function closeFormDialog(){
        $(document).unbind('keydown');
        $('#popupForm').remove();
        $('#opacDiv').remove();
      }
    
      function htmlEntities(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
      }
    
      function IsNumeric(num){
        return !isNaN(parseFloat(num)) && isFinite(num);
      }
    
      function comaDecimalToPointDecimal(str){
        return String(str).replace(/,/g, '.');
      }
    
      function deleteItem(item){
        var tempId = $(item).parent().parent().attr('id').match(/tempItemId-(\d+)/)[1];
        var i = 0;
        while(i<itemList.length){
          if(itemList[i].tempId == tempId){
            itemList.splice(i,1);
            break;
          }
          i++;
        }
        $(item).parent().parent().remove();
        numItems--;
        if(numItems <= 0){
          $('#non-products-info').removeClass('hidden');
        }
      }
    
      function editItem(item){
        var row = $(item).parent().parent();
        var cols = row.find('td');
        console.log(cols);
        var colDescription = $(cols[0]).text();
        var colAmount = $(cols[1]).text();
        var colPrice = $(cols[2]).text();
        var colDiscount = $(cols[3]).text();
      
        var elements  = '<div class="control-group"><label class="required" for="popup-description">Descripci&oacute;n del producto o servicio:</label><div class="controls"><textarea name="popup-description">'+colDescription+'</textarea><p class="help-block"></p></div></div>';
        elements += '<div class="control-group"><label class="required" for="popup-amount">Cantidad:</label><div class="controls"><input name="popup-amount" value="'+colAmount+'" type="text"/><p class="help-block"></p></div></div>';
        elements += '<div class="control-group"><label class="required" for="popup-price">Precio unitario:</label><div class="controls"><input name="popup-price" value="'+colPrice+'" type="text"/><p class="help-block"></p></div></div>';
        elements += '<div class="control-group"><label class="required" for="popup-discount">Descuento:</label><div class="controls"><input name="popup-discount" value="'+colDiscount+'" type="text"/><p class="help-block"></p></div></div>';
      
        var height = '380px';
        if($(window).height()<360) height = ($(window).height()-120)+'px';
        $('body').append('<div id="opacDiv" tabindex="0" class="popup-opacdiv" style="top:'+window.pageYOffset+'px;"></div>');
        $('body').append('<div id="popupForm" tabindex="1" class="popup-form-wrapper" style="top:'+window.pageYOffset+'px;"><div class="popup-form-close" ><a id="crossFormDialog" href="">X</a></div><div style="height:'+height+';" class="popup-form"><h4 class="popup-form-title">Formulario de producto o servicio</h4><form id="popup-form">'+elements+'<div class="right"><button class="btn" id="submitDialog">Aceptar</button><button class="btn" style="margin-left:20px;" id="cancelDialogButton" name="cancelDialogButton">Cancelar</button></div></form></div></div>');
      
        $('#crossFormDialog').bind('click', function(){
          closeFormDialog();
          return false;
        });
      
        $('#cancelDialogButton').bind('click', function(){
          closeFormDialog();
          return false;
        });
      
        $('#submitDialog').bind('click', function(){
          var allValid = validateForm();
          if(allValid){
            $(cols[0]).text(htmlEntities($("textarea[name=popup-description]").val()));
            $(cols[1]).text(comaDecimalToPointDecimal($("input[name=popup-amount]").val()));
            $(cols[2]).text(comaDecimalToPointDecimal($("input[name=popup-price]").val()));
            $(cols[3]).text(comaDecimalToPointDecimal($("input[name=popup-discount]").val()));
          
            var tempId = $(row).attr('id').match(/tempItemId-(\d+)/)[1];
            var i = 0;
            while(i<itemList.length){
              if(itemList[i].tempId == tempId){
                itemList[i].description = htmlEntities($("textarea[name=popup-description]").val());
                itemList[i].amount = comaDecimalToPointDecimal($("input[name=popup-amount]").val())
                itemList[i].price = comaDecimalToPointDecimal($("input[name=popup-price]").val())
                itemList[i].discount = comaDecimalToPointDecimal($("input[name=popup-discount]").val())
                break;
              }
              i++;
            }
            console.log(itemList);
          
            closeFormDialog();
          }
          return false;
        });
      
        $('#popup-form').bind('submit', function(){
          return false;
        });
      
        $(document).bind('keydown',function(e){
          if(e.keyCode == 27){
            closeFormDialog();
          } 
        });

        window.onscroll = function(){
          $('#opacDiv').css('top',window.pageYOffset);
          $('#popupForm').css('top',window.pageYOffset);
        };
      }
    
      function validateForm(){
        var allValid = true;
        var aux = htmlEntities($("textarea[name=popup-description]").val());
        if(aux.length == 0){
          allValid = false;
          $("textarea[name=popup-description]").parent().parent().addClass('error');
          $("textarea[name=popup-description]").parent().parent().find('p.help-block').html('El campo no puede estar vac&iacute;o.');
        }
        else{
          $("textarea[name=popup-description]").parent().parent().removeClass('error');
          $("textarea[name=popup-description]").parent().parent().addClass('success');
          $("textarea[name=popup-description]").parent().parent().find('p.help-block').html('');
        }
      
        aux = comaDecimalToPointDecimal($("input[name=popup-amount]").val());
        if(aux.length == 0 || !IsNumeric(aux) || parseFloat(aux)<0){
          allValid = false;
          $("input[name=popup-amount]").parent().parent().addClass('error');
          $("input[name=popup-amount]").parent().parent().find('p.help-block').html('El campo no puede estar vac&iacute;o y ha de ser un n&uacute;mero positivo.');
        }
        else{
          $("input[name=popup-amount]").parent().parent().removeClass('error');
          $("input[name=popup-amount]").parent().parent().addClass('success');
        }
      
        aux = comaDecimalToPointDecimal($("input[name=popup-price]").val());
        if(aux.length == 0 || !IsNumeric(aux) || parseFloat(aux)<0){
          allValid = false;
          $("input[name=popup-price]").parent().parent().addClass('error');
          $("input[name=popup-price]").parent().parent().find('p.help-block').html('El campo no puede estar vac&iacute;o y ha de ser un n&uacute;mero positivo.');
        }
        else{
          $("input[name=popup-price]").parent().parent().removeClass('error');
          $("input[name=popup-price]").parent().parent().addClass('success');
        }
      
        aux = comaDecimalToPointDecimal($("input[name=popup-discount]").val());
        if(aux.length == 0 || !IsNumeric(aux) || parseFloat(aux)<0){
          allValid = false;
          $("input[name=popup-discount]").parent().parent().addClass('error');
          $("input[name=popup-discount]").parent().parent().find('p.help-block').html('El campo no puede estar vac&iacute;o y ha de ser un n&uacute;mero positivo.');
        }
        else{
          $("input[name=popup-discount]").parent().parent().removeClass('error');
          $("input[name=popup-discount]").parent().parent().addClass('success');
        }
      
        return allValid;
      }
    
      $('#bill-data-input').bind('submit', function (){
        //continuar si hay productos o servicios, sino avisar de que no hay elementos
        if(numItems > 0){
          $('#product-textarea').val(JSON.stringify(itemList));
          return true;
        }
        else{
          //avisar de que al menos ha de haber un elemento...
          $('html, body').animate({scrollTop:0}, 'slow');
          $('#add-item-to-bill-alert > td').html('<div class="alert alert-error"><button data-dismiss="alert" class="close" type="button">×</button>Para poder crear la factura se ha de a&ntilde;adir al menos un producto o servicio. Para hacerlo haga click al bot&oacute;n verde que esta justo encima.</div>');
          return false;
        }
        return false;
      });

      $('#add-item-button').bind('click', function (){
        var elements  = '<div class="control-group"><label class="required" for="popup-description">Descripci&oacute;n del producto o servicio:</label><div class="controls"><textarea name="popup-description"></textarea><p class="help-block"></p></div></div>';
        elements += '<div class="control-group"><label class="required" for="popup-amount">Cantidad:</label><div class="controls"><input name="popup-amount" type="text"/><p class="help-block"></p></div></div>';
        elements += '<div class="control-group"><label class="required" for="popup-price">Precio unitario:</label><div class="controls"><input name="popup-price" type="text"/><p class="help-block"></p></div></div>';
        elements += '<div class="control-group"><label class="required" for="popup-discount">Descuento:</label><div class="controls"><input name="popup-discount" type="text"/><p class="help-block"></p></div></div>';
      
        var height = '380px';
        if($(window).height()<360) height = ($(window).height()-120)+'px';
        $('body').append('<div id="opacDiv" tabindex="0" class="popup-opacdiv" style="top:'+window.pageYOffset+'px;"></div>');
        $('body').append('<div id="popupForm" tabindex="1" class="popup-form-wrapper" style="top:'+window.pageYOffset+'px;"><div class="popup-form-close" ><a id="crossFormDialog" href="">X</a></div><div style="height:'+height+';" class="popup-form"><h4 class="popup-form-title">Formulario de producto o servicio</h4><form id="popup-form">'+elements+'<div class="right"><button class="btn" id="submitDialog">Aceptar</button><button class="btn" style="margin-left:20px;" id="cancelDialogButton" name="cancelDialogButton">Cancelar</button></div></form></div></div>');
      
        $('#crossFormDialog').bind('click', function(){
          closeFormDialog();
          return false;
        });
      
        $('#cancelDialogButton').bind('click', function(){
          closeFormDialog();
          return false;
        });
      
        $('#submitDialog').bind('click', function(){
          var allValid = validateForm();
          if(allValid){
            //Añadir el elemento tbn al textarea
            $('#item-table-header').after('<tr id="tempItemId-'+tempId+'" class="item"><td>'+htmlEntities($("textarea[name=popup-description]").val())+'</td><td>'+comaDecimalToPointDecimal($("input[name=popup-amount]").val())+'</td><td>'+comaDecimalToPointDecimal($("input[name=popup-price]").val())+'</td><td>'+comaDecimalToPointDecimal($("input[name=popup-discount]").val())+'</td><td><span class="icon icon-edit clickable"></span> <span class="icon icon-remove clickable"></span></td></tr>');
            tempId++;
            numItems++;
            if(numItems >= 1){
              $('#non-products-info').addClass('hidden');
            }
            $('#tempItemId-'+(tempId-1)).find('.icon-remove').bind('click', function(event) {
              deleteItem(event.target);
            });
            $('#tempItemId-'+(tempId-1)).find('.icon-edit').bind('click', function(event) {
              editItem(event.target);
            });
            itemList.push({tempId:(tempId-1), description: htmlEntities($("textarea[name=popup-description]").val()), amount: comaDecimalToPointDecimal($("input[name=popup-amount]").val()), price: comaDecimalToPointDecimal($("input[name=popup-price]").val()), discount: comaDecimalToPointDecimal($("input[name=popup-discount]").val())});
            closeFormDialog();
          }
          return false;
        });
      
        $('#popup-form').bind('submit', function(){
          return false;
        });
      
        $(document).bind('keydown',function(e){
          if(e.keyCode == 27){
            closeFormDialog();
          } 
        });

        window.onscroll = function(){
          $('#opacDiv').css('top',window.pageYOffset);
          $('#popupForm').css('top',window.pageYOffset);
        };
      
        return false;
      });
    </script>
  </body>
</html>
