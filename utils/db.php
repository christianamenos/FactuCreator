<?php
class DB extends PDO
{
  
  protected $host = 'YOUR_HOST_SERVER';
  protected $user = 'YOUR_DATABASE_USER';
  protected $password = 'YOUR_DATABASE_PASSWORD';
  protected $database = 'factucreator';
  protected $db_type = 'mysql';
  
  public function __construct($host = 'YOUR_HOST_SERVER',
                              $user = 'YOUR_DATABASE_USER',
                              $password = 'YOUR_DATABASE_PASSWORD',
                              $database = 'factucreator',
                              $db_type = 'mysql')
  {
    $this->host = $host;
    $this->user = $user;
    $this->password = $password;
    $this->database = $database;
    $this->db_type = $db_type;
    $dns = $this->db_type.':dbname='.$this->database.';host='.$this->host;
    try {
        parent::__construct($dns, $this->user, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
  }
  
}
?>