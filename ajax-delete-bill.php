<?php
include_once('utils/db.php');
$dbh = new DB();
$ret = false;
if(filter_has_var(INPUT_GET, 'id') && is_numeric(trim($_GET['id']))){
  $b = $dbh->beginTransaction();
  if($b){
    $pdoStmt = $dbh->prepare('DELETE FROM bill
                              WHERE id = :id');
    $ret = $pdoStmt->execute(array(':id' => trim($_GET['id'])));
    if($ret){
      $dbh->commit();
    }
    else{
      $dbh->rollback();
    }
  }
}
echo json_encode($ret);
?>