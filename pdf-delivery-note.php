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
if(isset($formErrorId)){
    $pdf = new FPDF('P','mm','A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0, 15, iconv('UTF-8', 'windows-1252', 'No se ha encontrado el albarán indicado.'));
    $pdf->Ln(10);
}
else{
    $lang = $language->shortening;
    $translate = array(
        'es' => array('download_pdf' => 'Descargar PDF',
                      'enterprise_logo' => 'Logotipo de empresa',
                      'bill_number' => 'Albarán número',
                      'now_date' => 'Fecha',
                      'expiration_date' => 'Fecha de vencimiento',
                      'reference' => 'Referencia',
                      'sender_data' => 'Datos de la empresa emisora del albarán',
                      'addressee_data' => 'Datos de la empresa receptora del albarán',
                      'name' => 'Nombre',
                      'nameFrom' => 'Nombre emisor',
                      'nameTo' => 'Nombre receptor',
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
                      'bill_number' => 'Número d\'albarà',
                      'now_date' => 'Data',
                      'expiration_date' => 'Data de venciment',
                      'reference' => 'Referència',
                      'sender_data' => 'Dades de l\'empresa emisora de l\'albarà',
                      'addressee_data' => 'Dades de l\'empresa receptora de l\'albarà',
                      'name' => 'Nom',
                      'nameFrom' => 'Nom emisor',
                      'nameTo' => 'Nom receptor',
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
    if(strlen($info->reference)>0){
        $pdf->Cell(80, 15, iconv('UTF-8', 'windows-1252', $translate[$lang]['reference'].': '.$info->reference));
    }
    $pdf->Image($info->sender_logo, 100, $prevY+3, 80);
    $pdf->SetFont('Arial','B',8);
    $pdf->Ln();
    
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['nameFrom'].': '.$info->sender_name));
    $pdf->Ln();
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['nameTo'].': '.$info->addressee_name));
    $pdf->Ln(15);
    
    $pageHeight = 250;
    $pdf->Cell(197, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['item_description']),1,0,'C');
    $pdf->Ln();
    
    foreach($items as $i){
        $prevY = $pdf->GetY();
        $height = ceil($pdf->GetStringWidth(iconv('UTF-8', 'windows-1252', $i->description))/65); //per calcular l'alçada, fem una divisió entre la amplada total del text amb la font seleccionada, dividit per l'ample de la cel·la (80), que ens dona el nombre de línies, quan volguem ficar les altres celes, els haurem de ficar aquest nombre de línies, multiplicat per el tamany de línia (7)
        if($pageHeight< $height+$pdf->getY()){
          $pdf->AddPage();
          $pdf->Cell(197, 7, iconv('UTF-8', 'windows-1252', $translate[$lang]['item_description']),1,0,'C');
          $pdf->Ln();
          $prevY = $pdf->GetY();
        }
        $pdf->MultiCell(197, 7, iconv('UTF-8', 'windows-1252', $i->description),1);
    }
    
    $pdf->Cell(0, 7, 'Firma del receptor:');
    $pdf->Ln();
    
    $pdf->Output('Albaran_'.$info->id.'.pdf', 'D');
}
?>