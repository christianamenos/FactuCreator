<?php
include_once('utils/db.php');

$dbh = new DB();
$b = $dbh->beginTransaction();
$ret = array();
if($b){
  $pdoStmt = $dbh->prepare('SELECT b.id, b.creation_date, b.payed, c.name AS sender_name, d.name AS addressee_name
                            FROM bill AS b
                            LEFT JOIN company AS c ON b.sender_id = c.id
                            LEFT JOIN company AS d ON b.addressee_id = d.id
                            WHERE payed = FALSE
                            ORDER BY b.creation_date DESC, b.id DESC');
  $res = $pdoStmt->execute();
  if($res){
    while($row = $pdoStmt->fetchAll(PDO::FETCH_OBJ)){
      $ret = $row;
    }
  }
}
echo json_encode($ret);
?>