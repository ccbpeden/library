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

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO books (title) VALUES ('{$this->getTitle()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
        $all_books = array();
        foreach($returned_books as $returned_book)
        {
            $title = $returned_book['title'];
            $id = $returned_book['id'];
            $new_book = new book($title, $id);
            array_push($all_books, $new_book);
        }
        return $all_books;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM books;");
    }

    static function find($input_id)
    {
        $returned_books = Book::getAll();
        foreach($returned_books as $returned_book)
        {
            $returned_id = $returned_book->getId();
            if($returned_id == $input_id)
            {
                return $returned_book;
            }
        }
    }

    function update($new_title)
    {
        $GLOBALS ['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getId()};");
        $this->setTitle($new_title);
    }

    function delete()
    {
        $GLOBALS['DB'] -> exec("DELETE FROM books WHERE id = {$this->getId()};");
        $GLOBALS['DB']-> exec("DELETE FROM authors_books WHERE book_id= {$this->getId()};");
    }

    function addAuthor($new_author_id)
    {
        $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) VALUES({$new_author_id}, {$this->getId()});");
    }
    function getAuthors()
    {
        $returned_authors= $GLOBALS['DB']->query("SELECT authors.* FROM books JOIN authors_books ON (authors_books.book_id= books.id) JOIN authors ON(authors_books.author_id= authors.id) WHERE books.id= {$this->getId()}; ");
        $output_authors= array();
        foreach ($returned_authors as $author) {
            $name = $author['name'];
            $id = $author['id'];
            $new_author = new Author($name, $id);
            array_push($output_authors, $new_author);
        }
        return $output_authors;
    }
    function getCopies()
    {
        $returned_copies = $GlOBALS['DB']->query("SELECT copies.* FROM books JOIN copies on(copies.book_id= book.id) WHERE book.id= {$this->getId()};");
        $output_copies= array();
        foreach ($returned_copies as $copy) {
            $status = $copy['status'];
            $book_id= $copy['book_id'];
            $id= $copy['id'];
            $new_copy= new Copy($book_id, $status, $id);
            array_push($output_copies, $new_copy);
        }
        return $output_copies;
    }

    static function findbyName($book_title)
    {
        $all_books = Book::getAll();
        foreach ($all_books as $book)
        {
            $returned_book_title = $book->getTitle();
            if($returned_book_title == $book_title)
            {
                return $book;
            }
        }
    }

    static function newBook($book_name, $author_name)
    {
        $authorexists = Author::findbyName($author_name);
        $bookexists = Book::findbyName($book_name);
        if($authorexists)
        {
            if($bookexists)
            {
                $new_copy = new Copy($bookexists->getId(), $status = 1);
                $new_copy->save();
                return 0;
            } else {
                $new_book = new Book($book_name);
                $new_book->save();
                $new_book->addAuthor($authorexists->getId());
                $new_copy = new Copy($new_book->getId(), $status = 1);
                $new_copy->save();
                return 1;
            }
        } else {
            $new_author = new Author($author_name);
            $new_author->save();
            $new_book = new Book($book_name);
            $new_book->save();
            $new_book->addAuthor($new_author->getId());
            $new_copy = new Copy($new_book->getId(), $status = 1);
            $new_copy->save();
            return 2;
        }
    }
}
 ?>
