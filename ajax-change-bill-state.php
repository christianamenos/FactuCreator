<?php
include_once('utils/db.php');


if(filter_has_var(INPUT_GET, 'id')
    && ctype_digit($_GET['id'])
    && filter_has_var(INPUT_GET, 'to')
    && ctype_digit($_GET['to'])
    && $_GET['to']>=0
    && $_GET['to']<=1){
    $billId = $_GET['id'];
    $toState = $_GET['to'];
}
$dbh = new DB();
$b = $dbh->beginTransaction();
$ret = array();
if($b){
  $pdoStmt = $dbh->prepare('UPDATE bill
                            SET payed=:payed
                            WHERE id=:id');
  $ret = $pdoStmt->execute(array(':payed' => $toState, ':id' => $billId));
  $dbh->commit();
}
echo json_encode($ret);
?>