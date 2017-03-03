<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Book.php";
    require_once "src/Author.php";
    require_once "src/Copy.php";

    $server = 'mysql:host=localhost:8889;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    class CopyTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
            Author::deleteAll();
            Author::deletefromJoinTable();
            Copy::deleteAll();
        }
        function test_Copy_setters_getters_constructor()
        {
            // Act
            $book_id = 1;
            $status = 1;
            $id = 1;
            $copy = new Copy($book_id, $status, $id);
            $actual_result = array($copy->getBook_id(),$copy->getStatus(), $copy->getId());
            // Assert
            $this->assertEquals([$book_id, $status, $id], $actual_result);
        }
        function test_Copy_save_getAll_deleteAll()
        {
            // Arrange
            $copy1 = New Copy(1, 1);
            $copy2 = New Copy(4, 0);
            $copy3 = New Copy(3, 0);

            // Act
            $copy1->save();
            Copy::deleteAll();
            $copy2->save();
            $copy3->save();
            $all_copies = Copy::getAll();
            // Assert
            $this->assertEquals([$copy2, $copy3], $all_copies);
        }
        function test_Copy_update()
        {
            // Arrange
            $copy1 = New Copy(1, 1, 1);
            $copy1->save();
            // Act
            $copy1->update(2, 0);
            $result = array($copy1->getBook_id(), $copy1->getStatus());
            // Assert
            $this->assertEquals([2,0],$result);
        }
        function test_Copy_find()
        {
            // Arrange
            $copy1 = New Copy(1, 1);
            $copy2 = New Copy(2, 0);
            $copy1->save();
            $copy2->save();
            // Act
            $result = Copy::find($copy2->getId());
            // Assert
            $this->assertEquals($copy2, $result);
        }
        function testDeleteCopy()
        {
            //Arrange
            $copy1 = New Copy(1, 1, 1);
            $copy2 = New Copy(2, 0, 2);
            $copy3 = New Copy(3, 1, 3);
            $copy1->save();
            $copy2->save();
            $copy3->save();
            // Act
            $copy2->delete();
            //Assert
            $this->assertEquals([$copy1, $copy3], Copy::getAll());
        }

        function test_getTitle()
        {
            $book = new Book("Peregrine Pickle");
            $book->save();
            $book_id = $book->getId();
            $copy1 = New Copy($book_id, 1, 1);
            $copy1->save();

            $result = $copy1->getTitle();

            $this->assertEquals("Peregrine Pickle", $result);
        }

        function test_getAuthor()
        {
            $authorname = "Peter Pan";
            $author = new Author($authorname);
            $author->save();
            $book = new Book("Peregrine Pickle");
            $book->save();
            $book_id = $book->getId();
            $author->addbook($book_id);
            $copy1 = New Copy($book_id, 1);
            $copy1->save();

            $result = $copy1->getAuthor();

            $this->assertEquals($authorname, $result);

        }
    }
?>
