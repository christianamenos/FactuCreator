<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>Facturas/Albaranes</title>
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

    <div class="tabbable tabs-left">
      <ul class="nav nav-tabs">
        <li class="active">
          <a id="tab-bill-all" data-toggle="tab" href="#lA">Todas las facturas</a>
        </li>
        <li class="">
          <a id="tab-bill-open" data-toggle="tab" href="#lP">Facturas pendientes</a>
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
          <a class="bottom-space" href="new-bill.php"><div class="btn btn-primary btn-small"><span class="icon icon-plus icon-white"></span> Crear factura</div></a>
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
          <a class="bottom-space" href="new-bill.php"><div class="btn btn-primary btn-small"><span class="icon icon-plus icon-white"></span> Crear factura</div></a>
          <div id="bill-open">
            
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
          <a class="bottom-space" href="new-bill.php"><div class="btn btn-primary btn-small"><span class="icon icon-plus icon-white"></span> Crear factura</div></a>
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
    function setBillRemoveAndChangeStatus(){
      $('.bill-remove').click(function() {
        var r=confirm("¿Está seguro que desea eliminar esta factura y todos los productos asociados a la misma?");
        var elem = $(this);
        if (r==true){
          $.ajax({
            url: "ajax-delete-bill.php?id="+$(this).attr('id'),
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
      
      $('.bill-change-status').click(function() {
        var r=confirm("¿Está seguro que desea marcar cambiar el estado de pago de la factura seleccionada?");
        var elem = $(this);
        var toStatus = 0;
        if($(this).hasClass('not-payed')){
          toStatus = 1;
          $(this).removeClass('not-payed');
          $(this).addClass('payed');
          $(this).removeClass('icon-remove-circle');
          $(this).addClass('icon-ok-circle');
        }
        else{
          $(this).removeClass('payed');
          $(this).addClass('not-payed');
          $(this).removeClass('icon-ok-circle');
          $(this).addClass('icon-remove-circle');  
        }
        if (r==true){
          $.ajax({
            url: "ajax-change-bill-state.php?id="+$(this).attr('id')+'&to='+toStatus,
            success: function(response) {
              var res = eval(response);
              if(res){
                  if($(elem).parent().parent().parent().parent().parent().attr('id')!='bill-all'){
                      $(elem).parent().parent().remove();
                  }
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
    
    function ajaxLoadBillsByMode(mode){
       $.ajax({
        url: "ajax-get-"+mode+"-bills.php",
        success: function(response) {
          var bills = eval(response);
          if(bills.length>0){
            var html = '<table>';
            html += '<tr><th>Empresa emisora</th><th>Empresa receptora</th><th>Fecha emisi&oacute;n</th><th>Acciones de facturas</th><th>Acciones de albaranes</th></tr>';
            for(var i=0; i<bills.length; i++){
             var payed = 'payed';
             var payedIcon = 'icon-ok-circle';
             if(bills[i].payed == 0){
                 payed = 'not-payed';
                 payedIcon = 'icon-remove-circle';
             }
             var c_d = bills[i].creation_date.split('-');
             html += '<tr><td>'+bills[i].sender_name+'</td><td>'+bills[i].addressee_name+'</td><td>'+c_d[2]+'-'+c_d[1]+'-'+c_d[0]+'</td><td><a title="Ver en detalle" href="view-bill.php?id='+bills[i].id+'"><span class="icon-search"></span></a> <a title="Editar" href="edit-bill.php?id='+bills[i].id+'"><span class="icon-edit"></span></a> <a id="'+bills[i].id+'" class="bill-remove" title="Eliminar" href="#"><span class="icon-remove"></span></a> <a id="'+bills[i].id+'" class="bill-change-status '+payed+'" title="Cambiar estado" href="#"><span class="'+payedIcon+'"></span></a> <a title="Ver en pdf" href="pdf-bill.php?id='+bills[i].id+'"><span class="icon-download-alt"></span></a></td><td><a title="Ver en detalle" href="view-delivery-note.php?id='+bills[i].id+'"><span class="icon-search"></span></a> <a title="Ver en pdf" href="pdf-delivery-note.php?id='+bills[i].id+'"><span class="icon-download-alt"></span></a></td></tr>';
            }
            html += '</table>';
          }
          else{
              html = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>No hay resultados para listar.</div>';
          }
          $("#bill-"+mode).html(html);
          setBillRemoveAndChangeStatus();
        }
      });
    }
    
    $(document).ready(function(){
      ajaxLoadBillsByMode('all');  
        
      $('#tab-bill-all').bind('click', function(){
        ajaxLoadBillsByMode('all');
      });
      
      $('#tab-bill-open').bind('click', function(){
        ajaxLoadBillsByMode('open');
      });
      
      $('#tab-bill-close').bind('click', function(){
        ajaxLoadBillsByMode('close');
      });
    });
  </script>

</body>
</html>
