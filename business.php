<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>Clientes/Empresas</title>
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
    <div class="tabbable tabs-left">
      <ul class="nav nav-tabs">
        <li class="active">
          <a id="tab-client-list" data-toggle="tab" href="#lCL">Listar clientes</a>
        </li>
        <li class="">
          <a id="tab-company-view" data-toggle="tab" href="#lCV">Datos empresas</a>
        </li>
      </ul>
      <div class="tab-content">
        <div id="lCL" class="tab-pane active">
          <h2>Secci&oacute;n de clientes</h2>
          <p>
            En esta secci&oacute;n se muestran la lista de empresas que han sido
            clientes anteriormente. Desde esta misma secci&oacute;n tambi&eacute;n
            puede a&ntilde;adir nuevos clientes, para usarlos (tanto clientes
            antiguos como nuevos) como destinatarios en facturas.
          </p>
          <a class="bottom-space" href="new-company.php"><div class="btn btn-primary btn-small"><span class="icon icon-plus icon-white"></span> A&ntilde;adir cliente</div></a>
          <div id="client-list">
            
          </div>
        </div>
        <div id="lCV" class="tab-pane">
          <h2>Secci&oacute;n de datos de empresa</h2>
          <p>
            En esta secci&oacute;n se muestran los datos de las empresas de su
            posesi&oacute;n.
          </p>
          <p>
            En caso de tener m&aacute;s de una empresa asociada a usted, le
            aparecer&aacute; un desplegable con el nombre de cada una para que
            usted pueda seleccionar cual de ellas quiere ver, o editar. En este
            &uacute;ltimo caso, solamente deber&aacute; pulsar el bot&oacute;n
            de edici&oacute;n que aparecer&aacute; en seleccionar la empresa
            deseada.
          </p>
          <a class="bottom-space" href="new-company.php"><div class="btn btn-primary btn-small"><span class="icon icon-plus icon-white"></span> A&ntilde;adir cliente</div></a>
          <div id="company-view">
            
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
    function setRemoveCompany(){
      $('.company-remove').click(function() {
        var r=confirm("¿Está seguro que desea eliminar esta empresa y todas sus facturas del sistema?");
        var elem = $(this);
        if (r==true){
          $.ajax({
            url: "ajax-delete-company.php?id="+$(this).attr('id'),
            success: function(response) {
              var res = eval(response);
              if(res){
                $(elem).parent().parent().remove();
              }
              else{
                alert("Ha habido algun problema y no se ha podido borrar el elemento.");
              }
            },
            error: function() {
              alert("Ha habido algun problema y no se ha podido borrar el elemento.");
            }
          });
        }
        return false;
      });
    }
    
    $(document).ready(function(){
      $.ajax({
        url: "ajax-get-companies-data.php",
        success: function(response) {
          var companies = eval(response);
          var html = '<table>';
          html += '<tr><th>Nombre de la empresa</th><th>Acciones</th></tr>';
          for(var i=0; i<companies.length; i++){
            html += '<tr><td><a title="Ver en detalle" href="view-company.php?id='+companies[i].id+'">'+companies[i].name+'</a></td><td><a title="Editar" href="edit-company.php?id='+companies[i].id+'"><span class="icon-edit"></span></a> <a id="'+companies[i].id+'" class="company-remove" title="Eliminar" href="#"><span class="icon-remove"></span></a></tr>';
          }
          html += '</table>';
          $("#client-list").html(html);
          setRemoveCompany();
        }
      });
      
      $('#tab-client-list').bind('click', function(){
        $.ajax({
          url: "ajax-get-companies-data.php",
          success: function(response) {
            var companies = eval(response);
            var html = '<table>';
            html += '<tr><th>Nombre de la empresa</th><th>Acciones</th></tr>';
            for(var i=0; i<companies.length; i++){
              html += '<tr><td><a title="Ver en detalle" href="view-company.php?id='+companies[i].id+'">'+companies[i].name+'</a></td><td><a title="Editar" href="edit-company.php?id='+companies[i].id+'"><span class="icon-edit"></span></a> <a id="'+companies[i].id+'" class="company-remove" title="Eliminar" href="#"><span class="icon-remove"></span></a></tr>';
            }
            html += '</table>';
            $("#client-list").html(html);
            setRemoveCompany();
          }
        });
      });
      
      $('#tab-company-view').bind('click', function(){
        $.ajax({
          url: "ajax-get-own-companies-data.php",
          success: function(response) {
            var companies = eval(response);
            var html = '<table>';
            html += '<tr><th>Nombre de la empresa</th><th>Acciones</th></tr>';
            for(var i=0; i<companies.length; i++){
              html += '<tr><td><a title="Ver en detalle" href="view-company.php?id='+companies[i].id+'">'+companies[i].name+'</a></td><td><a title="Editar" href="edit-company.php?id='+companies[i].id+'"><span class="icon-edit"></span></a> <a id="'+companies[i].id+'" class="company-remove" title="Eliminar" href="#"><span class="icon-remove"></span></a></tr>';
            }
            html += '</table>';
            $("#company-view").html(html);
            setRemoveCompany();
          }
        });
      });
      
    });
  </script>

</body>
</html>
