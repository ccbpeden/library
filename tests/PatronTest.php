<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Patron.php";
    // require_once "src/Task.php";
    $server = 'mysql:host=localhost:8889;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
          Patron::deleteAll();

        }
        function test_Patron_setters_getters_constructor()
        {
            // Act
            $title = "Pilgrim's Progress";
            $id = 1;
            $patron = new Patron($title, $id);
            $actual_result = array($patron->getName(), $patron->getId());
            // Assert
                $this->assertEquals([$title, $id], $actual_result);
        }
        function test_Patron_save_getAll_deleteAll()
        {
            // Arrange
            $patron1 = new Patron('John Smith');
            $patron2 = new Patron('Tom Smith');
            $patron3 = new Patron('Jane Smith');
            // Act
            $patron1->save();
            Patron::deleteAll();
            $patron2->save();
            $patron3->save();
            $all_patrons = Patron::getAll();
            // Assert
            $this->assertEquals([$patron2, $patron3], $all_patrons);
        }
        function test_Patron_update()
        {
            // Arrange
            $patron1 = new Patron('Tom Smith');
            $patron1->save();
            // Act
            $patron1->update('Tom Smythe');
            $all_patrons = Patron::getAll();
            // Assert
            $this->assertEquals(
                'Tom Smythe',
                $all_patrons[0]->getName()
            );
        }
        function test_Patron_find()
        {
            // Arrange
            $patron1 = new Patron('John Smith');
            $patron2 = new Patron('Tom Smith');
            $patron1->save();
            $patron2->save();
            // Act
            $result = Patron::find($patron2->getId());
            // Assert
            $this->assertEquals($patron2, $result);
        }
        function testDeletePatron()
        {
            //Arrange
            $patron1 = new Patron('John Smith');
            $patron2 = new Patron('Tom Smith');
            $patron3 = new Patron('Jane Smith');
            $patron1->save();
            $patron2->save();
            $patron3->save();
            // Act
            $patron2->delete();
            //Assert
            $this->assertEquals([$patron1, $patron3], Patron::getAll());
        }





    }
