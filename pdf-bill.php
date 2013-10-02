<?php
error_reporting(E_ALL); 
include_once('utils/db.php');
include_once('libs/fpdf/fpdf.php');

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
                                     ca.name AS addressee_name, ca.cif AS addressee_cif, ca.mail AS addressee_mail, ca.phone AS addressee_phone, ca.fax AS addressee_fax,  ca.webpage AS addressee_webpage, ca.logo AS addressee_logo, ca.country AS addressee_country, ca.province AS addressee_province, ca.city AS addressee_city, ca.postal_code AS addressee_postal_code, ca.street AS addressee_street, ca.number AS addressee_number, ca.floor as addressee_floor, ca.door as addressee_door, v.percentage as percentage
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
if(isset($formErrorId)){
    $pdf = new FPDF('P','mm','A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0, 15, iconv('UTF-8', 'windows-1252', 'No se ha encontrado la factura indicada.'));
    $pdf->Ln(10);
}
else{
    $lang = $language->shortening;
    $translate = array(
        'es' => array('download_pdf' => 'Descargar PDF',
                      'enterprise_logo' => 'Logotipo de empresa',
                      'bill_number' => 'Factura número',
                      'now_date' => 'Fecha',
                      'expiration_date' => 'Fecha de vencimiento',
                      'reference' => 'Referencia',
                      'sender_data' => 'Datos de la empresa emisora de la factura',
                      'addressee_data' => 'Datos de la empresa receptora de la factura',
                      'name' => 'Nombre',
                      'nif' => 'N.I.F.',
                      'street' => 'Calle',
                      'number' => 'Número',
                      'floor' => 'Planta',
                      'door' => 'Puerta',
                      'postal_code' => 'Código postal',
                      'city' => 'Localidad',
                      'province' => 'Provincia',
                      'country' => 'País',
                      'phone' => 'Teléfono',
                      'fax' => 'Fax',
                      'mail' => 'Correo electrónico',
                      'webpage' => 'Página web',
                      'item_description' => 'Descripción del producto o servicio',
                      'unit_price' => 'Precio',
                      'amount' => 'Cantidad',
                      'discount' => 'Descuento',
                      'final_price' => 'Subtotal',
                      'final_price_vat' => 'Subtotal con I.V.A.',
                      'total' => 'Total',
                      'total_vat' => 'I.V.A.',
                      'total_with_vat' => 'Total con I.V.A.',
                      'bank_transfer' => 'Transferencia bancaria',
                      'cash_payment' => 'Pago al contado',
                      'check' => 'Cheque',
                      'pay_mode' => 'Método de pago',
                      'bank_account' => 'Número de cuenta',
                      'signature' => 'Firma del receptor'
            ),
        'ca' => array('download_pdf' => 'Descàrrega del PDF',
                      'enterprise_logo' => 'Logotip de l\'empresa',
                      'bill_number' => 'Número de factura',
                      'now_date' => 'Data',
                      'expiration_date' => 'Data de venciment',
                      'reference' => 'Referència',
                      'sender_data' => 'Dades de l\'empresa emisora de la factura',
                      'addressee_data' => 'Dades de l\'empresa receptora de la factura',
                      'name' => 'Nom',
                      'nif' => 'N.I.F.',
                      'street' => 'Carrer',
                      'number' => 'Número',
                      'floor' => 'Planta',
                      'door' => 'Porta',
                      'postal_code' => 'Codi postal',
                      'city' => 'Localitat',
                      'province' => 'Província',
                      'country' => 'País',
                      'phone' => 'Telèfon',
                      'fax' => 'Fax',
                      'mail' => 'Correu electrònic',
                      'webpage' => 'Pàgina web',
                      'item_description' => 'Descripció del producte o servei',
                      'unit_price' => 'Preu',
                      'amount' => 'Quantitat',
                      'discount' => 'Descompte',
                      'final_price' => 'Subtotal',
                      'final_price_vat' => 'Subtotal amb I.V.A.',
                      'total' => 'Total',
                      'total_vat' => 'I.V.A.',
                      'total_with_vat' => 'Total con I.V.A.',
                      'bank_transfer' => 'Transferència bancària',
                      'cash_payment' => 'Pagament al comptat',
                      'check' => 'Xec',
                      'pay_mode' => 'Mètode de pagament',
                      'bank_account' => 'Número de compte',
                      'signature' => 'Firma del receptor'
            )
    );
    
    $pdf = new FPDF('P','mm','A4');
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(true);
    $pdf->SetFont('Arial','B',14);
    $prevY = $pdf->GetY();
    $pdf->Cell(0, 15, iconv('UTF-8', 'windows-1252', $translate[$lang]['bill_number'].': '.$info->id));
    $pdf->Ln();
    $creationDate = explode('-', $info->creation_date);
    $creationDate = $creationDate[2].'-'.$creationDate[1].'-'.$creationDate[0];
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(80, 15, iconv('UTF-8', 'windows-1252', $translate[$lang]['now_date'].': '.$creationDate));
    $pdf->Ln(10);
    $expirationDate = explode('-', $info->expiration_date);
    $expirationDate = $expirationDate[2].'-'.$expirationDate[1].'-'.$expirationDate[0];
    $pdf->Cell(80, 15, iconv('UTF-8', 'windows-1252', $translate[$lang]['expiration_date'].': '.$expirationDate));
    if(strlen($info->reference)>0){
        $pdf->Cell(80, 15, iconv('UTF-8', 'windows-1252', $translate[$lang]['reference'].': '.$info->reference));
    }
    $pdf->Image($info->sender_logo, 100, $prevY+3, 80);
    $pdf->SetFont('Arial','B',8);
    $pdf->Ln(15);
    
    
    $sender_floor = $sender_door = $sender_fax = $sender_webpage = $addressee_floor = $addressee_door = $addressee_fax = $addressee_webpage = '';
    
    if(strlen($info->sender_floor)>0) $sender_floor = ', '.$translate[$lang]['floor'].': '.$info->sender_floor;
    if(strlen($info->sender_door)>0)  $sender_door = ', '.$translate[$lang]['door'].': '.$info->sender_door;
    if(strlen($info->sender_fax)>0)  $sender_fax = ', '.$translate[$lang]['fax'].': '.$info->sender_fax;
    if(strlen($info->sender_webpage)>0)  $sender_webpage = ', '.$translate[$lang]['webpage'].': '.$info->sender_webpage;
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['name'].': '.$info->sender_name.', '.$translate[$lang]['nif'].': '.$info->sender_cif));
    $pdf->Ln();
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['street'].': '.$info->sender_street.', '.$translate[$lang]['number'].': '.$info->sender_number.$sender_floor.$sender_door));
    $pdf->Ln();
    $pdf->Cell(0,7, iconv('UTF-8', 'windows-1252', $translate[$lang]['postal_code'].': '.$info->sender_postal_code.', '.$translate[$lang]['city'].': '.$info->sender_city.', '.$translate[$lang]['province'].': '.$info->sender_province.', '.$translate[$lang]['country'].': '.$info->sender_country));
    $pdf->Ln();
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['phone'].': '.$info->sender_phone.$sender_fax));
    $pdf->Ln();
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['mail'].': '.$info->sender_mail.$sender_webpage));
    $pdf->Ln(15);
    
    if(strlen($info->addressee_floor)>0) $addressee_floor = ', '.$translate[$lang]['floor'].': '.$info->addressee_floor;
    if(strlen($info->addressee_door)>0)  $addressee_door = ', '.$translate[$lang]['door'].': '.$info->addressee_door;
    if(strlen($info->addressee_fax)>0)  $addressee_fax = ', '.$translate[$lang]['fax'].': '.$info->addressee_fax;
    if(strlen($info->addressee_webpage)>0)  $addressee_webpage = ', '.$translate[$lang]['webpage'].': '.$info->addressee_webpage;
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['name'].': '.$info->addressee_name.', '.$translate[$lang]['nif'].': '.$info->addressee_cif));
    $pdf->Ln();
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['street'].': '.$info->addressee_street.', '.$translate[$lang]['number'].': '.$info->addressee_number.$addressee_floor.$addressee_door));
    $pdf->Ln();
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['postal_code'].': '.$info->addressee_postal_code.', '.$translate[$lang]['city'].': '.$info->addressee_city.', '.$translate[$lang]['province'].': '.$info->addressee_province.', '.$translate[$lang]['country'].': '.$info->addressee_country));
    $pdf->Ln();
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['phone'].': '.$info->addressee_phone.$addressee_fax));
    $pdf->Ln();
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['mail'].': '.$info->addressee_mail.$addressee_webpage));
    $pdf->Ln(15);
    
    $pageHeight = 250;
    $pdf->Cell(80, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['item_description']),1,0,'C');
    $pdf->Cell(29, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['unit_price']),1,0,'C');
    $pdf->Cell(29, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['amount']),1,0,'C');
    $pdf->Cell(27, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['discount']),1,0,'C');
    $pdf->Cell(32, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['final_price']),1,0,'C');
    $pdf->Ln();
    
    $total = 0;
    foreach($items as $i){
        $prevY = $pdf->GetY();
        $height = ceil($pdf->GetStringWidth(iconv('UTF-8', 'windows-1252', $i->description))/63); //per calcular l'alçada, fem una divisió entre la amplada total del text amb la font seleccionada, dividit per l'ample de la cel·la (80), que ens dona el nombre de línies, quan volguem ficar les altres celes, els haurem de ficar aquest nombre de línies, multiplicat per el tamany de línia (7)
        if($pageHeight< $height+$pdf->getY()){
          $pdf->AddPage();
          $pdf->Cell(80, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['item_description']),1,0,'C');
          $pdf->Cell(29, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['unit_price']),1,0,'C');
          $pdf->Cell(29, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['amount']),1,0,'C');
          $pdf->Cell(27, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['discount']),1,0,'C');
          $pdf->Cell(32, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['final_price']),1,0,'C');
          $pdf->Ln();
          $prevY = $pdf->GetY();
        }
        $pdf->MultiCell(80, 7, iconv('UTF-8', 'windows-1252', $i->description),1);
        $pdf->SetXY(80+10, $prevY);
        $pdf->Cell(29, $height*7, iconv('UTF-8', 'windows-1252', $i->price.' €'),1,0,'C');
        $pdf->Cell(29, $height*7, iconv('UTF-8', 'windows-1252', $i->amount),1,0,'C');
        $pdf->Cell(27, $height*7, iconv('UTF-8', 'windows-1252', $i->discount.' €'),1,0,'C');
        $pdf->Cell(32, $height*7, iconv('UTF-8', 'windows-1252', ($i->price*$i->amount-$i->discount).' €'),1,0,'C');
        $pdf->Ln();  
        $total = $total + ($i->price*$i->amount-$i->discount);
    }
    
    $pdf->Cell(80+29+29+27, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['total']),1,0,'L');
    $pdf->Cell(32, 7, iconv('UTF-8', 'windows-1252', $total.' €'),1,0,'C');
    $pdf->Ln();

    $pdf->Cell(80+29+29+27, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['total_vat']),1,0,'L');
    $pdf->Cell(32, 7, iconv('UTF-8', 'windows-1252', ($total*($info->percentage/100)).' €'),1,0,'C');
    $pdf->Ln();

    $pdf->Cell(80+29+29+27, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['total_with_vat']),1,0,'L');
    $pdf->Cell(32, 7, iconv('UTF-8', 'windows-1252', ($total*(1+$info->percentage/100)).' €'),1,0,'C');
    $pdf->Ln(10);
    
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['pay_mode'].': '.'Transferencia bancaria'));
    $pdf->Ln(10);
    
    if($info->pay_mode == 0 && strlen($info->bank_account)>0){
        $nc = strrev(substr(base64_decode($info->bank_account), 32, 20));
        $ncFirst = substr($nc, 0, 4);
        $ncSecond = substr($nc, 4, 4);
        $ncThird = substr($nc, 8, 2);
        $ncFourth = substr($nc, 10, 10);
        
        
        $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['bank_account'].': '.$ncFirst.' '.$ncSecond.' '.$ncThird.' '.$ncFourth));
        $pdf->Ln(10);
    }
    
    $pdf->Cell(0, 7, 'Firma del receptor:');
    $pdf->Ln();
    
    $pdf->Output('Factura_'.$info->id.'.pdf', 'D');
}
?>