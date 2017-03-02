<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Author.php";
    // require_once "src/Task.php";
    $server = 'mysql:host=localhost:8889;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
          Author::deleteAll();
          Book::deleteAll();
         Author::deletefromJoinTable();
        }
        function test_Author_setters_getters_constructor()
        {
            // Act
            $title = "Pilgrim's Progress";
            $id = 1;
            $author = new Author($title, $id);
            $actual_result = array($author->getName(), $author->getId());
            // Assert
                $this->assertEquals([$title, $id], $actual_result);
        }
        function test_Author_save_getAll_deleteAll()
        {
            // Arrange
            $author1 = new Author('John Smith');
            $author2 = new Author('Tom Smith');
            $author3 = new Author('Jane Smith');
            // Act
            $author1->save();
            Author::deleteAll();
            $author2->save();
            $author3->save();
            $all_authors = Author::getAll();
            // Assert
            $this->assertEquals([$author2, $author3], $all_authors);
        }
        function test_Author_update()
        {
            // Arrange
            $author1 = new Author('Tom Smith');
            $author1->save();
            // Act
            $author1->update('Tom Smythe');
            $all_authors = Author::getAll();
            // Assert
            $this->assertEquals(
                'Tom Smythe',
                $all_authors[0]->getName()
            );
        }
        function test_Author_find()
        {
            // Arrange
            $author1 = new Author('John Smith');
            $author2 = new Author('Tom Smith');
            $author1->save();
            $author2->save();
            // Act
            $result = Author::find($author2->getId());
            // Assert
            $this->assertEquals($author2, $result);
        }
        function testDeleteAuthor()
        {
            //Arrange
            $author1 = new Author('John Smith');
            $author2 = new Author('Tom Smith');
            $author3 = new Author('Jane Smith');
            $author1->save();
            $author2->save();
            $author3->save();
            // Act
            $author2->delete();
            //Assert
            $this->assertEquals([$author1, $author3], Author::getAll());
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

            $author->addBook($book->getId());
            $author2->addBook($book2->getId());

            $result= $author2->getBooks();

            $this->assertEquals([$book2], $result);
        }

        function test_findbyName()
        {
            $author1 = new Author('John Smith');
            $author2 = new Author('Tom Smith');
            $author3 = new Author('Jane Smith');
            $author1->save();
            $author2->save();
            $author3->save();

            $result = Author::findbyName('Tom Smith');

            $this->assertEquals($author2, $result);

        }

    }
