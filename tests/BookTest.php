<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Book.php";
    require_once "src/Author.php";
    $server = 'mysql:host=localhost:8889;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
          Book::deleteAll();
          Author::deleteAll();
          Author::deletefromJoinTable();
        }
        function test_Book_setters_getters_constructor()
        {
            // Act
            $title = "Pilgrim's Progress";
            $id = 1;
            $book = new Book($title, $id);
            $actual_result = array($book->getTitle(), $book->getId());
            // Assert
                $this->assertEquals([$title, $id], $actual_result);
        }
        function test_Book_save_getAll_deleteAll()
        {
            // Arrange
            $book1 = new Book('John Smith');
            $book2 = new Book('Tom Smith');
            $book3 = new Book('Jane Smith');
            // Act
            $book1->save();
            Book::deleteAll();
            $book2->save();
            $book3->save();
            $all_books = Book::getAll();
            // Assert
            $this->assertEquals([$book2, $book3], $all_books);
        }
        function test_Book_update()
        {
            // Arrange
            $book1 = new Book('Tom Smith');
            $book1->save();
            // Act
            $book1->update('Tom Smythe');
            $all_books = Book::getAll();
            // Assert
            $this->assertEquals(
                'Tom Smythe',
                $all_books[0]->getTitle()
            );
        }
        function test_Book_find()
        {
            // Arrange
            $book1 = new Book('John Smith');
            $book2 = new Book('Tom Smith');
            $book1->save();
            $book2->save();
            // Act
            $result = Book::find($book2->getId());
            // Assert
            $this->assertEquals($book2, $result);
        }
        function testDeleteBook()
        {
            //Arrange
            $book1 = new Book('John Smith');
            $book2 = new Book('Tom Smith');
            $book3 = new Book('Jane Smith');
            $book1->save();
            $book2->save();
            $book3->save();
            // Act
            $book2->delete();
            //Assert
            $this->assertEquals([$book1, $book3], Book::getAll());
        }
        function test_AddAuthor_GetAuthors()
        {
            $book = new Book('Harry Potter');
            $book->save();
            $author = new Author('J.K Rowling');
            $author->save();
            $book2 = new Book ('Lord of the Rings');
            $book2->save();
            $author2= new Author('Tolkien');
            $author2->save();

            $book->addAuthor($author->getId());
            $book2->addAuthor($author2->getId());

            $result= $book->getAuthors();

            $this->assertEquals([$author], $result);
        }

    }
?>
