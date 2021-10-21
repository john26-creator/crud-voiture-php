<?php
namespace App\models\dao;

use App\models\dao\EntityBase;

abstract class Voiture extends EntityBase
{
    
   /**
     * Protected variable
     * (PK)->Primary key
     * @var int $id
     */
   protected $id;
    
   public function getId() {return $this->id;}
   public function setId($id) {$this->id=$id;}

  /**
   * Protected variable
   * @var varchar $immat
   */
  protected $immat;

  public function getImmat() {return $this->immat;}
  public function setImmat($immat) {$this->immat=$immat;}

  /**
   * Protected variable
   * @var varchar $couleur
   */
  protected $couleur;

  public function getCouleur() {return $this->couleur;}
  public function setCouleur($couleur) {$this->couleur=$couleur;}
  
  
  protected $marque;
  
  public function getMarque() {return $this->marque;}
  public function setMarque($marque) {$this->marque=$marque;}
  
  protected $modele;
  
  public function getModele() {return $this->modele;}
  public function setmodele($modele) {$this->modele=$modele;}
  
  /**
   * Constructor
   * @var mixed $id
   */
  public function __construct($id=0)
  {
    parent::__construct();
    $this->table='voiture';
    $this->primkeys=['id'];
    $this->fields=['immat','couleur','marque','modele'];
    $this->sql="SELECT * FROM {$this->table}";
    if($id) $this->read($id);
  }

}

