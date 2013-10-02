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
            <li><a href="business.php">Clientes/Empresas <span class="icon icon-user icon-white"></span></a></li>
            <li class="active"><a href="contact.php">Contacto <span class="icon icon-envelope icon-white"></span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
  </nav>
  <div class="container">
    <?php
    $dataSentAndCorrect = false;
    $formErrorNameMessage = '';
    $formErrorMailMessage = '';
    $formErrorSubjectMessage = '';
    $formErrorBodyMessage = '';
    if(filter_has_var(INPUT_POST, 'submit')){
      
      if(filter_has_var(INPUT_POST, 'name')){
        $filtred_str = htmlspecialchars(trim($_POST['name'], ENT_QUOTES));
        if($filtred_str!=='' && strlen($filtred_str)<121){
          $formValueName = $filtred_str;
        }
        else{
          $formErrorName = true;
          $formErrorNameMessage = "Error, el campo no puede estar vac&iacute;o, y no debe rebasar los 120 caracteres.";
        }
      }
      
      if(filter_has_var(INPUT_POST, 'mail')){
        $filtred_str = trim($_POST['mail']);
        $regex = '/^\w+(\.[_\w]+)*@\w+(\.[_\w]+)*\.\w{2,4}$/';
        if($filtred_str!=='' && preg_match($regex, $filtred_str)){
          $formValueMail = $filtred_str;
        }
        else{
          $formErrorMail = true;
          $formErrorMailMessage = "Error, el campo no puede ser vac&iacute;o, y ha de tener el formato de correo electr&oacute;nico correcto.";
        }
      }
      
      if(filter_has_var(INPUT_POST, 'subject')
      && ctype_digit($_POST['subject'])
      && $_POST['subject']>=0
      && $_POST['subject']<=2){
        $formValueSubject = $_POST['mail'];
      }
      else{
        $formErrorSubject = true;
        $formErrorSubjectMessage = "Error, ha indicado un valor inexistente. Seleccione uno de la lista.";
      }

      
      if(filter_has_var(INPUT_POST, 'body')){
        $filtred_str = htmlspecialchars(trim($_POST['body'], ENT_QUOTES));
        if(strlen($filtred_str) > 10){
          $formValueBody = $filtred_str;
        }
        else{
          $formErrorBody = true;
          $formErrorBodyMessage = "Error, con tal de poder escuchar su petici&oacute;n, duda o sugerencia, necesitamos una explicaci&oacute;n m&aacute;s extensa.";
        }
      }

      $dataSentAndCorrect = !isset($formErrorName) && !isset($formErrorMail)
                         && !isset($formErrorSubject) && !isset($formErrorBody);
    }
    if(!$dataSentAndCorrect){
    ?>
    <div>
      <h2>Contacto</h2>
      <p>
        Si tiene alguna duda sobre el funcionamiento del sofware, encuentra
        un mal funcionamiento en alguna funcinalidad o bien tiene alguna
        sugerencia para mejorar el funcionamiento de la aplicaci&oacute;n,
        p&oacute;ngase en contacto con el administrador de la aplicaci&oacute;n
        mediante el siguiente formulario de contacto.
      </p>
    </div>
    <div id="contact-form">
      <form method="post" action="contact.php" class="well">
        <legend>Formulario de contacto:</legend>
        <div class="control-group <?php if(isset($formErrorName)) echo "error"; else if(isset($formValueName)) echo "success";?>">
          <label class="required" for="name">Nombre:</label>
          <div class="controls">
            <input name="name" <?php if(isset($formValueName)) echo "value=\"{$formValueName}\""; ?> type="text" placeholder="Su nombre...">
            <?php
            if($formErrorNameMessage!==''){
              echo '<p class="help-block">'.$formErrorNameMessage.'</p>';
            }
            ?>
          </div>
        </div>
        <div class="control-group <?php if(isset($formErrorMail)) echo "error";  else if(isset($formValueName)) echo "success";?>">
          <label class="required" for="mail">Correo electr&oacute;nico:</label>
          <div class="controls">
            <input name="mail" <?php if(isset($formValueMail)) echo "value=\"{$formValueMail}\""; ?> type="text" placeholder="Su correo electr&oacute;nico...">
            <?php
            if($formErrorMailMessage!==''){
              echo '<p class="help-block">'.$formErrorMailMessage.'</p>';
            }
            ?>
          </div>
        </div>
        <div class="control-group <?php if(isset($formErrorSubject)) echo "error"; else if(isset($formValueSubject)) echo "success";?>">
          <label class="required" for="subject">Asunto:</label>
          <div class="controls">
            <select name="subject" type="text" placeholder="Asunto...">
              <option <?php if(!isset($formValueSubject) ||  (isset($formValueSubject) && $formValueSubject==0)) echo 'selected="selected"'; ?> value="0">Consulta</option>
              <option <?php if(isset($formValueSubject) && $formValueSubject==1) echo 'selected="selected"'; ?> value="1">Reportar mal funcionamiento</option>
              <option <?php if(isset($formValueSubject) && $formValueSubject==2) echo 'selected="selected"'; ?> value="2">Sugerencia</option>
            </select>
            <?php
            if($formErrorSubjectMessage!==''){
              echo '<p class="help-block">'.$formErrorSubjectMessage.'</p>';
            }
            ?>
          </div>
        </div>
        <div class="control-group <?php if(isset($formErrorBody)) echo "error";  else if(isset($formValueBody)) echo "success";?>">
          <label class="required" for="body">Mensaje:</label>
          <div class="controls">
            <textarea name="body" type="text" placeholder="Texto del mensaje..."><?php if(isset($formValueBody)) echo $formValueBody; ?></textarea>
            <?php
            if($formErrorBodyMessage!==''){
              echo '<p class="help-block">'.$formErrorBodyMessage.'</p>';
            }
            ?>
          </div>
        </div>
        <div class="form-actions">
          <p class="warning-form">
            <strong>Nota: </strong><em>todos los campos con el s&iacute;mbolo 
            (*) son necesarios.</em>
          </p>
          <button name="submit" class="btn btn-primary" type="submit">Enviar</button>
          <button class="btn" type="reset">Cancelar</button>
        </div>
      </form>
    </div>
    <?php
    }
    else{
      if($formValueSubject==0){
        $formValueSubject = 'Consulta';
      }
      else if($formValueSubject==1){
        $formValueSubject = 'Reportar mal funcionamiento';
      }
      else if($formValueSubject==2){
        $formValueSubject = 'Sugerencia';
      }
      else{
        $formValueSubject = 'Otra opci&oacute;n';
      }
      $mailSent = @mail('christian.amenos@gmail.com', $formValueSubject,
                       strip_tags('Consulta de '+$formValueName+', con mail '+strip_tags($formValueMail)+'\n'+$formValueBody));
      if($mailSent){
        ?>
        <div class="data-ok-message">
          <p>
            Los datos se han enviado correctamente. Gracias por interesarse en la 
            aplicaci&oacute;n.
          </p>
          <p>
            Si tiene cualquier otra duda o sugerencia no dude en contactar nuevamente
            con el administrador de la aplicaci&oacute;n.
          </p>
          <p>
            Puede volver a la pantalla principal mediante <a href="index.php">ESTE</a>
            enlace.
          </p>
        </div>
        <?php
      }else{
      ?>
        <div class="data-ko-message">
          <p>
            No se ha podido mandar la consulta. Es posible que el servidor de correo electrónico no esté bien configurado o falten datos en el formulario.
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
