<?php
namespace App\models\dao;
use App\models\dao\exceptions\NotFoundException;
use App\models\dao\exceptions\ReadException;
use FFI\Exception;
use PDO;

/**
 * Active Record Pattern paradigm
 * Database Entity Wrapper
 * @ignore
 */
abstract class EntityBase
{
  /**
   * Database shared connector
   * @var PDO connector
   */
  protected $db;

  //----------------------------------------------------------------------------

  /**
   * Database table name
   * @var string
   */
  protected $table;

  /**
   * Main (table) SQL primary key (field) names
   * @var array
   */
  protected $primkeys;

  /**
   * Allowed CRUD fields (table columns)
   * @var array
   */
  protected $fields;

  /**
   * Main (table) SQL query
   * @var string
   */
  protected $sql;

  //////////////////////////////////////////////////////////////////////////////

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->db=DataBase::getInstance();
  }

  //////////////////////////////////////////////////////////////////////////////

  /**
   * Get single primary key value
   * @return mixed
   */
  public function getId()
  {
    $pkfield=$this->primkeys[0];
    return $this->$pkfield;
  }

  /**
   * Returns primary keys in associative array
   * @return array
   */
  public function getIds()
  {
    $ids=array();
    foreach($this->primkeys as $pk)
    {
      $ids[$pk]=$this->$pk;
    }
    return $ids;
  }

  /**
   * Fetches row from database and populates variables
   * @param string $sql SQL statement
   * @throws NotFoundException
   * @throws ReadException
   */
  public function fetch($sql)
  {
    if($rst=$this->db->query($sql))
    {
        
      if($rst)
      {
        $row=$rst->fetch();
        $this->populate($row);
        $rst->closeCursor ();
      }
      else
      {
        throw new NotFoundException($sql,$this->db->error,$this->db->errno);
      }
    }
    else
    {
      throw new ReadException($sql,$this->db->error,$this->db->errno);
    }
  }

  //----------------------------------------------------------------------------

  /**
   * Fills class variables of child object with array elements
   * @param array $array array of column=>value elements
   */
  public function populate(array $array)
  {
    foreach($array as $key=>$val)
    {
      if(in_array($key,array_merge($this->primkeys,$this->fields))) //include only allowed fields
      {
        $this->$key=$val;
      }
    }
  }

   //----------------------------------------------------------------------------

  /**
   * Returns the object of type given by ClassType variable and dynamically
   * fills only properties that correspond to columns in given SQL query
   * @param string $sql SQL query
   * @param string $Class classname
   * @return Object
   * @return NotFoundException
   * @throws ReadException
   * @throws Exception
   */
  protected function getObject($sql,$Class)
  {
    if(class_exists($Class))
    {
      if($rst=$this->db->query($sql))
      {
          $rst->setFetchMode(PDO::FETCH_CLASS, $Class);
        if($Object=$rst->fetch())
        {
            $rst->closeCursor();
        }
        else
        {
          throw new NotFoundException($sql,$this->db->error,$this->db->errno);
        }
      }
      else
      {
        throw new ReadException($sql,$this->db->error,$this->db->errno);
      }
    }
    else
    {
      throw new Exception("Non-existent class: $Class");
    }
    return $Object;
  }
  
  protected function getObjectPS($ps,$Class)
  {
      $Object = NULL;
      if(class_exists($Class))
      {
          if($ps->execute())
          {
              $Object=$ps->fetchObject ($Class);
              $ps->closeCursor ();
          }
          else
          {
              throw new ReadException("sql",$this->db->error,$this->db->errno);
          }
      }
      else
      {
          throw new Exception("Non-existent class: {$Class}!");
      }
        return $Object;
      }

  /**
   * Returns the object of self type
   * @param $sql SQL query
   * @return self
   */
  protected function getSelfObject($sql)
  {
    return $this->getObject($sql,get_class($this));
  }
  
  protected function getSelfObjectPS($ps)
  {
      return $this->getObjectPS($ps,get_class($this));
  }
  

  protected function getObjectsPS($ps,$Class)
  {
      $Objects=array();
      
      if(class_exists($Class))
      {
          if($ps->execute())
          {
              while($Object=$ps->fetchObject ($Class)){
                      $Objects[]=$Object;
                  }
              $ps->closeCursor ();
          }
          else
          {
              throw new ReadException("sql",$this->db->error,$this->db->errno);
          }
      }
      else
      {
          throw new Exception("Non-existent class: {$Class}!");
      }
      return $Objects;
  }
  
  protected function getSelfObjectsPreparedStatement($ps){
      return $this->getObjectsPS($ps,get_class($this));
  }

  /**
   * Returns the array of objects of type given by ClassType variable and
   * dynamically fills only properties that correspond to columns in given SQL query
   * @param string $sql SQL query
   * @param string $Class class (name)
   * @return Object[]
   * @throws ReadException
   * @throws Exception
   */
  protected function getObjects($sql,$Class)
  {
      $Objects=array();
      
      if(class_exists($Class))
      {
          if($rst=$this->db->query($sql))
          {
              while($Object=$rst->fetchObject ($Class))
              {
                  $Objects[]=$Object;
              }
              $rst->closeCursor ();
          }
          else
          {
              throw new ReadException($sql,$this->db->error,$this->db->errno);
          }
      }
      else
      {
          throw new Exception("Non-existent class: {$Class}!");
      }
      return $Objects;
  }
  
  
  /**
   * Returns the array of self type objects
   * @param string $sql SQL query
   * @return self[]
   */
  protected function getSelfObjects($sql)
  {
    return $this->getObjects($sql,get_class($this));
  }

  /**
   * Returns the list of all objects
   * @param INTEGER $limit
   * @param INTEGER $offset
   * @return Object[]
   */
  public function getList($limit=100,$offset=0)
  {
    $sql="{$this->sql} LIMIT {$limit} OFFSET {$offset}";
    return $this->getSelfObjects($sql);
  }

  // ID FUNCTIONS //////////////////////////////////////////////////////////////

  /**
   * Returns the list of IDs and there are three possibilities:
   * @param string $glue (can be comma or AND operator)
   * @param mixed $ids single PK value or array of PKs with values
   * @return string
   */
  private function getImplodedIds($glue,$ids)
  {
    $wheres=[];
    if($ids)
    {
      if(is_array($ids))
      {
        foreach($ids as $id=>$val)
        {
          $wheres[]="{$id}='{$val}'";
        }
        $where=implode($glue,$wheres);
      }
      else
      {
        $where=" {$this->primkeys[0]}='{$ids}'";
      }
    }
    else //if $ids was not given, use object's primary key(s)
    {
      if($this->primkeys)
      {
        foreach($this->primkeys as $pk)
        {
          if($this->$pk)
          {
            $wheres[]="{$pk}='{$this->$pk}'";
          }
          else
          {
            $wheres[]="{$pk}=NULL";
          }
        }
        $where=implode($glue,$wheres);
      }
      else
      {
        $where='1=1'; // no PK
      }
    }
    return $where;
  }

  // CRUDS FUNCTIONS ///////////////////////////////////////////////////////////

  /**
   * Reads database row from table and dynamically fills object's variables from row's fields
   * @param mixed $ids single PK value or array of PKs with values
   * @throws ReadException
   */
  public function read($ids=0)
  {
    $sql="{$this->sql} WHERE {$this->getImplodedIds(' AND ',$ids)} LIMIT 1";
    $this->fetch($sql);
  }

  /**
   * Reads given columns only from table and dynamically fills object's variables
   * @param array $columns Array of table columns
   * @param mixed $ids single PK value or array of PKs with values
   * @throws ReadException
   */
  public function readColumns(array $columns,$ids=0)
  {
    if(is_array($columns))
    {
      $sql='SELECT '.implode(',',$columns)." FROM {$this->table} WHERE {$this->getImplodedIds(' AND ',$ids)} LIMIT 1";
      $this->fetch($sql);
    }
    else
    {
      throw new Exception('No columns defined!');
    }
  }

  /**
   * Finds rows from table according to given list of columns/values
   * @param array $conditions Array of table columns=>'vaule' pairs (conditions)
   * @throws Exception
   */
  public function find($conditions=0)
  {
    if(is_array($conditions))
    {
      $sql="SELECT * FROM {$this->table} WHERE {$this->getImplodedIds(' AND ',$conditions)} LIMIT 1";
      $this->fetch($sql);
    }
    else
    {
      throw new Exception('No columns defined!');
    }
  }

  //////////////////////////////////////////////////////////////////////////////

  public function beginTransaction()
  {
    $this->db->beginTransaction();
  }

  public function commitTransaction()
  {
    $this->db->commit();
  }

  public function rollbackTransaction()
  {
    $this->db->rollback();
  }

}