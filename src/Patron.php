<?php

Class Patron

{
    private $name;
    private $id;

    function __construct($name='', $id=null)
    {
        $this->setName($name);
        $this->setId($id);
    }

      function getName(){
      return $this->name;
    }

     function setName($name){
      $this->name = (string)$name;
    }

     function getId(){
      return $this->id;
    }

     function setId($id){
      $this->id = (int)$id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO patrons (name) VALUES ('{$this->getName()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
        $all_patrons = array();
        foreach($returned_patrons as $returned_patron)
        {
            $name = $returned_patron['name'];
            $id = $returned_patron['id'];
            $new_patron = new Patron($name, $id);
            array_push($all_patrons, $new_patron);
        }
        return $all_patrons;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM patrons;");
    }

    static function find($input_id)
    {
        $returned_patrons = Patron::getAll();
        foreach($returned_patrons as $returned_patron)
        {
            $returned_id = $returned_patron->getId();
            if($returned_id == $input_id)
            {
                return $returned_patron;
            }
        }
    }

    function update($new_name)
    {
        $GLOBALS ['DB']->exec("UPDATE patrons SET name = '{$new_name}' WHERE id = {$this->getId()};");
        $this->setName($new_name);
    }

    function delete()
    {
        $GLOBALS['DB'] -> exec("DELETE FROM patrons WHERE id = {$this->getId()};");

    }

    function checkoutBook($copy_id, $due_date, $return_date)
    {
        $GLOBALS['DB']->exec("INSERT INTO checkouts (copy_id, due_date, return_date) where patron_id = {$this->getId()}");
    }

    function getAllCheckouts()
    {
        $returned_checkouts = $GLOBALS['DB']->query("SELECT books.* copies.* checkouts.* FROM patrons JOIN checkouts ON (patrons.id = checkouts.patron_id) JOIN copies ON (checkouts.copy_id = copies.id) JOIN books ON (copies.book_id = books.id) WHERE patrons.id = {$this->getId()};");

    }


}

 ?>
