<?php
  class Person {
    private $name;
    private $gender;
    private $id;

    function __construct($name, $gender, $id=null) {
      $this->name = $name;
      $this->gender = $gender;
      $this->id = $id;
    }

    function getId () {
      return $this->id;
    }
    function setName ($new_name){
      $this->name = (string) $new_name;
    }
    function getName () {
      return $this->name;
    }
    function setGender($new_gender){
      $this->gender = (string) $new_gender;
    }
    function getGender(){
      return $this->gender;
    }

    static function getAll(){
      $returned = $GLOBALS['db']->query("SELECT * FROM tests;");
      $results = $returned->fetchAll(PDO::FETCH_OBJ);
      return $results;
    }

    static function find($id){
      $find = null;
      $executed = $GLOBALS['db']->prepare("SELECT * FROM tests WHERE id = :id");
      $executed->bindParam(':id', $id, PDO::PARAM_STR);
      $executed->execute();
      $result = $executed->fetch(PDO::FETCH_ASSOC);
      if($result['id'] == $id){
        $find = new Person($result['name'], $result['gender'], $result['id']);
      }
      return $find;
    }

    function update($new_name, $new_gender){
      $executed = $GLOBALS['db']->exec("UPDATE tests SET name = '{$new_name}', gender = '{$new_gender}' WHERE id = {$this->getId()};");
      if($executed){
        $this->setName($new_name);
        $this->setGender($new_gender);
      }else{
        return false;
      }
    }

    function delete(){
      $executed = $GLOBALS['db']->exec("DELETE FROM tests WHERE id = {$this->getId()};");
      if(!$executed){
        return false;
      } else {
        return true;
      }
    }
  }
?>
