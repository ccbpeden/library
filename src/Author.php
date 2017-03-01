<?php

Class Author

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
        $GLOBALS['DB']->exec("INSERT INTO authors (name) VALUES ('{$this->getName()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors;");
        $all_authors = array();
        foreach($returned_authors as $returned_author)
        {
            $name = $returned_author['name'];
            $id = $returned_author['id'];
            $new_author = new author($name, $id);
            array_push($all_authors, $new_author);
        }
        return $all_authors;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM authors;");
    }
    static function deletefromJoinTable()
    {
        $GLOBALS['DB']->exec("DELETE FROM authors_books");
    }

    static function find($input_id)
    {
        $returned_authors = Author::getAll();
        foreach($returned_authors as $returned_author)
        {
            $returned_id = $returned_author->getId();
            if($returned_id == $input_id)
            {
                return $returned_author;
            }
        }
    }

    function update($new_name)
    {
        $GLOBALS ['DB']->exec("UPDATE authors SET name = '{$new_name}' WHERE id = {$this->getId()};");
        $this->setName($new_name);
    }

    function delete()
    {
        $GLOBALS['DB'] -> exec("DELETE FROM authors WHERE id = {$this->getId()};");
        $GLOBALS['DB']-> exec("DELETE FROM authors_books WHERE author_id= {$this->getId()};");
    }
    function addBook($new_book_id)
    {
        $GLOBALS['DB']->exec("INSERT INTO authors_books(book_id,author_id) VALUES({$new_book_id}, {$this->getId()});");
    }
    function getBooks()
    {
        $returned_books= $GLOBALS['DB']->query("SELECT books.* FROM authors JOIN authors_books ON (authors_books.author_id= authors.id) JOIN books ON (authors_books.book_id= books.id)  WHERE authors.id= {$this->getId()};");
        $output_books= array();
        foreach ($returned_books as $book) {
            $title= $book['title'];
            $id= $book['id'];
            $new_book= new Book($title, $id);
            array_push($output_books, $new_book);
        }
        return $output_books;
    }
}

 ?>
