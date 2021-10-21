<?php
namespace App\models\dao\exceptions;
use FFI\Exception;

abstract class DatabaseException extends Exception
{
  protected $query;

  public function __construct($query='',$message='',$code=0,$previous=NULL)
  {
    $this->query=$query;
    parent::__construct($message,$code,$previous);
  }

  final public function getHtmlMessage($class="alert alert-danger")
  {
    return "<div class=\"{$class}\">CODE = {$this->getCode()}<br>MESSAGE = {$this->getMessage()}<br>QUERY = {$this->query}</div>";
  }
  
  final public function getQuery()
  {
    return $this->query;
  }
}