<?php
include_once('utils/db.php');

$dbh = new DB();
$b = $dbh->beginTransaction();
$ret = array();
if($b){
  $pdoStmt = $dbh->prepare('SELECT * 
                            FROM company
                            WHERE own = FALSE');
  $res = $pdoStmt->execute();
  if($res){
    while($row = $pdoStmt->fetchAll(PDO::FETCH_OBJ)){
      $ret = $row;
    }
  }
}
echo json_encode($ret);
?>