<?php

Class Book

  {
    private $title;
    private $id;

    function __construct($title='', $id=null)
    {
        $this->setTitle($title);
        $this->setId($id);
    }

      function getTitle(){
      return $this->title;
    }

     function setTitle($title){
      $this->title = (string)$title;
    }

     function getId(){
      return $this->id;
    }

     function setId($id){
      $this->id = (int)$id;
    }
  }

 ?>
