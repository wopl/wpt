<?php
// **********************************************************************************
// **                                                                              **
// ** Database.php                                  (c) Wolfram Plettscher 03/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Entity;

use \PDO;
use \Symfony\Component\HttpFoundation\Session\Session;

// **********************************************************************************
// **                                                                              **
// ** class: Database                                                              **
// **                                                                              **
// **********************************************************************************
class Database extends PDO
{
  private $host;
  private $port;
  private $dbname;
  private $user;
  private $pass;

  private $dbh;
  private $error;
  private $stmt;
  private $session;

  // ********************************************************************************
  // **                                                                            **
  // ** function: __construct                                                      **
  // **                                                                            **
  // ********************************************************************************
  public function __construct ()
  {
    $session = new Session();

    // Database parameters are read from session
    // have been filled by SecurityController
    $host = $session->get ('database_host');
    $port = $session->get ('database_port');
    $dbname = $session->get ('database_name');
    $user = $session->get ('database_user');
    $pass = $session->get ('database_password');

    $dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname;

//	die ("$dsn ... $user ... $pass");
	
    $option = array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    try {
      $this->dbh = new PDO ($dsn, $user, $pass, $option);
    }

    catch (PDOException $e){
      $error = $e->getMessage();
      die ("could not make new PDO " . $e->getMessage());
    }
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: query                                                            **
  // **                                                                            **
  // ********************************************************************************
  public function query ($query)
  {
    $this->stmt = $this->dbh->prepare ($query);
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: bind                                                             **
  // **                                                                            **
  // ********************************************************************************
  public function bind ($param, $value, $type = null)
  {
    if (is_null ($type)) {
      switch (true) {
        case is_int ($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool ($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null ($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
      }
    }
    $this->stmt->bindvalue ($param, $value, $type);
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: execute                                                          **
  // **                                                                            **
  // ********************************************************************************
  public function execute ()
  {
    return $this->stmt->execute();
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: resultset                                                        **
  // **                                                                            **
  // ********************************************************************************
  public function resultset ()
  {
    $this->execute();
    return $this->stmt->fetchAll (PDO::FETCH_ASSOC);
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: single                                                           **
  // **                                                                            **
  // ********************************************************************************
  public function single ()
  {
    $this->execute();
    return $this->stmt->fetch (PDO::FETCH_ASSOC);
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: rowCount                                                         **
  // **                                                                            **
  // ********************************************************************************
  public function rowCount ()
  {
    return $this->stmt->rowCount();
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: lastInsertID                                                     **
  // **                                                                            **
  // ********************************************************************************
//  public function lastInsertID ()
//  {
//    return $this->dbh->lastInsertID();
//  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: beginTransaction                                                 **
  // **                                                                            **
  // ********************************************************************************
  public function beginTransaction ()
  {
    return $this->dbh->beginTransaction();
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: endTransaction                                                   **
  // **                                                                            **
  // ********************************************************************************
  public function endTransaction ()
  {
    return $this->dbh->commit();
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: cancelTransaction                                                **
  // **                                                                            **
  // ********************************************************************************
  public function cancelTransaction ()
  {
    return $this->dbh->rollBack();
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: debugDumpParams                                                  **
  // **                                                                            **
  // ********************************************************************************
  public function debugDumpParams ()
  {
    return $this->dbh->debugDumpParams();
  }
}
