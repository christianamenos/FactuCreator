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
            <li><a href="index.php">Facturas/Albaranes <span class="icon icon-th-list icon-white"></span></a></li>
            <li class="active"><a href="business.php">Clientes/Empresas <span class="icon icon-user icon-white"></span></a></li>
            <li><a href="contact.php">Contacto <span class="icon icon-envelope icon-white"></span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
  </nav>

  <div class="container">
    <?php
    $dataSentAndCorrect = false;
    $insertedDataOk = false;
    $uploadedNewLogo = false;
    $formOldValueLogo = '';
    
    $formErrorNameMessage = '';
    $formErrorMailMessage = '';
    $formErrorOwnMessage = '';
    $formErrorCifMessage = '';
    $formErrorPhoneMessage = '';
    $formErrorFaxMessage = '';
    $formErrorWebpageMessage = '';
    $formErrorLogoMessage = '';
    $formErrorCountryMessage = '';
    $formErrorProvinceMessage = '';
    $formErrorCityMessage = '';
    $formErrorPostalCodeMessage = '';
    $formErrorStreetMessage = '';
    $formErrorNumberMessage = '';
    $formErrorFloorMessage = '';
    $formErrorDoorMessage = '';
    $formErrorBankAccountMessage = '';
    
    if(filter_has_var(INPUT_GET, 'id') && is_numeric($_GET['id'])){
      $reqId = $_GET['id'];
      if(filter_has_var(INPUT_POST, 'submit')){

        if(filter_has_var(INPUT_POST, 'name')){
          $filtred_str = htmlspecialchars(trim($_POST['name']), ENT_QUOTES);
          if($filtred_str!=='' && strlen($filtred_str)<256){
            $formValueName = $filtred_str;
          }
          else{
            $formErrorName = true;
            $formErrorNameMessage = "Error, el campo no puede estar vac&iacute;o, y no debe rebasar los 256 caracteres.";
          }
        }

        if(filter_has_var(INPUT_POST, 'own')){
          $filtred_str = trim($_POST['own']);
          if($filtred_str!=='' && is_numeric($filtred_str) && $filtred_str>=0 && $filtred_str <2){
            $formValueOwn = $filtred_str;
          }
          else{
            $formErrorOwn = true;
            $formErrorOwnMessage = "Error, el campo no puede ser vac&iacute;o, y ha de tener contener uno de los elemenots de la lista.";
          }
        }

        if(filter_has_var(INPUT_POST, 'cif')){
          $filtred_str = trim($_POST['cif']);
          $regex = '/^(([a-zA-Z]{1}\d{7}\w{1})|(\d{8}[a-zA-Z]{1}))$/';
          if($filtred_str!=='' && preg_match($regex, $filtred_str) && strlen($filtred_str)==9){
            $formValueCif = $filtred_str;
          }
          else{
            $formErrorCif = true;
            $formErrorCifMessage = "Error, el campo no puede ser vac&iacute;o, y ha de tener el formato de un CIF o un NIF.";
          }
        }

        if(filter_has_var(INPUT_POST, 'mail')){
          $filtred_str = trim($_POST['mail']);
          $regex = '/^\w+(\.[_\w]+)*@\w+(\.[_\w]+)*\.\w{2,4}$/';
          if($filtred_str!=='' && preg_match($regex, $filtred_str) && strlen($filtred_str)<256){
            $formValueMail = $filtred_str;
          }
          else{
            $formErrorMail = true;
            $formErrorMailMessage = "Error, el campo no puede ser vac&iacute;o, y ha de tener el formato de correo electr&oacute;nico correcto, con longitud menor a 256 caracteres.";
          }
        }

        if(filter_has_var(INPUT_POST, 'phone')){
          $filtred_str = trim($_POST['phone']);
          if($filtred_str!=='' && is_numeric($filtred_str) && strlen($filtred_str) == 9){
            $formValuePhone = $filtred_str;
          }
          else{
            $formErrorPhone = true;
            $formErrorPhoneMessage = "Error, el campo no puede ser vac&iacute;o, ha de consistir &uacute;nicamente en numeros, sin espacios ni otros caracteres adicionales.";
          }
        }

        $formValueFax = '';
        if(filter_has_var(INPUT_POST, 'fax')){
          $filtred_str = trim($_POST['fax']);
          if($filtred_str==='' || (is_numeric($filtred_str) && strlen($filtred_str) <= 9)){
            $formValueFax = $filtred_str;
          }
          else{
            $formErrorFax = true;
            $formErrorFaxMessage = "Error, el campo ha de consistir &uacute;nicamente en numeros, sin espacios ni otros caracteres adicionales.";
          }
        }

        $formValueWebpage = '';
        if(filter_has_var(INPUT_POST, 'webpage')){
          $filtred_str = trim($_POST['webpage']);
          $regex = '/^http(s)?:\/\/(w{3}\.)?\w+(\.\w+)*(\/[\w_\-\+\%]+(\.[\w]+)*)*(\?([\w_\-\+\%]+=[\w_\-\+\%]+)(\&[\w_\-\+\%]+=[\w_\-\+\%]+)*)?$/';
          if(($filtred_str==='' || preg_match($regex, $filtred_str)) && strlen($filtred_str) < 256){
            $formValueWebpage = $filtred_str;
          }
          else{
            $formErrorWebpage = true;
            $formErrorWebpageMessage = "Error, el campo ha de tener formato de url, con http:// o https:// delante, y tener una longitud menor a 256 caracteres.";
          }
        }

        $formValueCountry = '';
        if(filter_has_var(INPUT_POST, 'country')){
          $filtred_str = htmlspecialchars(trim($_POST['country']), ENT_QUOTES);
          if($filtred_str!=='' && strlen($filtred_str)<256){
            $formValueCountry = $filtred_str;
          }
          else{
            $formErrorCountry = true;
            $formErrorCountryMessage = "Error, el campo no puede estar vac&iacute;o, y no debe rebasar los 256 caracteres.";
          }
        }

        $formValueProvince = '';
        if(filter_has_var(INPUT_POST, 'province')){
          $filtred_str = htmlspecialchars(trim($_POST['province']), ENT_QUOTES);
          if($filtred_str!=='' && strlen($filtred_str)<256){
            $formValueProvince = $filtred_str;
          }
          else{
            $formErrorProvince = true;
            $formErrorProvinceMessage = "Error, el campo no puede estar vac&iacute;o, y no debe rebasar los 256 caracteres.";
          }
        }

        $formValueCity = '';
        if(filter_has_var(INPUT_POST, 'city')){
          $filtred_str = htmlspecialchars(trim($_POST['city']), ENT_QUOTES);
          if($filtred_str!=='' && strlen($filtred_str)<256){
            $formValueCity = $filtred_str;
          }
          else{
            $formErrorCity = true;
            $formErrorCityMessage = "Error, el campo no puede estar vac&iacute;o, y no debe rebasar los 256 caracteres.";
          }
        }

        $formValuePostalCode = '';
        if(filter_has_var(INPUT_POST, 'pc')){
          $filtred_str = trim($_POST['pc']);
          if($filtred_str!=='' && is_numeric($filtred_str) && strlen($filtred_str)<=5){
            $formValuePostalCode = $filtred_str;
          }
          else{
            $formErrorPostalCode = true;
            $formErrorPostalCodeMessage = "Error, el campo no puede estar vac&iacute;o, y debe ser un n&uacute;mero con menos de 5 cifras.";
          }
        }

        $formValueStreet = '';
        if(filter_has_var(INPUT_POST, 'street')){
          $filtred_str = htmlspecialchars(trim($_POST['street']), ENT_QUOTES);
          if($filtred_str!=='' && strlen($filtred_str)<256){
            $formValueStreet = $filtred_str;
          }
          else{
            $formErrorStreet = true;
            $formErrorStreetMessage = "Error, el campo no puede estar vac&iacute;o, y no debe rebasar los 256 caracteres.";
          }
        }

        $formValueNumber = '';
        if(filter_has_var(INPUT_POST, 'number')){
          $filtred_str = trim($_POST['number']);
          if($filtred_str!=='' && is_numeric($filtred_str) && strlen($filtred_str)<9){
            $formValueNumber = $filtred_str;
          }
          else{
            $formErrorNumber = true;
            $formErrorNumberMessage = "Error, el campo no puede estar vac&iacute;o, y debe ser un n&uacute;mero con menos de 9 cifras.";
          }
        }

        $formValueFloor = '';
        if(filter_has_var(INPUT_POST, 'floor')){
          $filtred_str = htmlspecialchars(trim($_POST['floor']), ENT_QUOTES);
          if(strlen($filtred_str)<9){
            $formValueFloor = $filtred_str;
          }
          else{
            $formErrorFloor = true;
            $formErrorFloorMessage = "Error, el campo debe constar de menos de 9 caracteres.";
          }
        }

        $formValueDoor = '';
        if(filter_has_var(INPUT_POST, 'door')){
          $filtred_str = htmlspecialchars(trim($_POST['door']), ENT_QUOTES);
          if(strlen($filtred_str)<9){
            $formValueDoor = $filtred_str;
          }
          else{
            $formErrorDoor = true;
            $formErrorDoorMessage = "Error, el campo debe constar de menos de 9 caracteres.";
          }
        }

        $formValueBankAccount = '';
        if(filter_has_var(INPUT_POST, 'ba')){
          $filtred_str = htmlspecialchars(trim($_POST['ba']), ENT_QUOTES);
          if((strlen($filtred_str)==20 && is_numeric($filtred_str)) || strlen($filtred_str)==0){
            $formValueBankAccount = base64_encode(md5('f4ct1r3 p4s5w0rD 54lT').strrev($filtred_str).'8471935601');
          }
          else{
            $formErrorBankAccount = true;
            $formErrorBankAccountMessage = "Error, el campo debe constar de 20 n&uacute;meros exactamente, sin espacios, guiones ni ning&uacute;n otro tipo de caracteres.";
          }
        }
        
        $formValueLogo = '';
        if(isset($_FILES['logo'])){
          if($_FILES['logo']['error']===0 && is_uploaded_file($_FILES['logo']['tmp_name']) && $_FILES['logo']['size']<= 3000000 && in_array($_FILES['logo']['type'], array("image/gif","image/jpeg","image/png"))){
            $type = explode('/', $_FILES['logo']['type']);
            $formValueLogo = getcwd().'/img/logos/logo_'.time().'.'.$type[1];
            move_uploaded_file($_FILES['logo']['tmp_name'], $formValueLogo);
            $uploadedNewLogo = true;
          }
          else if($_FILES['logo']['error']!==4){
            $formErrorLogo = true;
            $uploadedNewLogo = false;
            $formErrorLogoMessage = "Error, solo se aceptan im&aacute;genes en formatos jpg, png o gif, con un tama&ntilde;o m&aacute;ximo de archivo de 3 MB.";
          }
        }


        $dataSentAndCorrect = !isset($formErrorName)        && !isset($formErrorOwn)
                           && !isset($formErrorMail)        && !isset($formErrorPhone)
                           && !isset($formErrorFax)         && !isset($formErrorCif)
                           && !isset($formErrorWebpage)     && !isset($formErrorLogo)
                           && !isset($formErrorCountry)     && !isset($formErrorProvince)
                           && !isset($formErrorCity)        && !isset($formErrorStreet)
                           && !isset($formErrorNumber)      && !isset($formErrorFloor)
                           && !isset($formErrorPostalCode)  && !isset($formErrorDoor)
                           && !isset($formErrorBankAccount);

        if($dataSentAndCorrect){
          $dbhAux = new DB();
          $sqlAux = "SELECT logo
                     FROM company
                     WHERE id = :id";
          $stmtAux = $dbhAux->prepare($sqlAux);
          $stmtAux->execute(array(':id' => $reqId));
          $resultAux = $stmtAux->fetch(PDO::FETCH_OBJ);
          if($resultAux!==false){
            $formOldValueLogo = $resultAux->logo;
          }
          if($formValueLogo===''){
            $formValueLogo = $formOldValueLogo;
            $formOldValueLogo = '';
          }
          
          $dbh = new DB();
          $dbh->beginTransaction();
          $ba = '';
          if(strlen($formValueBankAccount)>0) $ba = ', bank_account=:bank_account';
          $sql = 'UPDATE company 
                  SET name=:name, own=:own, mail=:mail, cif=:cif, phone=:phone,
                      fax=:fax, webpage=:webpage, logo=:logo, country=:country,
                      province=:province, city=:city, postal_code=:postal_code,
                      street=:street, number=:number, floor=:floor, door=:door'.$ba.'
                  WHERE id=:id';
          $stmt = $dbh->prepare($sql);
          $values = array(':id'   => $reqId,
                               ':name'        => $formValueName,
                               ':own'         => $formValueOwn,
                               ':mail'        => $formValueMail,
                               ':cif'         => $formValueCif,
                               ':phone'       => $formValuePhone,
                               ':fax'         => $formValueFax,
                               ':webpage'     => $formValueWebpage,
                               ':logo'        => $formValueLogo,
                               ':country'     => $formValueCountry,
                               ':province'    => $formValueProvince,
                               ':city'        => $formValueCity,
                               ':postal_code' => $formValuePostalCode,
                               ':street'      => $formValueStreet,
                               ':number'      => $formValueNumber,
                               ':floor'       => $formValueFloor,
                               ':door'        => $formValueDoor);
          if(strlen($formValueBankAccount)>0) $values[':bank_account'] = $formValueBankAccount;
          $res = $stmt->execute($values);
          if($res){
            $insertedDataOk = true;
            $dbh->commit();
            if($formOldValueLogo !== ''){
              unlink($formOldValueLogo);
            }
          }
          else{
            $dbh->rollback();
          }
        }

        if((!$dataSentAndCorrect || !$insertedDataOk) && (isset($formValueLogo) && file_exists($formValueLogo))){
          unlink($formValueLogo);
        }

      }
      else{
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
        }
      }
      if(!$dataSentAndCorrect){
        //if(isset($formValueLogo) && file_exists($formValueLogo)) unlink($formValueLogo);
      ?>
        <div>
          <h2>A&ntilde;adir empresa o cliente</h2>
          <p>
            En esta secci&oacute;n puede a&ntilde;adir al sistema la informaci&oacute;n
            de una de sus empresas o bien de uno de sus clientes, completando el
            formulario que puede ver a continuaci&oacute;n.
          </p>
          <p>
            Note que hay una lista desplegable en la que puede indicar si lo que
            usted est&aacute; indicando se trata de una empresa de su propiedad, o
            bien un cliente, que ser&aacute; lo que distinguir&aacute; la creaci&oacute;n
            entre un tipo y otro.
          </p>
        </div>
        <div id="contact-form">
          <form method="post" action="edit-company.php?id=<?php echo $reqId; ?>" enctype="multipart/form-data" class="well">
            <legend>Formulario de empresa/cliente:</legend>
            <div class="row">
              <div class="span5">
                <div class="control-group <?php if(isset($formErrorName)) echo "error"; else if(isset($formValueName)) echo "success";?>">
                  <label class="required" for="name">Nombre de la empresa/cliente:</label>
                  <div class="controls">
                    <input name="name" <?php if(isset($formValueName)) echo "value=\"{$formValueName}\""; ?> type="text" placeholder="Nombre...">
                    <?php
                    if($formErrorNameMessage!==''){
                      echo '<p class="help-block">'.$formErrorNameMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorOwn)) echo "error"; else if(isset($formValueOwn)) echo "success";?>">
                  <label class="required" for="own">Cliente/Empresa en propiedad:</label>
                  <div class="controls">
                    <select name="own" type="text" placeholder="Asunto...">
                      <option <?php if(!isset($formValueOwn) ||  (isset($formValueOwn) && $formValueOwn==0)) echo 'selected="selected"'; ?> value="0">Cliente</option>
                      <option <?php if(isset($formValueOwn) && $formValueOwn==1) echo 'selected="selected"'; ?> value="1">Empresa de mi propiedad</option>
                    </select>
                    <?php
                    if($formErrorOwnMessage!==''){
                      echo '<p class="help-block">'.$formErrorOwnMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorCif)) echo "error";  else if(isset($formValueCif)) echo "success";?>">
                  <label class="required" for="cif">CIF/NIF/NIE:</label>
                  <div class="controls">
                    <input name="cif" <?php if(isset($formValueCif)) echo "value=\"{$formValueCif}\""; ?> type="text" placeholder="CIF/NIF/NIE...">
                    <?php
                    if($formErrorCifMessage!==''){
                      echo '<p class="help-block">'.$formErrorCifMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorMail)) echo "error";  else if(isset($formValueName)) echo "success";?>">
                  <label class="required" for="mail">Correo electr&oacute;nico:</label>
                  <div class="controls">
                    <input name="mail" <?php if(isset($formValueMail)) echo "value=\"{$formValueMail}\""; ?> type="text" placeholder="Correo electr&oacute;nico...">
                    <?php
                    if($formErrorMailMessage!==''){
                      echo '<p class="help-block">'.$formErrorMailMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorPhone)) echo "error";  else if(isset($formValuePhone)) echo "success";?>">
                  <label class="required" for="phone">Tel&eacute;fono:</label>
                  <div class="controls">
                    <input name="phone" <?php if(isset($formValuePhone)) echo "value=\"{$formValuePhone}\""; ?> type="text" placeholder="Tel&eacute;fono...">
                    <?php
                    if($formErrorPhoneMessage!==''){
                      echo '<p class="help-block">'.$formErrorPhoneMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorFax)) echo "error";  else if(isset($formValueFax)) echo "success";?>">
                  <label for="fax">Fax:</label>
                  <div class="controls">
                    <input name="fax" <?php if(isset($formValueFax)) echo "value=\"{$formValueFax}\""; ?> type="text" placeholder="Fax...">
                    <?php
                    if($formErrorFaxMessage!==''){
                      echo '<p class="help-block">'.$formErrorFaxMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorWebpage)) echo "error";  else if(isset($formValueWebpage)) echo "success";?>">
                  <label for="webpage">P&aacute;gina web:</label>
                  <div class="controls">
                    <input name="webpage" <?php if(isset($formValueWebpage)) echo "value=\"{$formValueWebpage}\""; ?> type="text" placeholder="P&aacute;gina web...">
                    <?php
                    if($formErrorWebpageMessage!==''){
                      echo '<p class="help-block">'.$formErrorWebpageMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>
                  
                <div class="control-group <?php if(isset($formErrorBankAccount)) echo "error"; else if(isset($formValueBankAccount)) echo "success";?>">
                  <label for="ba">Cuenta bancaria:</label>
                  <div class="controls">
                    <input name="ba" type="text" placeholder="N&uacute;mero de cuenta (sin espacios)...">
                    <?php
                    if($formErrorBankAccountMessage!==''){
                      echo '<p class="help-block">'.$formErrorBankAccountMessage.'</p>';
                    }
                  ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorLogo)) echo "error";?>">
                  <label for="logo">Logotipo:</label>
                  <div class="controls">
                    <input name="logo" type="file">
                    <?php
                    if($formErrorLogoMessage!==''){
                      echo '<p class="help-block">'.$formErrorLogoMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>
              </div>

              <div class="span5">
                <div class="control-group <?php if(isset($formErrorCountry)) echo "error";  else if(isset($formValueCountry)) echo "success";?>">
                  <label class="required" for="country">Pa&iacute;s:</label>
                  <div class="controls">
                    <input name="country" <?php if(isset($formValueCountry)) echo "value=\"{$formValueCountry}\""; ?> type="text" placeholder="Pa&iacute;s...">
                    <?php
                    if($formErrorCountryMessage!==''){
                      echo '<p class="help-block">'.$formErrorCountryMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorProvince)) echo "error";  else if(isset($formValueProvince)) echo "success";?>">
                  <label class="required" for="province">Provincia:</label>
                  <div class="controls">
                    <input name="province" <?php if(isset($formValueProvince)) echo "value=\"{$formValueProvince}\""; ?> type="text" placeholder="Provincia...">
                    <?php
                    if($formErrorProvinceMessage!==''){
                      echo '<p class="help-block">'.$formErrorProvinceMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorCity)) echo "error";  else if(isset($formValueCity)) echo "success";?>">
                  <label class="required" for="city">Poblaci&oacute;n:</label>
                  <div class="controls">
                    <input name="city" <?php if(isset($formValueCity)) echo "value=\"{$formValueCity}\""; ?> type="text" placeholder="Poblaci&oacute;n...">
                    <?php
                    if($formErrorCityMessage!==''){
                      echo '<p class="help-block">'.$formErrorCityMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorPostalCode)) echo "error";  else if(isset($formValuePostalCode)) echo "success";?>">
                  <label class="required" for="pc">C&oacute;digo postal:</label>
                  <div class="controls">
                    <input name="pc" <?php if(isset($formValuePostalCode)) echo "value=\"{$formValuePostalCode}\""; ?> type="text" placeholder="C&oacute;digo postal...">
                    <?php
                    if($formErrorPostalCodeMessage!==''){
                      echo '<p class="help-block">'.$formErrorPostalCodeMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorStreet)) echo "error"; else if(isset($formValueStreet)) echo "success";?>">
                  <label class="required" for="street">Calle:</label>
                  <div class="controls">
                    <input name="street" <?php if(isset($formValueStreet)) echo "value=\"{$formValueStreet}\""; ?> type="text" placeholder="Calle...">
                    <?php
                    if($formErrorStreetMessage!==''){
                      echo '<p class="help-block">'.$formErrorStreetMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorNumber)) echo "error"; else if(isset($formValueNumber)) echo "success";?>">
                  <label class="required" for="number">N&uacute;mero:</label>
                  <div class="controls">
                    <input name="number" <?php if(isset($formValueNumber)) echo "value=\"{$formValueNumber}\""; ?> type="text" placeholder="Num...">
                    <?php
                    if($formErrorNumberMessage!==''){
                      echo '<p class="help-block">'.$formErrorNumberMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorFloor)) echo "error"; else if(isset($formValueFloor)) echo "success";?>">
                  <label for="floor">Piso:</label>
                  <div class="controls">
                    <input name="floor" <?php if(isset($formValueFloor)) echo "value=\"{$formValueFloor}\""; ?> type="text" placeholder="Piso...">
                    <?php
                    if($formErrorFloorMessage!==''){
                      echo '<p class="help-block">'.$formErrorFloorMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

                <div class="control-group <?php if(isset($formErrorDoor)) echo "error"; else if(isset($formValueDoor)) echo "success";?>">
                  <label for="door">Puerta:</label>
                  <div class="controls">
                    <input name="door" <?php if(isset($formValueDoor)) echo "value=\"{$formValueDoor}\""; ?> type="text" placeholder="Puerta...">
                    <?php
                    if($formErrorDoorMessage!==''){
                      echo '<p class="help-block">'.$formErrorDoorMessage.'</p>';
                    }
                    ?>
                  </div>
                </div>

              </div>
            </div>

            <div class="form-actions">
              <p class="warning-form">
                <strong>Nota: </strong><em>todos los campos con el s&iacute;mbolo 
                (*) son necesarios. </em>
              </p>
              <p>
                <strong>Nota: </strong><em>Si por algun motivo desconoce el valor de
                alguno de los campos <strong>obligatorios</strong>, escriba un valor ficticio. Tanto
                en campos num&eacute;ricos (como el tel&eacute;fono), como en caso
                de campos de texto (como la calle) recomendamos usar un n&uacute;mero
                que se vea que es falso por si quiere editar ese campo a posteriori.</em>
              </p>
              <p>
                <strong>Por ejemplo:</strong> ( <strong>Tel&eacute;fono:</strong> 000000000, <strong>Calle:</strong> NSNC ).
              </p>
              <button name="submit" class="btn btn-primary" type="submit">Enviar</button>
              <button class="btn" type="reset">Cancelar</button>
            </div>
          </form>
        </div>
      <?php
      }
      else{
        if($insertedDataOk){
        ?>
          <div>
            <p class="data-ok-message">
              Los datos se han guardado correctamente.
            </p>
            <p>
              Puede volver a la pantalla principal mediante <a title="P&aacute;gina
              principal" href="index.php">ESTE</a> enlace. O puede a&ntilde;adir
              una nueva empresa o cliente mediante <a title="A&ntildeadir nuevo 
              cliente o empresa" href="new-company.php">ESTE</a> otro enlace.
            </p>
          </div>
        <?php
        }
        else{
        ?>
          <div>
            <p class="data-ko-message">
              Ha habido un problema al guardar los datos en el sistema. Pruebe de 
              nuevo y si el problema persiste, pongase en contacto con el 
              administrador de la aplicaci&oacute;n.
            </p>
            <p>
              Puede volver a la pantalla principal mediante <a title="P&aacute;gina
              principal" href="index.php">ESTE</a> enlace. O puede a&ntilde;adir
              una nueva empresa o cliente mediante <a title="A&ntildeadir nuevo 
              cliente o empresa" href="new-company.php">ESTE</a> otro enlace.
            </p>
          </div>
        <?php
        }
      }
    }
    else{
    ?>
      <div>
        <p class="data-ko-message">
          Para poder editar los datos de una empresa o cliente, se tiene que
          indicar cual se quiere editar.
        </p>
        <p>
          Por favor, edite mediante los enlaces de edici&oacute;n al lado de la
          empresa o cliente del que quiera editar los datos, y que puede encontrar
          en los listados de clientes o de empresas en <a title="P&aacute;gina
          principal" href="index.php">ESTE</a> enlace.
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
