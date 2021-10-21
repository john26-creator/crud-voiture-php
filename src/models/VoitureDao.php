<?php
namespace App\models;

use App\models\dao\Voiture;
/**
 * Class Voiture
 */
class VoitureDao extends Voiture
{

  public function findAll () {
    $sql="SELECT * FROM voiture";
    return $this->getSelfObjects($sql);
  }
  
  public function findById($id) {
      $request="SELECT * FROM voiture WHERE id= :id";
      $sth = $this->db->prepare($request);
      $sth->bindParam(':id',$id);
      return $this->getSelfObjectPS($sth);
  }
  
  public function updateVoiture ($voiture) {
      $request = "UPDATE voiture SET couleur = :couleur, immat = :immat, marque= :marque, modele= :modele WHERE id = :id;";
      $sth = $this->db->prepare($request);
      $id = $voiture->getId();
      $c = $voiture->getCouleur();
      $ma = $voiture->getMarque();
      $mo = $voiture->getModele();
      $i = $voiture->getImmat();
      $sth->bindParam(':id',$id);
      $sth->bindParam(':couleur', $c);
      $sth->bindParam(':marque', $ma);
      $sth->bindParam(':modele', $mo);
      $sth->bindParam(':immat', $i);
      
      return $sth->execute();
  }
  
 public function deleteVoiture ($id) {
     $request = "DELETE FROM voiture WHERE id= :id";
     $sth = $this->db->prepare($request);
     $sth->bindParam(':id',$id);
     return $sth->execute();
 }

 public function insertVoiture ($immat, $couleur, $marque, $modele) {
  $request = "INSERT INTO voiture (`immat`, `couleur`, `marque`, `modele`) VALUES (:immat, :couleur, :marque, :modele);";
  $sth = $this->db->prepare($request);
  $sth->bindParam(':immat',$immat);
  $sth->bindParam(':couleur',$couleur);
  $sth->bindParam(':marque',$marque);
  $sth->bindParam(':modele',$modele);
  $sth->execute();
  
  return $this->db->lastInsertId();
}

public function insertVoiture2 ($id, $immat, $couleur, $marque, $modele) {
  $request = "INSERT INTO voiture (`id`,`immat`, `couleur`, `marque`, `modele`) VALUES (:id, :immat, :couleur, :marque, :modele);";
  $sth = $this->db->prepare($request);
  $sth->bindParam(':id', $id);
  $sth->bindParam(':immat',$immat);
  $sth->bindParam(':couleur',$couleur);
  $sth->bindParam(':marque',$marque);
  $sth->bindParam(':modele',$modele);
  $sth->execute();
  
  return $this->db->lastInsertId();
}
  
}


