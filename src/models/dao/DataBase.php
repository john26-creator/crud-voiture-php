<?php
namespace App\models\dao;

use PDO;
use App\models\dao\exception\DatabaseException;
use App\models\dao\Properties;


class DataBase
{
  /*
   * Database variable
   * @var mysqli
   */
  protected static $db;

  /**
   * Constructor
   */
  final protected function __construct()
  {
    //no public constructor for singleton class
  }

  /**
   * Instantiator static method
   * As of PHP 5.3.0, PHP implements a feature called late static bindings which
   * can be used to reference the called class in a context of static inheritance.
   * @return PDO
   */
  public static function getInstance()
  {
    if(!is_object(self::$db))
    {
      
      self::$db = new PDO('mysql:host='. Properties::DB_HOST.';dbname='. Properties::DB_NAME, Properties::DB_USER, Properties::DB_PASS);
    }
    return self::$db;
  }

  protected function __destruct()
  {
    if (self::$db) self::$db->close();
  }


  protected function __clone()
  {
    
  }
}


